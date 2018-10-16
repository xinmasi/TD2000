<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("修改保存");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if(count($_FILES)>1)
{
   $ATTACHMENTS=upload();
   $ATTACHMENT_ID=$ATTACHMENT_ID_OLD.$ATTACHMENTS["ID"];
   $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD.$ATTACHMENTS["NAME"];
}
else
{
   $ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
   $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------校验-------------------------------------
if($BIRTHDAY!="" && !is_date($BIRTHDAY))
{
   Message("",_("出生日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}

$query="UPDATE HR_STAFF_RELATIVES SET MEMBER='$MEMBER',RELATIONSHIP='$RELATIONSHIP',BIRTHDAY='$BIRTHDAY',POLITICS='$POLITICS',WORK_UNIT='$WORK_UNIT',UNIT_ADDRESS='$UNIT_ADDRESS',POST_OF_JOB='$POST_OF_JOB',OFFICE_TEL='$OFFICE_TEL',HOME_ADDRESS='$HOME_ADDRESS',HOME_TEL='$HOME_TEL',JOB_OCCUPATION='$JOB_OCCUPATION',STAFF_NAME='$STAFF_NAME',PERSONAL_TEL='$PERSONAL_TEL',REMARK='$REMARK',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME',LAST_UPDATE_TIME='$CUR_TIME' WHERE RELATIVES_ID = '$RELATIVES_ID'";
exequery(TD::conn(),$query);

Message("",_("修改成功！"));
parse_str($_SERVER["HTTP_REFERER"], $tmp_url);
$paras = strpos($_SERVER["HTTP_REFERER"], "?") ? isset($tmp_url["connstatus"]) ? $_SERVER["HTTP_REFERER"] : $_SERVER["HTTP_REFERER"]."&connstatus=1" : $paras = $_SERVER["HTTP_REFERER"]."?connstatus=1";

?>

<center>
		<input type="button" class="BigButton" value="<?=_("返回")?>" onclick="window.location.href='<?=$paras?>'"/>
</center>
</body>
</html>
