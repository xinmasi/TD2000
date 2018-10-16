<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
$query  = "SELECT CAR_USER, HISTORT, ATTACH_ID, ATTACH_NAME  from VEHICLE where V_ID='$V_ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $CAR_USER		= $ROW["CAR_USER"];
   $HISTORT		    = $ROW["HISTORT"];
   $ATTACHMENT_ID   = $ROW["ATTACH_ID"];
   $ATTACHMENT_NAME = $ROW["ATTACH_NAME"];
} 

$HTML_PAGE_TITLE = _("车辆档案");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";

function upload_attach()
{
	document.form1.submit();
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
    URL="delete_attach.php?V_ID=<?=$V_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
    window.location=URL;
  }
}
</script>


<body class="bodycolor">
<form enctype="multipart/form-data" action="update_archives.php" method="post" name="form1">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" HEIGHT="20"><span class="big3"> <?=_("车辆档案")?></span>
    </td>
  </tr>
</table>
<form enctype="multipart/form-data" action="notes_update.php" method="post" name="form1">
<table class="TableBlock" align="center" width="90%">
    <tr>
      <td nowrap class="TableContent" width="20%"><?=_("当前使用人：")?></td>
      <td class="TableData">
       <input type="hidden" name="TO_ID" value=""> 	
        <input type="text" name="TO_NAME" size="13" class="BigInput" value="<?=$CAR_USER?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('90','','TO_ID', 'TO_NAME')"><?=_("选择")?></a><a href = "javascript:;" onClick ="ClearUser('TO_ID','TO_NAME')">&nbsp;<?=_("清空")?></a></td>
	 </tr>
	 <tr>
      <td nowrap class="TableContent" width="20%"> <?=_("历史记录：")?></td>
      <td class="TableData">
       <textarea name="HISTORT" class="BigInput" cols="74" rows="5"><?=$HISTORT?></textarea>&nbsp </td>	  	
    </tr>
	<tr>
      <td nowrap class="TableContent" width="20%"> <?=_("附件：")?></td>
      <td class="TableData">
	  <?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1)?>
        <script>ShowAddFile();</script>
        <script>$("ATTACHMENT_upload_div").innerHTML='<a href="javascript:upload_attach();"><?=_("上传附件")?></a>'</script>
        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">&nbsp </td>	  	
    </tr>
	<tr class="TableControl">
      <td nowrap colspan="2" align="center">
		<input type="hidden" value="<?=$V_ID?>" name="V_ID">
		<input type="hidden" name="PUBLISH" value="">
        <input type="button" value="<?=_("保存")?>" class="BigButton" onClick="submit();">&nbsp;&nbsp;
		<input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();">&nbsp;&nbsp;
		
      </td>
    </tr>
</table>
</form>
</body>