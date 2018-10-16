<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");


$HTML_PAGE_TITLE = _("加班登记");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());

//----------- 合法性校验 ---------

if($START_TIME!="")
{
  $TIME_OK=is_date_time($START_TIME);

  if(!$TIME_OK)
  { 
  	Message(_("错误"),_("加班开始时间有问题，请核实"));
    Button_Back();
    exit;
  }
}

if($END_TIME!="")
{
  $TIME_OK=is_date_time($END_TIME);

  if(!$TIME_OK)
  { 
  	Message(_("错误"),_("加班结束时间有问题，请核实"));
    Button_Back();
    exit;
  }
}

if(compare_date_time($START_TIME,$END_TIME)>=0)
{ 
	 Message(_("错误"),_("加班结束时间应晚于加班开始时间"));
   Button_Back();
   exit;
}
if($OVERTIME_HOURS>99)
{
    Message(_("错误"),_("加班时长只允许两位数字"));
    Button_Back();
    exit;
}
if(!is_numeric($OVERTIME_HOURS) || !is_numeric($OVERTIME_MINUTES))
{
    Message(_("错误"),_("加班时长应该是数字！"));
    Button_Back();
    exit;
}
if($OVERTIME_HOURS<=0 || $OVERTIME_MINUTES<0)
{
    Message(_("错误"),_("加班时长必须大于0！"));
    Button_Back();
    exit;
}
$query="select USER_ID from attendance_overtime  where OVERTIME_ID='$OVERTIME_ID'";
$result=exequery(TD::conn(),$query);
if($ROWS=mysql_fetch_array($result))
{
    $OVERTIME_USER_ID=$ROWS["USER_ID"];
}
//判断是否重复登记
if($START_TIME)
{
    //在同一时间段是否重复提交加班申请
    $sql = "select *from attendance_overtime where OVERTIME_ID!='$OVERTIME_ID' AND USER_ID = '".$OVERTIME_USER_ID."' AND ((START_TIME >= '".$START_TIME."' AND START_TIME <= '".$END_TIME."') OR (START_TIME <= '".$START_TIME."' AND END_TIME >= '".$START_TIME."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("错误"),_("您此时间段已经申请过加班"));
        Button_Back();
        exit;
    }
}
//加班时长
if($OVERTIME_HOURS=="" && $OVERTIME_MINUTES=="")
{   
   $ALL_HOURS3 = floor((strtotime($END_TIME)-strtotime($START_TIME)) / 3600);
   $HOUR13 = (strtotime($END_TIME)-strtotime($START_TIME)) % 3600;
   $MINITE3 = floor($HOUR13 / 60);
   $OVERTIME_HOURS2 = $ALL_HOURS3._("小时").$MINITE3._("分");
}
else
{
	 $OVERTIME_HOURS=$OVERTIME_HOURS==""?0:$OVERTIME_HOURS;
	 $OVERTIME_MINUTES=$OVERTIME_MINUTES==""?0:$OVERTIME_MINUTES;
   $OVERTIME_HOURS2 = $OVERTIME_HOURS._("小时").$OVERTIME_MINUTES._("分");
}

$query="update ATTENDANCE_OVERTIME set OVERTIME_HOURS='$OVERTIME_HOURS2',ALLOW='0',STATUS='0',START_TIME='$START_TIME',REASON='',OVERTIME_CONTENT='$OVERTIME_CONTENT',END_TIME='$END_TIME',APPROVE_ID='$APPROVE_ID',RECORD_TIME='$CUR_TIME',CONFIRM_TIME='0000-00-00 00:00:00' where OVERTIME_ID='$OVERTIME_ID'";
exequery(TD::conn(),$query);
//---------- 事务提醒 ----------
$SMS_CONTENT=$_SESSION["LOGIN_USER__NAME"]._("提交加班申请，请批示！");
$REMIND_URL = "attendance/manage/confirm";
if($SMS_REMIND=="on")
   send_sms("",$_SESSION["LOGIN_USER_ID"],$APPROVE_ID,6,$SMS_CONTENT,$REMIND_URL);

if($SMS2_REMIND=="on")
   send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$APPROVE_ID,$SMS_CONTENT,6);

header("location: ./?connstatus=1");
?>

</body>
</html>
