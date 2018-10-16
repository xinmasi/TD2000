<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");

while(list($KEY, $VALUE) = each($_POST))
{
    $$KEY = trim($VALUE);
}

$HTML_PAGE_TITLE = _("新建保存");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
//------------------- 保存 -----------------------
$CUR_TIME=date("Y-m-d H:i:s",time());


if($BEGIN_DATE!="" && !is_date($BEGIN_DATE))
{
   Message("",_("起始日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}

if($END_DATE!="" && !is_date($END_DATE))
{
   Message("",_("结束日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}

if($FINISH_TIME!="" && !is_date_time($FINISH_TIME))
{
   Message("",_("完成时间应为时间型，如：1999-01-01 10:08:10"));
   Button_Back();
   exit;
}

if($REMIND_TIME!="" && !is_date_time($REMIND_TIME))
{
   Message("",_("提醒时间应为时间型，如：1999-01-01 10:08:10"));
   Button_Back();
   exit;
}

$CAL_ID=1;

$query="INSERT INTO `TASK`(`USER_ID` , `TASK_NO` , `TASK_TYPE` , `TASK_STATUS` , `COLOR` , `IMPORTANT` , `SUBJECT` , `EDIT_TIME` , `BEGIN_DATE` , `END_DATE` , `CONTENT` , `RATE` , `FINISH_TIME` , `TOTAL_TIME` , `USE_TIME` , `CAL_ID` )
VALUES ('".$_SESSION["LOGIN_USER_ID"]."', '$TASK_NO', '$TASK_TYPE', '$TASK_STATUS', '$COLOR', '$IMPORTANT', '$SUBJECT', '$CUR_TIME', '$BEGIN_DATE', '$END_DATE', '$CONTENT', '$RATE', '$FINISH_TIME', '$TOTAL_TIME', '$USE_TIME', '$CAL_ID');";
exequery(TD::conn(),$query);
$TASK_ID=mysql_insert_id();

//------- 事务提醒 --------
if($REMIND_TIME!="" && $SMS_REMIND=="on")
{
  $REMIND_URL="1:calendar/task/note.php?TASK_ID=".$TASK_ID;
  $SMS_CONTENT=_("请查看我的任务！")."\n"._("标题：").csubstr($SUBJECT,0,50);
  send_sms($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$_SESSION["LOGIN_USER_ID"],5,$SMS_CONTENT,$REMIND_URL,$TASK_ID);
}

if($REMIND_TIME!="" && $SMS2_REMIND=="on")
{
   $SMS_CONTENT=_("OA任务:").$SUBJECT;
   send_mobile_sms_user($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$_SESSION["LOGIN_USER_ID"],$SMS_CONTENT,5);
}

header("location: index.php?PAGE_START=$PAGE_START&IS_MAIN=1")
?>

</body>
</html>
