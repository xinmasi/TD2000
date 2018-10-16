<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_sms1.php");

$query="select FLOW_TITLE from SCORE_FLOW where FLOW_ID='$FLOW_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$FLOW_TITLE=$ROW["FLOW_TITLE"];
}

$SMS_CONTENT=_("有对您的考核任务“").$FLOW_TITLE._("”,请自评。");
$REMIND_URL="score/self_assessment/self_assessment.php?FLOW_ID=$FLOW_ID&GROUP_ID=$GROUP_ID";

send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID,15,$SMS_CONTENT,$REMIND_URL,$FLOW_ID);

header("location:score_index.php?FLOW_ID=$FLOW_ID");
?>