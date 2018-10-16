<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");
print $_GET;

$HTML_PAGE_TITLE = _("劳动技能信息修改保存");
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
//------------------- 劳动技能信息 -----------------------
if($SKILLS_CERTIFICATE==_("是"))
   $SKILLS_CERTIFICATE=1;
if($SKILLS_CERTIFICATE==_("否"))
   $SKILLS_CERTIFICATE=0;
   
if($SPECIAL_WORK==_("是"))
   $SPECIAL_WORK=1;
if($SPECIAL_WORK==_("否"))
   $SPECIAL_WORK=0;
//-----------------合法性校验-------------------------------------
if($ISSUE_DATE!="" && !is_date($ISSUE_DATE))
{
   Message("",_("发证日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}
if($EXPIRE_DATE!="" && !is_date($EXPIRE_DATE))
{
   Message("",_("证书到期日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}

$query="UPDATE HR_STAFF_LABOR_SKILLS 
		              SET 
			STAFF_NAME='$STAFF_NAME',
			ABILITY_NAME='$ABILITY_NAME',
			SPECIAL_WORK='$SPECIAL_WORK',
			SKILLS_LEVEL='$SKILLS_LEVEL',
			SKILLS_CERTIFICATE='$SKILLS_CERTIFICATE',
			ISSUE_DATE='$ISSUE_DATE',
			EXPIRE_DATE='$EXPIRE_DATE',
			EXPIRES='$EXPIRES',
			ISSUING_AUTHORITY='$ISSUING_AUTHORITY',
			REMARK='$REMARK',
			ATTACHMENT_ID='$ATTACHMENT_ID',
			ATTACHMENT_NAME='$ATTACHMENT_NAME',
			LAST_UPDATE_TIME='$CUR_TIME' 
		  WHERE SKILLS_ID = '$SKILLS_ID'";
exequery(TD::conn(),$query);

header("location:index1.php?SKILLS_ID=$SKILLS_ID&connstatus=1")

?>
</body>
</html>
