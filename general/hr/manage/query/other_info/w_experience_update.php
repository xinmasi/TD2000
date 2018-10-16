<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("工作经历修改保存");
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
//-----------------合法性校验-------------------------------------
if($START_DATE!="" && !is_date($START_DATE))
{
   Message("",_("工作开始日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}
if($END_DATE!="" && !is_date($END_DATE))
{
   Message("",_("工作结束日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}

$query="UPDATE HR_STAFF_WORK_EXPERIENCE 
		              SET 
			STAFF_NAME='$STAFF_NAME',
			START_DATE='$START_DATE',
			END_DATE='$END_DATE',
			WORK_UNIT='$WORK_UNIT',
			MOBILE='$MOBILE',
			WORK_BRANCH='$WORK_BRANCH',
			POST_OF_JOB='$POST_OF_JOB',
			WORK_CONTENT='$WORK_CONTENT',
			KEY_PERFORMANCE='$KEY_PERFORMANCE',
			REASON_FOR_LEAVING='$REASON_FOR_LEAVING',
			WITNESS='$WITNESS',
			REMARK='$REMARK',
			ATTACHMENT_ID='$ATTACHMENT_ID',
			ATTACHMENT_NAME='$ATTACHMENT_NAME',
			LAST_UPDATE_TIME='$CUR_TIME' 
		  WHERE W_EXPERIENCE_ID = '$W_EXPERIENCE_ID'";
exequery(TD::conn(),$query);

Message("",_("修改成功！"));
Button_Back();
exit;
?>
</body>
</html>
