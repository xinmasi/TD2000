<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/editor.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("招聘需求修改");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
<script Language="JavaScript">
jQuery(document).ready(function(){
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
});
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm()
{

   if(getEditorText('REQU_REQUIRES').length==0 &&  getEditorHtml('REQU_REQUIRES')=="")
   { alert("<?=_("岗位要求不能为空！")?>");
     return (false);
   }
 return (true);
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
    URL="delete_attach.php?REQUIREMENTS_ID=<?=$REQUIREMENTS_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
    window.location=URL;
  }
}
</script>


<?
$query="select * from HR_RECRUIT_REQUIREMENTS where REQUIREMENTS_ID='$REQUIREMENTS_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  $REQUIREMENTS_ID=$ROW["REQUIREMENTS_ID"];
  $USER_ID=$ROW["USER_ID"];
	$DEPT_ID=$ROW["DEPT_ID"];
  $REQU_NO=$ROW["REQU_NO"];
  $REQU_DEPT=$ROW["REQU_DEPT"];
  $REQU_JOB=$ROW["REQU_JOB"];
  $REQU_NUM=$ROW["REQU_NUM"];
  $REQU_REQUIRES=$ROW["REQU_REQUIRES"];
  $REQU_TIME=$ROW["REQU_TIME"];
  $REMARK=$ROW["REMARK"];
	$ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME =$ROW["ATTACHMENT_NAME"];
  $ADD_TIME =$ROW["ADD_TIME"];

  if($REQU_DEPT=="ALL_DEPT")
     $REQU_DEPT_NAME=_("全体部门");
  else
     $REQU_DEPT_NAME=substr(GetDeptNameById($REQU_DEPT),0,-1);
}

?>

<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("编辑需求信息")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<form action="update.php"  method="post" id="form1" name="form1" enctype="multipart/form-data" onsubmit="return CheckForm()">
 <table class="TableBlock" width="70%" align="center">
   <tr>
    	 <td nowrap class="TableData"><?=_("需求编号：")?></td>
      <td class="TableData" >
        <INPUT type="text"name="REQU_NO" class="BigInput validate[required,custom[onlyNumberSp]]" data-prompt-position="centerRight:0,-8" size="15" value="<?=$REQU_NO?>">
      </td>
       <td nowrap class="TableData"><?=_("需求岗位：")?></td>
      <td class="TableData">
        <INPUT type="text"name="REQU_JOB" class="BigInput validate[required]" data-prompt-position="centerRight:0,-8" size="15" value="<?=$REQU_JOB?>">
      </td>
   </tr>
    <tr>
    	<td nowrap class="TableData"><?=_("用工日期：")?></td>
      <td class="TableData">
       <input type="text" name="REQU_TIME" size="15" maxlength="10" class="BigInput validate[required]" data-prompt-position="centerRight:0,-8" value="<?=$REQU_TIME?>" onClick="WdatePicker()"/>
      </td>
    	<td nowrap class="TableData"><?=_("需求人数：")?></td>
      <td class="TableData">
       <INPUT type="text"name="REQU_NUM" class="BigInput validate[required,custom[onlyNumberSp]]" data-prompt-position="centerRight:15,-8" size="15" value="<?=$REQU_NUM?>">&nbsp;<?=_("人")?>
      </td>
    </tr>
       <tr>
      <td nowrap class="TableData"><?=_("需求部门：")?></td>
      <td class="TableData"  colspan=3>
        <input type="hidden" name="REQU_DEPT" value="<?=$REQU_DEPT?>">
        <textarea cols=53 name=REQU_DEPT_NAME rows=2 class="BigStatic validate[required]" data-prompt-position="centerRight:0,-8" wrap="yes" readonly><?=$REQU_DEPT_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('','REQU_DEPT', 'REQU_DEPT_NAME')"><?=_("添加")?></a>
       <a href="javascript:;" class="orgClear" onClick="ClearUser('REQU_DEPT', 'REQU_DEPT_NAME')"><?=_("清空")?></a>
      </td>
   </tr>
    <tr>
      <td nowrap class="TableData"><?=_("备注：")?></td>
      <td class="TableData" colspan=3>
        <textarea name="REMARK" cols="66" rows="3" class="BigInput validate[maxSize[60]]" data-prompt-position="centerRight:0,18" value=""><?=$REMARK?></textarea>
      </td>
    </tr>
    <tr class="TableData" id="attachment2">
      <td nowrap><?=_("附件文档：")?></td>
      <td nowrap colspan=3>
<?
if($ATTACHMENT_ID=="")
   echo _("无附件");
else
   echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1);
?>
      </td>
   </tr>
   <tr height="25" id="attachment1">
      <td nowrap class="TableData"><span id="ATTACH_LABEL"><?=_("附件上传：")?></span></td>
      <td class="TableData" colspan=3>
        <script>ShowAddFile();</script>
        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
      </td>
   </tr>
   <tr>
      <td nowrap class="TableData"> <?=_("提醒：")?></td>
      <td class="TableData" colspan=3>
<?=sms_remind(60);?>
      </td>
   </tr>
       <tr id="EDITOR">
      <td class="TableData" colspan="4"> <?=_("岗位要求：")?>
<?
$editor = new Editor('REQU_REQUIRES') ;
$editor->Height = '300';
$editor->Value = $REQU_REQUIRES ;
$editor->Config = array('model_type' => '15') ;
$editor->Create() ;
?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
      	<input type="hidden" value="<?=$REQUIREMENTS_ID?>" name="REQUIREMENTS_ID">
        <input type="submit" value="<?=_("保存")?>" class="BigButton">
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index1.php?start=<?=$start?>'">
      </td>
    </tr>
  </table>
</form>

</body>
</html>