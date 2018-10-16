<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_project.php");
include_once("inc/utility_sms1.php");
include_once("inc/workflow/inc/workflow.inc.php");
$HTML_PAGE_TITLE = _("添加项目任务");
include_once("inc/header.inc.php");
mysql_set_charset(MYOA_DB_CHARSET, TD::conn());
?>
<body class="bodycolor">
<?
$year1 = substr ($TASK_START_TIME , 0 , 4 );
$month1 = substr ($TASK_START_TIME , 5 , 2 );
$day1 = substr ($TASK_START_TIME , 8 , 2 );
$year2 = substr ($TASK_END_TIME , 0 , 4 );
$month2 = substr ($TASK_END_TIME , 5 , 2 );
$day2 = substr ($TASK_END_TIME , 8 , 2 );
$time1 = mktime (0, 0, 0, intval($month1), intval($day1), intval($year1));
$time2 = mktime (0, 0, 0, intval($month2), intval($day2), intval($year2));
$TASK_TIME = floor(($time2-$time1)/86400)+1;

$query = "SELECT PROJ_NAME from proj_project where PROJ_ID='$PROJ_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $PROJ_NAME=$ROW['PROJ_NAME'];
    $PROJ_NAME = gbk_stripslashes($PROJ_NAME);
}
$CONTENT_LOG = $_SESSION['LOGIN_USER_NAME'] ." 从项目：".$PROJ_NAME." 中给我分配了任务: " . $TASK_NAME . "！";
if($_POST['add_executor']==9)
{
    $query1 = "insert into calendar(USER_ID,CONTENT,OVER_STATUS,ALLDAY,CAL_TIME,END_TIME,CAL_LEVEL)
 VALUES  ('$TASK_USER','$CONTENT_LOG','0','1','$time1','$time2','$TASK_LEVEL')";
    exequery(TD::conn(),$query1);
    $CAL_ID=mysql_insert_id();
}
if($TASK_MILESTONE=="on")
    $TASK_MILESTONE="1";
else
    $TASK_MILESTONE="0";

$c = "0";
if($CONSTRAIN == "on")
    $c = "1";

$TASK_NAME          = gbk_stripslashes($TASK_NAME);
$TASK_DESCRIPTION   = gbk_stripslashes($TASK_DESCRIPTION);
$REMARK             = gbk_stripslashes($REMARK);
$query = "insert into PROJ_TASK (TAST_CONSTRAIN,PROJ_ID, TASK_NO, TASK_NAME, TASK_DESCRIPTION, TASK_USER, TASK_MILESTONE, TASK_START_TIME, TASK_END_TIME, TASK_TIME, TASK_LEVEL, PARENT_TASK, PRE_TASK, TASK_PERCENT_COMPLETE,REMARK , FLOW_ID_STR,CAL_ID) VALUES ('$c','$PROJ_ID', '$TASK_NO', '$TASK_NAME', '$TASK_DESCRIPTION', '$TASK_USER', '$TASK_MILESTONE', '$TASK_START_TIME', '$TASK_END_TIME', '$TASK_TIME', '$TASK_LEVEL', '$PARENT_TASK', '$PRE_TASK', '$TASK_PERCENT_COMPLETE','$REMARK' ,'$FLOW_ID_STR','$CAL_ID')";
exequery(TD::conn(),$query);
$task_id = mysql_insert_id();
$info = _("我给 ").td_trim(GetUserNameById($TASK_USER))._(" 分配了任务 ").$TASK_NAME._(" [项目管理]");
insert_news($PROJ_ID,$info);
//增加事务提醒 2014-1-20
$CUR_TIME = date("Y-m-d H:i:s", time());
$SMS_CONTENT = $_SESSION['LOGIN_USER_NAME'] ." 从项目：".$PROJ_NAME." 中给我分配了任务: " . $TASK_NAME . "！";
$REMIND_URL = "1:project/task/index1.php?PROJ_ID=$PROJ_ID&TASK_ID=$task_id";
send_sms($CUR_TIME, $_SESSION["LOGIN_USER_ID"], $TASK_USER, 42, $SMS_CONTENT, $REMIND_URL,$PROJ_ID);

Message("",_("任务创建成功!"))

?>
<div align="center">
    <input type="button" class="BigButton" value="<?=_("继续创建")?>" onclick="location='new.php?PROJ_ID=<?=$PROJ_ID?>'">
    <input type="button" class="BigButton" value="<?=_("返回")?>" onclick="location='index.php?PROJ_ID=<?=$PROJ_ID?>'">
</div>
</body>
</html>