<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_sms1.php");
include_once("inc/header.inc.php");
//------------zfc-----------

$query = "select TASK_NAME,TASK_USER,PROJ_ID from proj_task where TASK_ID = '".$_GET['TASK_ID']."'";
$cursor = exequery(TD::conn(), $query);
if($ROW=mysql_fetch_array($cursor))
{
    $PRE_TASK_NAME = $ROW["TASK_NAME"];
    $PRE_TASK_USER = $ROW["TASK_USER"];
    $PRE_PROJ_ID = $ROW["PROJ_ID"];

    $CUR_TIME = date("Y-m-d H:i:s", time());
    $SMS_CONTENT ="[事务催办] " . rtrim(GetUserNameById($PRE_TASK_USER),",") . "请您尽快办理项目任务 " . $PRE_TASK_NAME ." 如果已办理完成请结束任务";
    $REMIND_URL = "1:project/task/detail.php?PROJ_ID=".$PRE_PROJ_ID."&TASK_ID=" . $_GET['TASK_ID'];
    send_sms($CUR_TIME, $_SESSION["LOGIN_USER_ID"], $PRE_TASK_USER , 42, $SMS_CONTENT, $REMIND_URL,$PRE_PROJ_ID);

    Message(_(""),_("催办提醒发送成功!"));

}else
{
    Message(_("错误"),_("催办提醒发送失败请重试!"));
}

Button_Back();
?>