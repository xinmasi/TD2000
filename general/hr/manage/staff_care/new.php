<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");


$HTML_PAGE_TITLE = _("新建员工关怀");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet"type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script> 
jQuery(document).ready(function(){      
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
});  
</script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm()
{
   if (getEditorText('CARE_CONTENT').length == 0 && getEditorHtml('CARE_CONTENT') == "" && document.form1.ATTACHMENT_ID_OLD.value == "")
   { alert("<?=_("员工关怀内容不能为空！")?>");
     return (false);
   }
   return (true);
}

function InsertImage(src)
{
   AddImage2Editor('CARE_CONTENT', src);
}
function sendForm()
{
  if(CheckForm())
     document.form1.submit();
}
function upload_attach()
{
  if(CheckForm())
   {   
     document.form1.submit();
   }
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
    URL="delete_attach.php?CARE_ID=<?=$CARE_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
    window.location=URL;
  }
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建员工关怀")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>

<form enctype="multipart/form-data" action="add.php"  method="post" name="form1" id="form1" onSubmit="return CheckForm();">
<table class="TableBlock" width="80%" align="center">
   <tr>
      <td nowrap class="TableData"><?=_("关怀类型：")?></td>
      <td class="TableData" >
        <select name="CARE_TYPE" style="background: white;" title="<?=_("员工关怀类型可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
          <option value=""><?=_("员工关怀类型")?></option>
          <?=hrms_code_list("HR_STAFF_CARE","")?>
        </select>
      </td>
      <td nowrap class="TableData"><?=_("关怀开支费用/人：")?></td>
      <td class="TableData">
        <INPUT type="text"name="CARE_FEES"  class="BigInput validate[custom[money]]" data-prompt-position="centerRight:20,-6" size="12" value="<?=$CARE_FEES?>">(<?=_("元")?>)&nbsp;
      </td>    
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("被关怀员工：")?></td>
      <td class="TableData">
        <input type="hidden" name="BY_CARE_STAFFS" value="<?=$BY_CARE_STAFFS?>">
        <textarea cols=40 name="BY_CARE_NAME" rows=2 class="BigStatic validate[required]" data-prompt-position="topRight:0,-8"  wrap="yes" readonly></textarea>&nbsp;
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','BY_CARE_STAFFS', 'BY_CARE_NAME','1')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('BY_CARE_STAFFS', 'BY_CARE_NAME')"><?=_("清空")?></a>
      </td>
      <td nowrap class="TableData"> <?=_("关怀日期：")?></td>
      <td class="TableData">
        <input type="text" name="CARE_DATE" size="12" maxlength="10" class="BigInput validate[required]" data-prompt-position="centerRight:0,-8" value="<?=$CARE_DATE?>" onClick="WdatePicker()"/>
      </td>
    </tr>
     
    <tr>
      <td nowrap class="TableData"><?=_("关怀效果：")?></td>
      <td class="TableData" colspan=3>
        <textarea name="CARE_EFFECTS" cols="60" rows="3" class="BigInput" wrap="on"><?=$CARE_EFFECTS?></textarea>
      </td>
    </tr>      
    <tr>
      <td nowrap class="TableData"><?=_("参与人：")?></td>
      <td class="TableData" colspan=3>
        <input type="hidden" name="PARTICIPANTS" value="">
        <textarea cols=60 name="PARTICIPANTS_NAME" rows=2 class="BigStatic validate[required]" data-prompt-position="topRight:0,-8" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','PARTICIPANTS', 'PARTICIPANTS_NAME','1')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PARTICIPANTS', 'PARTICIPANTS_NAME')"><?=_("清空")?></a>
      </td>
    </tr>    
   <tr height="25" id="attachment1">
      <td nowrap class="TableData" ><span id="ATTACH_LABEL"><?=_("附件上传：")?></span></td>
      <td class="TableData"colspan=3>
        <script>ShowAddFile();ShowAddImage();</script>
        <script>$("ATTACHMENT_upload_div").innerHTML='<a href="javascript:upload_attach();"><?=_("上传附件")?></a>'</script>
        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData"> <?=_("提醒：")?></td>
      <td class="TableData" colspan=3>
<?=sms_remind(57);?>
      </td>
   </tr>
    <tr id="EDITOR">
      <td class="TableData" colspan="4"> <?=_("关怀内容：")?>
<?
$editor = new Editor('CARE_CONTENT') ;
$editor->Height = '300';
$editor->Config = array("EditorAreaStyles" => "body{font-size:20pt;}","model_type" => "14");
$editor->Value = $CARE_CONTENT ;
//$editor->Config = array('model_type' => '14') ;
$editor->Create() ;
?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
        <input type="submit" value="<?=_("保存")?>" class="BigButton">
      </td>
    </tr>
  </table>
</form>
</body>
</html>