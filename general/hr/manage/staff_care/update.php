<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("修改保存");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if(count($_FILES)>1)
{
   $ATTACHMENTS=upload();
   $CARE_CONTENT=ReplaceImageSrc($CARE_CONTENT, $ATTACHMENTS);
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

$C = preg_match('/<img.*?\ssrc=\\\"\/inc\/attach.php\?(.*)MODULE=upload_temp/i',$CARE_CONTENT);
$CARE_CONTENT = replace_attach_url($CARE_CONTENT);
if($C==1)
{
    $ATTACHMENT_ID=move_attach($ATTACHMENT_ID,$ATTACHMENT_NAME,"","",true).",";
}

$CUR_TIME=date("Y-m-d H:i:s",time());
$REMAND_USERS = $PARTICIPANTS.$BY_CARE_STAFFS;
//-----------------校验-------------------------------------
if($CARE_DATE!="" && !is_date($CARE_DATE))
{
   Message("",_("关怀日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}
$SEND_TIME=$CARE_DATE.' 08:00:00';
$REMAND_USERS = $PARTICIPANTS.$BY_CARE_STAFFS;
//------- 事务提醒 --------
if($SMS_REMIND=="on")
{
  $REMIND_URL="ipanel/hr/care_detail.php?CARE_ID=".$CARE_ID;
  $SMS_CONTENT=_("请查看员工关怀！");
  send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,57,$SMS_CONTENT,$REMIND_URL,$CARE_ID);
}

if($SMS2_REMIND=="on")
{
   $SMS_CONTENT=_("OA员工关怀:").csubstr($CARE_CONTENT,0,50);
   send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,57);
}

$query="UPDATE HR_STAFF_CARE SET BY_CARE_STAFFS='$BY_CARE_STAFFS',CARE_DATE='$CARE_DATE',CARE_CONTENT='$CARE_CONTENT',PARTICIPANTS='$PARTICIPANTS',CARE_EFFECTS='$CARE_EFFECTS',CARE_FEES='$CARE_FEES',CARE_TYPE='$CARE_TYPE',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME',LAST_UPDATE_TIME='$CUR_TIME' WHERE CARE_ID = '$CARE_ID'";
exequery(TD::conn(),$query);

if($OP==0)
  header("location:modify.php?CARE_ID=$CARE_ID");
else
{
header("location:index1.php?CARE_ID=$CARE_ID&connstatus=1");
}
?>
</body>
</html>
