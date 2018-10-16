<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");
print $_GET;

$HTML_PAGE_TITLE = _("学习经历信息修改保存");
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
   Message("",_("开始日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}
if($END_DATE!="" && !is_date($END_DATE))
{
   Message("",_("结束日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}

$query="UPDATE HR_STAFF_LEARN_EXPERIENCE 
		              SET 
			STAFF_NAME='$STAFF_NAME',
			START_DATE='$START_DATE',
			END_DATE='$END_DATE',
			SCHOOL='$SCHOOL',
			SCHOOL_ADDRESS='$SCHOOL_ADDRESS',
			MAJOR='$MAJOR',
			ACADEMY_DEGREE='$ACADEMY_DEGREE',
			DEGREE='$DEGREE',
			POSITION='$POSITION',
			AWARDING='$AWARDING',
			CERTIFICATES='$CERTIFICATES',
			WITNESS='$WITNESS',
			REMARK='$REMARK',
			ATTACHMENT_ID='$ATTACHMENT_ID',
			ATTACHMENT_NAME='$ATTACHMENT_NAME',
			LAST_UPDATE_TIME='$CUR_TIME' 
		  WHERE L_EXPERIENCE_ID = '$L_EXPERIENCE_ID'";
exequery(TD::conn(),$query);

header("location:index1.php?L_EXPERIENCE_ID=$L_EXPERIENCE_ID&connstatus=1")

?>
</body>
</html>
