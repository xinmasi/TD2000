<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("培训计划审批");
include_once("inc/header.inc.php");
include_once("inc/utility.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$ASSESSING_STATUS0 = $ASSESSING_STATUS;
?>
<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
if($PASS==1)
{
    $ASSESSING_STATUS = 1;
    $MSG=_("审批通过!");
    $ASSESSING_VIEW='<font color="green">'._("批准").'</font> <b>by '.$_SESSION["LOGIN_USER_NAME"]." ".$CUR_TIME."</b><br/>".$ASSESSING_VIEW."<br/>";
}
else
{
    $ASSESSING_STATUS = 2;
    $MSG=_("审批未通过!");
    $ASSESSING_VIEW='<font color="red">'._("驳回").'</font> <b>by '.$_SESSION["LOGIN_USER_NAME"]." ".$CUR_TIME."</b><br/>".$ASSESSING_VIEW."<br/>";
}
$query = "update HR_TRAINING_PLAN set ASSESSING_STATUS='$ASSESSING_STATUS',ASSESSING_TIME='$CUR_TIME',ASSESSING_VIEW=CONCAT(ASSESSING_VIEW,'$ASSESSING_VIEW') WHERE T_PLAN_ID='$T_PLAN_ID'";
exequery(TD::conn(),$query);
//------- 事务提醒 --------
$query  = "select * from HR_TRAINING_PLAN where T_PLAN_ID='$T_PLAN_ID'";
$cursor = exequery(TD::conn(),$query);
$ROW = mysql_fetch_array($cursor);

$REMAND_USERS      = $ROW["CREATE_USER_ID"];
$T_PLAN_NAME       = $ROW["T_PLAN_NAME"];
$T_JOIN_PERSON     = $ROW["T_JOIN_PERSON"];//参与培训人员
$CHARGE_PERSON     = $ROW["CHARGE_PERSON"];//负责人
$COURSE_START_TIME = $ROW["COURSE_START_TIME"];//培训开始时间
$T_ADDRESS         = $ROW["T_ADDRESS"];//培训地点
$T_COURSE_NAME     = $ROW["T_COURSE_NAME"];	//培训名称
$CREATE_USER_ID    = $ROW["CREATE_USER_ID"];

$query1  = "SELECT USER_NAME FROM user WHERE USER_ID='$CHARGE_PERSON'";
$cursor1 = exequery(TD::conn(),$query1);
if($ROW1 = mysql_fetch_array($cursor1))
{
    $USER_NAME = $ROW1["USER_NAME"];
}


if($ASSESSING_STATUS==1)
{
    $REMIND_URL  = "hr/training/plan/index1.php";
    $SMS_CONTENT = sprintf(_("%s 已审批通过您的培训计划 %s。"), $_SESSION["LOGIN_USER_NAME"], $T_PLAN_NAME);
    send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,61,$SMS_CONTENT,$REMIND_URL);
    //参与培训人员/负责人事务提醒
    if($T_JOIN_PERSON !="")
    {
        $CONTENT = sprintf(_("通知您于%s在%s参加培训，培训课程：%s"),$COURSE_START_TIME,$T_ADDRESS,$T_COURSE_NAME);
        $REMIND_URL1 = "hr/training/plan/plan_detail.php?T_PLAN_ID=$T_PLAN_ID";
        send_sms("",$CREATE_USER_ID,$T_JOIN_PERSON,61,$USER_NAME.$CONTENT,$REMIND_URL1,$T_PLAN_ID);
        if($CHARGE_PERSON!=$REMAND_USERS)
        {
            $CONTENTS = sprintf(_("您负责的培训计划 %s已经通过审批。"), $T_PLAN_NAME);
            send_sms("",$CREATE_USER_ID,$CHARGE_PERSON,61,$CONTENTS,$REMIND_URL1,$T_PLAN_ID);
        }
    }
}

if($ASSESSING_STATUS==1)
{
    $SMS_CONTENT=sprintf(_("%s 已审批通过您的培训计划 %s。"), $_SESSION["LOGIN_USER_NAME"], $T_PLAN_NAME);
    send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,61);
    //参与培训人员短信提醒
    if($T_JOIN_PERSON !="")
    {
        $CONTENT = sprintf(_("通知您于%s在%s参加培训，培训课程：%s"),$COURSE_START_TIME,$T_ADDRESS,$T_COURSE_NAME);
        send_mobile_sms_user("",$CREATE_USER_ID,$T_JOIN_PERSON,$USER_NAME.$CONTENT,61);
        if($CHARGE_PERSON!=$REMAND_USERS)
        {
            $CONTENTS = sprintf(_("您负责的培训计划 %s已经通过审批。"), $T_PLAN_NAME);
            send_mobile_sms_user("",$CREATE_USER_ID,$CHARGE_PERSON,$CONTENTS,61);
        }
    }
}
if($ASSESSING_STATUS==2)
{
    $REMIND_URL="hr/training/plan/index1.php";
    $SMS_CONTENT=sprintf(_("%s 已驳回您的培训计划 %s。"), $_SESSION["LOGIN_USER_NAME"], $T_PLAN_NAME);
    send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,61,$SMS_CONTENT,$REMIND_URL);
}

if($ASSESSING_STATUS==2)
{
    $SMS_CONTENT=sprintf(_("%s 已驳回您的培训计划 %s。"), $_SESSION["LOGIN_USER_NAME"], $T_PLAN_NAME);
    send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,61);
}
Message("",$MSG);
?>
<center><input type="button" class="BigButton" value="<?=_("返回")?>" onClick="location='index1.php?ASSESSING_STATUS=0';"></center>
</body>
</html>