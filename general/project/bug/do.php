<?
include_once("inc/auth.inc.php");

include_once("inc/utility.php");
include_once("inc/utility_sms1.php");
ob_end_clean();
$CUR_TIME = date("Y-m-d H:i:s", time());
$RESULT = $_SESSION["LOGIN_USER_NAME"] . "(" . $CUR_TIME . ") : " . $RESULT . "|*|";

$query = "update PROJ_BUG SET STATUS=3,RESULT=CONCAT(RESULT,'$RESULT') WHERE BUG_ID='$BUG_ID'";
exequery(TD::conn(), $query);

//获取项目对应的数据
$query = "SELECT PROJ_ID,BUG_NAME,BEGIN_USER,TASK_ID from PROJ_BUG where BUG_ID='$BUG_ID'";
$cursor = exequery(TD::conn(), $query);
if ($ROW = mysql_fetch_array($cursor)) 
{
    $PROJ_ID = $ROW["PROJ_ID"];
    $TASK_ID = $ROW["TASK_ID"];
    $BUG_NAME = $ROW["BUG_NAME"];
    $BEGIN_USER = $ROW["BEGIN_USER"];
}

$SMS_CONTENT = sprintf(_("项目问题：[%s]已答复！"), $BUG_NAME);
$REMIND_URL = "1:project/task/problem/?PROJ_ID=" . $PROJ_ID . "&TASK_ID=" . $TASK_ID;
send_sms($CUR_TIME, $_SESSION["LOGIN_USER_ID"], $BEGIN_USER, 42, $SMS_CONTENT, $REMIND_URL,$PROJ_ID);

header("location:bug_list.php");
?>