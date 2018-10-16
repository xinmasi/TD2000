<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");


$HTML_PAGE_TITLE = _("外出登记");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());

//----------- 合法性校验 ---------

if($OUT_TIME1!="")
{
	$OUT_TIME11="1999-01-02 ".$OUT_TIME1.":02";
  $TIME_OK=is_date_time($OUT_TIME11);

  if(!$TIME_OK)
  { 
  	Message(_("错误"),_("时间有问题，请核实"));
    Button_Back();
    exit;
  }
}

if($OUT_TIME2!="")
{
	$OUT_TIME22="1999-01-02 ".$OUT_TIME2.":02";
  $TIME_OK=is_date_time($OUT_TIME22);

  if(!$TIME_OK)
  { 
  	Message(_("错误"),_("时间有问题，请核实"));
    Button_Back();
    exit;
  }
}

if(compare_date_time($OUT_TIME11,$OUT_TIME22)>=0)
{ 
	 Message(_("错误"),_("外出结束时间应晚于外出开始时间"));
   Button_Back();
   exit;
}
$query="select USER_ID from ATTEND_OUT  where OUT_ID='$OUT_ID'";
$result=exequery(TD::conn(),$query);
if($ROWS=mysql_fetch_array($result))
{
    $OUT_USER_ID=$ROWS["USER_ID"];
}
if($OUT_DATE != "")
{
    
    //和请假时间做比较
    $b_time=$OUT_DATE." ".$OUT_TIME1;//开始时间
    $e_time=$OUT_DATE." ".$OUT_TIME2;//结束时间
    $sql = "select *from ATTEND_LEAVE where  USER_ID = '".$OUT_USER_ID."' AND ((LEAVE_DATE1 >= '".$b_time."' AND LEAVE_DATE1 <= '".$e_time."') OR (LEAVE_DATE1 <= '".$b_time."'AND LEAVE_DATE2 >= '".$b_time."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("错误"),_("您申请外出的时间和请假时间有冲突"));
        Button_Back();
        exit;
    }

    //和出差时间做比较
    $sql = "select *from ATTEND_EVECTION where  USER_ID = '".$OUT_USER_ID."' AND (EVECTION_DATE1 <= '".$OUT_DATE."' AND EVECTION_DATE2 >= '".$OUT_DATE."')";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("错误"),_("您申请外出的时间和出差时间有冲突"));
        Button_Back();
        exit;
    }
    
    //在同一时间段是否重复提交外出申请
    $sql = "select * from ATTEND_OUT where OUT_ID!='$OUT_ID' and USER_ID = '".$OUT_USER_ID."' AND ((concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) >= '".str_replace(' ','',$b_time)."' AND concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) <= '".str_replace(' ','',$e_time)."') OR (concat(LEFT (SUBMIT_TIME ,10),OUT_TIME2) >= '".str_replace(' ','',$b_time)."' AND concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) <= '".str_replace(' ','',$b_time)."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("错误"),_("您此时间段已经申请过外出"));
        Button_Back();
        exit;
    }
}
$SUBMIT_TIME=$OUT_DATE." ".$OUT_TIME1;
$query="update ATTEND_OUT set ALLOW='0',STATUS='0',SUBMIT_TIME='$SUBMIT_TIME',REASON='',OUT_TYPE='$OUT_TYPE',OUT_TIME1='$OUT_TIME1',OUT_TIME2='$OUT_TIME2',LEADER_ID='$LEADER_ID' where OUT_ID='$OUT_ID'";
exequery(TD::conn(),$query);
//---------- 事务提醒 ----------
$SMS_CONTENT=$_SESSION["LOGIN_USER__NAME"]._("提交外出申请，请批示！");
$REMIND_URL="attendance/manage/confirm";
if($SMS_REMIND=="on")
   send_sms("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,6,$SMS_CONTENT,$REMIND_URL);

if($SMS2_REMIND=="on")
   send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,$SMS_CONTENT,6);

header("location: ./");
?>

</body>
</html>
