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
//-- 保存 --
$query="update HR_RECRUIT_PLAN set
          APPROVE_DATE='$APPROVE_DATE',
          APPROVE_COMMENT='$APPROVE_COMMENT',
          PLAN_STATUS='$PLAN_STATUS'";
$query.=" where PLAN_ID='$PLAN_ID'";
exequery(TD::conn(),$query);
//------- 事务提醒 --------
$query="select CREATE_USER_ID, PLAN_NAME from HR_RECRUIT_PLAN where PLAN_ID='$PLAN_ID'";
$cursor=exequery(TD::conn(),$query);
$ROW=mysql_fetch_array($cursor);
$REMAND_USERS=$ROW["CREATE_USER_ID"];
$PLAN_NAME=$ROW["PLAN_NAME"];
if($PLAN_STATUS==1)
{
    $REMIND_URL="hr/recruit/plan/plan_detail.php?PLAN_ID=".$PLAN_ID;
    $SMS_CONTENT=sprintf(_("%s 已审批通过您的招聘计划 %s。"), $_SESSION["LOGIN_USER_NAME"], $PLAN_NAME);
    send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,62,$SMS_CONTENT,$REMIND_URL,$PLAN_ID);
}

if($PLAN_STATUS==1)
{
    $SMS_CONTENT=sprintf(_("%s 已审批通过您的招聘计划 %s。"), $_SESSION["LOGIN_USER_NAME"], $PLAN_NAME);
    send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,62);
}
if($PLAN_STATUS==2)
{
    $REMIND_URL="hr/recruit/plan/plan_detail.php?PLAN_ID=".$PLAN_ID;
    $SMS_CONTENT=sprintf(_("%s 已驳回您的招聘计划 %s。"), $_SESSION["LOGIN_USER_NAME"], $PLAN_NAME);
    send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,62,$SMS_CONTENT,$REMIND_URL,$PLAN_ID);
}

if($PLAN_STATUS==2)
{
    $SMS_CONTENT=sprintf(_("%s 已驳回您的招聘计划 %s。"), $_SESSION["LOGIN_USER_NAME"], $PLAN_NAME);
    send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,62);
}
header("location: index1.php?connstatus=1");
?>

</body>
</html>
