<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = $USER_NAME;
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>

<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm()
{
   if(document.form1.PPLAN_CONTENT.value=="")
   { alert("<?=_("计划任务不能为空！")?>");
     return (false);
   }

   return true;
}

function save()
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
     URL="delete_person_attach.php?AUTO_PERSON=<?=$AUTO_PERSON?>&USER_ID=<?=$USER_ID?>&PLAN_ID=<?=$PLAN_ID?>&USER_NAME=<?=urlencode($USER_NAME)?>&NAME=<?=urlencode($NAME)?>&URL_BEGIN_DATE=<?=$URL_BEGIN_DATE?>&URL_END_DATE=<?=$URL_END_DATE?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
     window.location=URL;
  }
}   

</script>


<body class="bodycolor">
<?
$query = "SELECT AUTO_PERSON,PBEGEI_DATE,PEND_DATE,PPLAN_CONTENT,PUSE_RESOURCE,ATTACHMENT_ID,ATTACHMENT_NAME from WORK_PERSON where AUTO_PERSON='$AUTO_PERSON'";
$cursor= exequery(TD::conn(),$query);
$COUNT=0;
if($ROW=mysql_fetch_array($cursor))
{
  $AUTO_PERSON=$ROW["AUTO_PERSON"];
  $PBEGEI_DATE=$ROW["PBEGEI_DATE"];
  $PEND_DATE=$ROW["PEND_DATE"];
  $PPLAN_CONTENT=$ROW["PPLAN_CONTENT"];
  $PUSE_RESOURCE=$ROW["PUSE_RESOURCE"];
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"]; 	 	  
  

  if($PEND_DATE=="0000-00-00")
     $PEND_DATE="";
}
?>
<table border="0" width="95%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" align="absmiddle" width="22" height="20"><span class="big3"> <?=_("修改计划任务")?>(<?=$USER_NAME?>)</span>
    </td>
  </tr>
</table>
<table class="TableBlock" width="95%" align="center">
  <form enctype="multipart/form-data" action="sub_resource.php"  method="post" name="form1">
    <tr>
      <td class="TableContent" width="10%"> <?=_("开始时间：")?></td>
      <td class="TableData" width="20%">
        <input type="text" name="PBEGEI_DATE" size="10" maxlength="10" class="BigInput" value="<?=$PBEGEI_DATE?>" onClick="WdatePicker()">
      
      </td>
      <td class="TableContent" width="10%"> <?=_("结束时间：")?></td>
      <td class="TableData">
        <input type="text" name="PEND_DATE" size="10" maxlength="10" class="BigInput" value="<?=$PEND_DATE?>" onClick="WdatePicker()">
     
      </td>
    </tr>
    <tr>
      <td class="TableContent"> <?=_("计划任务：")?></td>
      <td class="TableData" colspan="3">
        <textarea cols=65 name="PPLAN_CONTENT" rows=5 class="BigINPUT" wrap="yes"><?=$PPLAN_CONTENT?></textarea>
      </td>
    </tr>
    <tr>
      <td class="TableContent"> <?=_("相关资源：")?></td>
      <td class="TableData" colspan="3">
        <textarea cols=65 name="PUSE_RESOURCE" rows=5 class="BigINPUT" wrap="yes"><?=$PUSE_RESOURCE?></textarea>
      </td>
    </tr>
   <tr>
      <td nowrap class="TableContent"><?=_("附件文档：")?></td>
      <td nowrap class="TableData" colspan="3">
<?
      if($ATTACHMENT_ID=="")
         echo _("无附件");
      else
         echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,0,1,1,1,1,1,0,0);
?>
      </td>
    </tr>
    <tr height="25">
      <td nowrap class="TableContent"><?=_("附件选择：")?></td>
      <td class="TableData" colspan="3">
         <script>ShowAddFile();</script>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableContent"> <?=_("提醒：")?></td>
      <td class="TableData" colspan="3">
<?=sms_remind(12);?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="4">
      	<input type="hidden" value="<?=$AUTO_PERSON?>" name="AUTO_PERSON">
      	<input type="hidden" value="<?=$PLAN_ID?>" name="PLAN_ID">
      	<input type="hidden" value="<?=$NAME?>" name="NAME">
      	<input type="hidden" value="<?=$URL_BEGIN_DATE?>" name="URL_BEGIN_DATE">
      	<input type="hidden" value="<?=$URL_END_DATE?>" name="URL_END_DATE">
      	<input type="hidden" value="<?=$USER_ID?>" name="USER_ID">
      	<input type="hidden" value="<?=$USER_NAME?>" name="USER_NAME">
        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">       	
        <input type="button" value="<?=_("保存")?>" class="BigButton" onclick="save();">&nbsp;&nbsp;
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="window.close();">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="history.go(-1);">
      </td>
    </tr>
  </form>
</table>

</body>
</html>