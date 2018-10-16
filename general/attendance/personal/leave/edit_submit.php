<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("修改请假登记");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
//----------- 合法性校验 ---------
if($LEAVE_DATE1!="")
{
    $TIME_OK=is_date_time($LEAVE_DATE1);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("请假开始时间格式不对，应形如 1999-01-01 12:12:12"));
        Button_Back();
        exit;
    }
}

if($LEAVE_DATE2!="")
{
    $TIME_OK=is_date_time($LEAVE_DATE2);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("请假结束时间格式不对，应形如 1999-01-01 12:12:12"));
        Button_Back();
        exit;
    }
}

if(compare_date_time($LEAVE_DATE1,$LEAVE_DATE2)>=0)
{
    Message(_("错误"),_("请假结束时间应晚于请假开始时间"));
    Button_Back();
    exit;
}
$query="select USER_ID,ANNUAL_LEAVE from ATTEND_LEAVE  where LEAVE_ID='$LEAVE_ID'";
$result=exequery(TD::conn(),$query);
if($ROWS=mysql_fetch_array($result))
{
    $LEAVE_USER_ID=$ROWS["USER_ID"];
    $LEAVE=$ROWS["ANNUAL_LEAVE"];
}
if($LEAVE_DATE1)
{
    $leave_date_start = substr($LEAVE_DATE1,0,10);
    $leave_date_end = substr($LEAVE_DATE2,0,10);

    // 和外出时间做比较
    $sql = "select *from ATTEND_OUT where  USER_ID = '".$LEAVE_USER_ID."' AND ((concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) >= '".str_replace(' ', '',$LEAVE_DATE1)."' AND concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) <= '".str_replace(' ', '',$LEAVE_DATE2)."') OR (concat(LEFT (SUBMIT_TIME ,10),OUT_TIME2) >= '".str_replace(' ', '',$LEAVE_DATE1)."' AND concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) <= '".str_replace(' ', '',$LEAVE_DATE1)."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("错误"),_("您申请请假的时间和外出时间有冲突"));
        Button_Back();
        exit;
    }

    //和出差时间做比较
    $sql = "select *from ATTEND_EVECTION where  USER_ID = '".$LEAVE_USER_ID."' AND ((EVECTION_DATE1 >= '".$leave_date_start."' AND EVECTION_DATE1 <= '".$leave_date_end."') OR (EVECTION_DATE1 <= '".$leave_date_start."' AND EVECTION_DATE2 >= '".$leave_date_start."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("错误"),_("您申请请假的时间和出差时间有冲突"));
        Button_Back();
        exit;
    }
    
    //在同一时间段是否重复提交请假申请
    $sql = "select *from ATTEND_LEAVE where LEAVE_ID!='$LEAVE_ID' and USER_ID = '".$LEAVE_USER_ID."' AND ((LEAVE_DATE1 >= '".$LEAVE_DATE1."' AND LEAVE_DATE1 <= '".$LEAVE_DATE2."') OR (LEAVE_DATE1 <= '".$LEAVE_DATE1."' AND LEAVE_DATE2 >= '".$LEAVE_DATE1."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("错误"),_("您此时间段已经申请过请假"));
        Button_Back();
        exit;
    }
}
//----------------获取年假剩余天数-----------------
include_once("new/get_ann_func.php");
//$ANNUAL_LEAVE_LEFT=get_ann($LEAVE_USER_ID);
$DAY_DIFF= DateDiff_("d",$LEAVE_DATE1,$LEAVE_DATE2);
if($DAY_DIFF< $ANNUAL_LEAVE)
    $ANNUAL_LEAVE=$DAY_DIFF;
if($LEAVE_TYPE2 == 3 && !is_numeric($ANNUAL_LEAVE))
{
    Message(_("错误"),_("使用年休假应该是数字！"));
    Button_Back();
    exit;
}
if($LEAVE_TYPE2 == 3 && !preg_match('/^[0-9]\d*([.][0|5])?$/', $ANNUAL_LEAVE))
{
    Message(_("错误"),_("使用年休假只支持1天或0.5天！"));
    Button_Back();
    exit;
}
if($LEAVE_TYPE2 == 3 && $ANNUAL_LEAVE > $ANNUAL_LEAVE_LEFT)
{
    Message(_("错误"),_("使用年休假天数应小于或等于年休假剩余天数！"));
    Button_Back();
    exit;
}


$CUR_TIME=date("Y-m-d H:i:s",time());
if(compare_date_time($CUR_TIME,$LEAVE_DATE2)>=0 && !strstr($LEAVE_TYPE,_("补假")))
    $LEAVE_TYPE = _("补假：").$LEAVE_TYPE;

$query="update ATTEND_LEAVE set ALLOW='0',STATUS='1',DESTROY_TIME='0000-00-00 00:00:00',REASON='',LEAVE_TYPE='$LEAVE_TYPE',LEAVE_DATE1='$LEAVE_DATE1',LEAVE_DATE2='$LEAVE_DATE2',ANNUAL_LEAVE='$ANNUAL_LEAVE',LEADER_ID='$LEADER_ID',LEAVE_TYPE2='$LEAVE_TYPE2' where LEAVE_ID='$LEAVE_ID'";
exequery(TD::conn(),$query);

//---------- 事务提醒 ----------
$SMS_CONTENT=$_SESSION["LOGIN_USER_NAME"]._("提交请假申请，请批示！");
$REMIND_URL="attendance/manage/confirm";
if($SMS_REMIND=="on")
    send_sms("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,6,$SMS_CONTENT,$REMIND_URL);
if($SMS2_REMIND=="on")
    send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,$SMS_CONTENT,6);

header("location: ./?connstatus=1");
?>

</body>
</html>
