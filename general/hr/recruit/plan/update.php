<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

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
//-- 保存 --
$query="update HR_RECRUIT_PLAN set
          PLAN_NAME='$PLAN_NAME',
          PLAN_DITCH='$PLAN_DITCH',
          PLAN_BCWS='$PLAN_BCWS',
          PLAN_RECR_NO='$PLAN_RECR_NO',
          START_DATE='$START_DATE',
          END_DATE='$END_DATE',
          RECRUIT_DIRECTION='$RECRUIT_DIRECTION',
          RECRUIT_REMARK='$RECRUIT_REMARK',
          APPROVE_PERSON='$APPROVE_PERSON',
          PLAN_STATUS='$PLAN_STATUS',
          ATTACHMENT_ID='$ATTACHMENT_ID',
          ATTACHMENT_NAME='$ATTACHMENT_NAME'";
$query.=" where PLAN_ID='$PLAN_ID'";
exequery(TD::conn(),$query);

//------- 事务提醒 --------
$REMAND_USERS = $APPROVE_PERSON;
if($PLAN_STATUS==0 && $SMS_REMIND=="on")
{
    $REMIND_URL="hr/recruit/plan_approval/index1.php?PLAN_ID=".$PLAN_ID;
    $SMS_CONTENT=$_SESSION["LOGIN_USER_NAME"]._("提交招聘计划，请批示！");
    send_sms($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,62,$SMS_CONTENT,$REMIND_URL,$PLAN_ID);
}

if($PLAN_STATUS==0 && $SMS2_REMIND=="on")
{
    $SMS_CONTENT=$_SESSION["LOGIN_USER_NAME"]._("提交招聘计划，请批示！");
    send_mobile_sms_user($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,62);
}


Message(_("提示"),$PLAN_NAME._(" 的招聘计划已保存。"));
header("location: index1.php?connstatus=1");
?>
</body>
</html>
