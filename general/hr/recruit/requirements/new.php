<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/editor.php");

$DEPT_NAME=GetDeptNameById($_SESSION["LOGIN_DEPT_ID"]);

$HTML_PAGE_TITLE = _("新建需求信息");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/mytable.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function check_requ_no(id)
{
   if(id=="")
      return;
   $("requ_no_msg").innerText="<img src='<?=MYOA_STATIC_SERVER?>/static/images/loading_16.gif' align='absMiddle'> <?=_("检查中，请稍候……")?>";
   _get("check_unique.php","REQU_NO="+id, show_msg);
}
function show_msg(req)
{
   if(req.status==200)
   {
      if(req.responseText=="SUCCESS")
      {
         $("requ_no_msg").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/correct.gif' align='absMiddle'>";
       	 document.form1.PD.value="1";
       }
      else
      {
         $("requ_no_msg").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/error.gif' align='absMiddle'> <?=_("该需求编号已存在")?>";
         document.form1.PD.value="";
      }
   }
   else
   {
      $("user_id_msg").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/error.gif' align='absMiddle'> <?=_("错误：")?>"+req.status;
   }
}
function CheckForm()
{
	
   if(document.form1.REQU_NO.value=="")
   { 
      alert("<?=_("需求编号不能为空！")?>");
      return (false);
   }
   if(document.form1.REQU_NO.value!=document.form1.REQU_NO.value.replace(/\D/g,''))
   {
      alert("<?=_("需求编号只能为数字！")?>");
      return (false);
   }
    if(document.form1.PD.value=="")
   {
     alert("<?=_("该需求编号已存在")?>");
     document.form1.REQU_NO.focus();
      return (false);
   }
   if(document.form1.REQU_JOB.value=="")
   { 
      alert("<?=_("需求岗位不能为空！")?>");
      return (false);
   }
   if(document.form1.REQU_TIME.value=="")
   { 
      alert("<?=_("请选择用工日期！")?>");
      return (false);
   }
   if(document.form1.REQU_NUM.value=="")
   { 
      alert("<?=_("需求人数不能为空！")?>");
      return (false);
   }
   if (getEditorHtml('REQU_REQUIRES')== "" && getEditorText('REQU_REQUIRES').length == 0)
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

<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建需求信息")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="add.php"  method="post" id="form1" name="form1" onSubmit="return CheckForm();">
<table class="TableBlock" width="60%" align="center">
   <tr>
    	 <td nowrap class="TableData"><?=_("需求编号：")?></td>
      <td class="TableData" >
        <INPUT type="text"name="REQU_NO" class="BigInput" size="15" value="<?=$REQU_NO?>"  onblur="check_requ_no(this.value)"><span id="requ_no_msg"></span>
      </td>
       <td nowrap class="TableData"><?=_("需求岗位：")?></td>
      <td class="TableData">
        <INPUT type="text"name="REQU_JOB" class="BigInput" size="15" value="<?=$REQU_JOB?>">
      </td>
   </tr>
    <tr>
    	<td nowrap class="TableData"><?=_("用工日期：")?></td>
      <td class="TableData">
       <input type="text" name="REQU_TIME" size="15" maxlength="10" class="BigInput" value="<?=$REQU_TIME?>" onClick="WdatePicker()"/>
      </td>
    	<td nowrap class="TableData"><?=_("需求人数：")?></td>
      <td class="TableData">
       <INPUT type="text"name="REQU_NUM" class="BigInput" size="15" value="<?=$REQU_NUM?>">&nbsp;<?=_("人")?>
      </td>
    </tr>
       <tr>
      <td nowrap class="TableData"><?=_("需求部门：")?></td>
      <td class="TableData"  colspan=3>
        <input type="hidden" name="REQU_DEPT" value="<?=$_SESSION["LOGIN_DEPT_ID"]?>,">
        <textarea cols=53 name=REQU_DEPT_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$DEPT_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('','REQU_DEPT', 'REQU_DEPT_NAME')"><?=_("添加")?></a>
       <a href="javascript:;" class="orgClear" onClick="ClearUser('REQU_DEPT', 'REQU_DEPT_NAME')"><?=_("清空")?></a>
      </td>
   </tr>
    <tr>
      <td nowrap class="TableData"><?=_("备注：")?></td>
      <td class="TableData" colspan=3>
        <textarea name="REMARK" cols="66" rows="3" class="BigInput validate[maxSize[100]]" data-prompt-position="centerRight:0,18" value=""><?=$REMARK?></textarea>
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
        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID ?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME ?>">
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
        <input type="hidden" name="PD" value="<?=$PD?>">
        <input type="submit" value="<?=_("保存")?>" class="BigButton">
      </td>
    </tr>
  </table>
</form>
</body>
</html>