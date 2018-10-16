<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_calendar.php");

$HTML_PAGE_TITLE = _("编辑保存");
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
$sql3="select * from TASK WHERE TASK_ID = '$TASK_ID' and USER_ID = '".$_SESSION["LOGIN_USER_ID"]."'";
$re2=exequery(TD::conn(),$sql3);
if ($ROW2=mysql_fetch_array($re2))
	$RATE1=$ROW2['RATE'];
if ($RATE<$RATE1)
{
	Message("",_("进度百分比数值不能小于上一次的数值！") );
	Button_Back();exit;
}
$query="UPDATE TASK SET TASK_NO='$TASK_NO',TASK_TYPE='$TASK_TYPE',TASK_STATUS='$TASK_STATUS',COLOR='$COLOR',IMPORTANT='$IMPORTANT',SUBJECT='$SUBJECT',EDIT_TIME='$CUR_TIME',BEGIN_DATE='$BEGIN_DATE',END_DATE='$END_DATE',CONTENT='$CONTENT',RATE='$RATE',FINISH_TIME='$FINISH_TIME',TOTAL_TIME='$TOTAL_TIME',USE_TIME='$USE_TIME',CAL_ID='$CAL_ID' WHERE TASK_ID = '$TASK_ID' and USER_ID = '".$_SESSION["LOGIN_USER_ID"]."'";
exequery(TD::conn(),$query);
//任务完成同步删除任务中心数据
if($TASK_STATUS==3) 
{
	delete_taskcenter('TASK',$TASK_ID);
} 
$sql="select WORK_PLAN_ID from TASK where TASK_ID = '$TASK_ID' and USER_ID = '".$_SESSION["LOGIN_USER_ID"]."'";
$re=exequery(TD::conn(),$sql);
if ($ROW=mysql_fetch_array($re))
{
	$WORK_PLAN_ID=$ROW['WORK_PLAN_ID'];
}
if ($WORK_PLAN_ID!='')
{
	$sql4="select * from WORK_DETAIL where PLAN_ID='$WORK_PLAN_ID'";
	$re3=exequery(TD::conn(),$sql4);
	if ($ROW3=mysql_fetch_array($re3))
	{
		$sql2="select MAX(PERCENT) as PERCENT from WORK_DETAIL where PLAN_ID='$WORK_PLAN_ID' and WRITER='".$_SESSION["LOGIN_USER_ID"]."'";
		$re1=exequery(TD::conn(),$sql2);
		if($ROW1=mysql_fetch_array($re1))
			$PERCENT=$ROW1['PERCENT'];
		$sql1="update WORK_DETAIL set PROGRESS='$CONTENT',PERCENT='$RATE',WRITE_TIME='$CUR_TIME' WHERE PLAN_ID='$WORK_PLAN_ID' AND WRITER='".$_SESSION["LOGIN_USER_ID"]."' and PERCENT='$PERCENT'";
		exequery(TD::conn(),$sql1);
	}
	else
	{
		$sql5="insert into WORK_DETAIL (PLAN_ID,WRITE_TIME,PROGRESS,PERCENT,TYPE_FLAG,WRITER,ATTACHMENT_ID,ATTACHMENT_NAME) 
		values ('$WORK_PLAN_ID','$CUR_TIME','','$RATE','0','".$_SESSION["LOGIN_USER_ID"]."','','')";
		exequery(TD::conn(),$sql5);
	}
}
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
