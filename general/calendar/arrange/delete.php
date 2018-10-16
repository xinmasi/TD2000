<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_calendar.php");
$CAL_ID = td_trim($CAL_ID);
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";
if($CAL_ID != "")
{
   $query="select * from CALENDAR where CAL_ID in ($CAL_ID)";
   $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
   if($ROW=mysql_fetch_array($cursor))
   {
		$CAL_TIME=$ROW["CAL_TIME"];
		$CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
		$CONTENT=$ROW["CONTENT"];
		$SMS_CONTENT=_("请查看日程安排！")."\n"._("内容：").csubstr($CONTENT,0,100);
		$SMS_CONTENT2=_("OA日程安排:").$CONTENT;   
		$query="delete from SMS2 where SEND_TIME<='$CAL_TIME' and CONTENT='$SMS_CONTENT2' and SEND_FLAG='0'";
		exequery(TD::conn(),$query);
   }
	$query="delete from CALENDAR where  CAL_ID in ($CAL_ID)";
	exequery(TD::conn(),$query);
   //同步删除任务中心中日程信息
   delete_taskcenter('CALENDAR',$CAL_ID,1);
   delete_remind_sms(5, $_SESSION["LOGIN_USER_ID"], $SMS_CONTENT, $CAL_TIME);
}
if($_GET["AJAX"]=="1")
   exit;
if($_GET["action"]=="search")
	header("location: search.php?IS_MAIN=1");
else
{
	$REFER_URL=$_COOKIE["cal_view"]=="" ? "index.php" : $_COOKIE["cal_view"].".php";
	header("location: $REFER_URL?OVER_STATUS=$OVER_STATUS&YEAR=$YEAR&MONTH=$MONTH&DAY=$DAY&IS_MAIN=1");
}
?>