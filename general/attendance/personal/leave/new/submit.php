<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_org.php");
include_once("inc/flow_hook.php");

$HTML_PAGE_TITLE = _("新建请假登记");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
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
//请假日期  比较时间是否冲突 spz 16.11.10
if($LEAVE_DATE1)
{
    $leave_date_start = substr($LEAVE_DATE1,0,10);
    $leave_date_end = substr($LEAVE_DATE2,0,10);
    if((empty($TO_ID) && $batch!="on") || (empty($COPY_TO_ID) && $batch=="on"))
    {
        $TO_ID = $_SESSION["LOGIN_USER_ID"];
    }

    // 和外出时间做比较
    $sql = "select *from ATTEND_OUT where  USER_ID = '".$TO_ID."' AND ((concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) >= '".str_replace(' ', '',$LEAVE_DATE1)."' AND concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) <= '".str_replace(' ', '',$LEAVE_DATE2)."') OR (concat(LEFT (SUBMIT_TIME ,10),OUT_TIME2) >= '".str_replace(' ', '',$LEAVE_DATE1)."' AND concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) <= '".str_replace(' ', '',$LEAVE_DATE1)."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("错误"),_("您申请请假的时间和外出时间有冲突"));
        Button_Back();
        exit;
    }

    //和出差时间做比较
    $sql = "select *from ATTEND_EVECTION where  USER_ID = '".$TO_ID."' AND ((EVECTION_DATE1 >= '".$leave_date_start."' AND EVECTION_DATE1 <= '".$leave_date_end."') OR (EVECTION_DATE1 <= '".$leave_date_start."' AND EVECTION_DATE2 >= '".$leave_date_start."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("错误"),_("您申请请假的时间和出差时间有冲突"));
        Button_Back();
        exit;
    }
    
    //在同一时间段是否重复提交请假申请
    $sql = "select *from ATTEND_LEAVE where  USER_ID = '".$TO_ID."' AND ((LEAVE_DATE1 >= '".$LEAVE_DATE1."' AND LEAVE_DATE1 <= '".$LEAVE_DATE2."') OR (LEAVE_DATE1 <= '".$LEAVE_DATE1."' AND LEAVE_DATE2 >= '".$LEAVE_DATE1."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("错误"),_("您此时间段已经申请过请假"));
        Button_Back();
        exit;
    }
    
    if($COPY_TO_ID!='' && $batch=="on")
    {
        $LEAVE_USER_ID=trim($COPY_TO_ID,',');
        $LEAVE_USER_ID_ARRAY= explode(',', $LEAVE_USER_ID);
        $out_count=0;
        $evection_count=0;
        $leave_count=0;
        $out_user='';
        $evection_user='';
        $leave_user='';
        for($i=0;$i<count($LEAVE_USER_ID_ARRAY);$i++)
        {
            $LEAVE_USER_ID1=$LEAVE_USER_ID_ARRAY[$i];
            // 和外出时间做比较
            $sql = "select *from ATTEND_OUT where  USER_ID = '".$LEAVE_USER_ID1."' AND ((concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) >= '".str_replace(' ', '',$LEAVE_DATE1)."' AND concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) <= '".str_replace(' ', '',$LEAVE_DATE2)."') OR (concat(LEFT (SUBMIT_TIME ,10),OUT_TIME2) >= '".str_replace(' ', '',$LEAVE_DATE1)."' AND concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) <= '".str_replace(' ', '',$LEAVE_DATE1)."'))";

            $cursor = exequery(TD::conn(),$sql);

            if($ROW=mysql_fetch_array($cursor)){
                $out_count++;
                $out_user .=GetUserNameByUserId($LEAVE_USER_ID1);
            }

            //和出差时间做比较
            $sql = "select *from ATTEND_EVECTION where  USER_ID = '".$LEAVE_USER_ID1."' AND ((EVECTION_DATE1 >= '".$leave_date_start."' AND EVECTION_DATE1 <= '".$leave_date_end."') OR (EVECTION_DATE1 <= '".$leave_date_start."' AND EVECTION_DATE2 >= '".$leave_date_start."'))";

            $cursor = exequery(TD::conn(),$sql);

            if($ROW=mysql_fetch_array($cursor)){

                $evection_count++;
                $evection_user .=GetUserNameByUserId($LEAVE_USER_ID1);
            }
            //在同一时间段是否重复提交请假申请
            $sql = "select *from ATTEND_LEAVE where  USER_ID = '".$LEAVE_USER_ID1."' AND ((LEAVE_DATE1 >= '".$LEAVE_DATE1."' AND LEAVE_DATE1 <= '".$LEAVE_DATE2."') OR (LEAVE_DATE1 <= '".$LEAVE_DATE1."' AND LEAVE_DATE2 >= '".$LEAVE_DATE1."'))";

            $cursor = exequery(TD::conn(),$sql);

            if($ROW=mysql_fetch_array($cursor)){

                $leave_count++;
                $leave_user .=GetUserNameByUserId($LEAVE_USER_ID1);
            }
        }
        if($out_count>0)
        {
            $out_user = td_trim($out_user);
            Message(_("错误"),$out_user._("申请请假的时间和外出时间有冲突"));
        }
        if($evection_count>0)
        {
            $evection_user = td_trim($evection_user);
            Message(_("错误"),$evection_user._("申请请假的时间和出差时间有冲突"));
        }
        if($leave_count>0)
        {
            $leave_user = td_trim($leave_user);
            Message(_("错误"),$leave_user._("此时间段已经申请过请假"));
        }
        if($out_count>0 || $evection_count>0 || $leave_count>0)
        {
            Button_Back();
            exit;
        }
    }
}

//----------------获取年假剩余天数-----------------
include_once("get_ann_func.php");
if($TO_ID=="")
{
    $TO_ID=$_SESSION["LOGIN_USER_ID"];
}
//$ANNUAL_LEAVE_LEFT=get_ann($TO_ID);
$DAY_DIFF= DateDiff_("d",$LEAVE_DATE1,$LEAVE_DATE2);
if($DAY_DIFF< $ANNUAL_LEAVE)
    $ANNUAL_LEAVE=$DAY_DIFF;
if($LEAVE_TYPE2 == 3 && !is_numeric($ANNUAL_LEAVE))
{
    Message(_("错误"),_("使用年休假应该是数字！"));
    Button_Back();
    exit;
}
if($LEAVE_TYPE2 == "3" && $ANNUAL_LEAVE<=0)
{
    Message(_("错误"),_("使用年休假天数必须大于0！"));
    Button_Back();
    exit;
}
if($LEAVE_TYPE2 == 3 && !preg_match('/^[0-9]\d*([.][0|5])?$/', $ANNUAL_LEAVE))
{
    Message(_("错误"),_("使用年休假只支持1天或0.5天！"));
    Button_Back();
    exit;
}
//echo $ANNUAL_LEAVE."</br>".$ANNUAL_LEAVE_LEFT;
//exit();
if($LEAVE_TYPE2 == 3 && $ANNUAL_LEAVE > $ANNUAL_LEAVE_LEFT)
{
    Message(_("错误"),_("使用年休假天数应小于或等于年休假剩余天数！"));
    Button_Back();
    exit;
}

$CUR_TIME=date("Y-m-d H:i:s",time());
if(compare_date_time($CUR_TIME,$LEAVE_DATE2)>=0)
    $LEAVE_TYPE = _("补假：").$LEAVE_TYPE;


//验证所选部门的人员是否在管理范围内
include_once("general/attendance/personal/attend_leave.php");

if($batch=="on")
{
    if($COPY_TO_ID!="")
    {
        $LEAVE_USER_ID=trim($COPY_TO_ID,',');
        $LEAVE_USER_ID_ARRAY= explode(',', $LEAVE_USER_ID);
        for($i=0;$i<count($LEAVE_USER_ID_ARRAY);$i++)
        {
            $LEAVE_USER_ID1=$LEAVE_USER_ID_ARRAY[$i];
            $query="insert into ATTEND_LEAVE(USER_ID,LEADER_ID,LEAVE_TYPE,LEAVE_DATE1,LEAVE_DATE2,ANNUAL_LEAVE,STATUS,ALLOW,REGISTER_IP,RECORD_TIME,LEAVE_TYPE2,AGENT) values ('$LEAVE_USER_ID1','$LEADER_ID','$LEAVE_TYPE','$LEAVE_DATE1','$LEAVE_DATE2','$ANNUAL_LEAVE','1','0','".get_client_ip()."','$CUR_TIME','$LEAVE_TYPE2','".$_SESSION["LOGIN_USER_ID"]."')";
            exequery(TD::conn(),$query);
        }
    }
    else
    {
        $LEAVE_USER_ID=$_SESSION["LOGIN_USER_ID"];
        $query="insert into ATTEND_LEAVE(USER_ID,LEADER_ID,LEAVE_TYPE,LEAVE_DATE1,LEAVE_DATE2,ANNUAL_LEAVE,STATUS,ALLOW,REGISTER_IP,RECORD_TIME,LEAVE_TYPE2,AGENT) values ('$LEAVE_USER_ID','$LEADER_ID','$LEAVE_TYPE','$LEAVE_DATE1','$LEAVE_DATE2','$ANNUAL_LEAVE','1','0','".get_client_ip()."','$CUR_TIME','$LEAVE_TYPE2','".$_SESSION["LOGIN_USER_ID"]."')";
        exequery(TD::conn(),$query);
    }
    $status=0;
}
else
{
    if($TO_ID!="")
        $LEAVE_USER_ID=$TO_ID;
    else
        $LEAVE_USER_ID=$_SESSION["LOGIN_USER_ID"];
    $query="insert into ATTEND_LEAVE(USER_ID,LEADER_ID,LEAVE_TYPE,LEAVE_DATE1,LEAVE_DATE2,ANNUAL_LEAVE,STATUS,ALLOW,REGISTER_IP,RECORD_TIME,LEAVE_TYPE2,AGENT) values ('$LEAVE_USER_ID','$LEADER_ID','$LEAVE_TYPE','$LEAVE_DATE1','$LEAVE_DATE2','$ANNUAL_LEAVE','1','0','".get_client_ip()."','$CUR_TIME','$LEAVE_TYPE2','".$_SESSION["LOGIN_USER_ID"]."')";
    exequery(TD::conn(),$query);
    $ROW_ID=mysql_insert_id();
    $LEAVE_USER_NAME=td_trim(GetUserNameById($LEAVE_USER_ID));
    $LEADER_NAME = td_trim(GetUserNameById($LEADER_ID));
    if($LEAVE_TYPE2 == 1)
    {
        $LEAVE_TYPE2 = "事假";
    }else if($LEAVE_TYPE2 == 2)
    {
        $LEAVE_TYPE2 = "病假";
    }else if($LEAVE_TYPE2 == 3)
    {
        $LEAVE_TYPE2 = "年假";
    }else if($LEAVE_TYPE2 == 9)
    {
        $LEAVE_TYPE2 = "其他";
    }
    $data_array=array("KEY"=>"$ROW_ID","field"=>"LEAVE_ID","USER_ID"=>"$LEAVE_USER_ID","USER_NAME"=>"$LEAVE_USER_NAME","LEAVE_TYPE"=>"$LEAVE_TYPE","ABSENCE_TYPE"=>"$LEAVE_TYPE2","LEAVE_DATE1"=>"$LEAVE_DATE1","LEAVE_DATE2"=>"$LEAVE_DATE2","ANNUAL_LEAVE"=>"$ANNUAL_LEAVE","LEADER_ID"=>"$LEADER_ID","LEADER_NAME"=>"$LEADER_NAME");
    $config= array("module"=>"attend_leave");
    $status=0;
    run_hook($data_array,$config);
}
if($status==0)
{
    //---------- 事务提醒 ----------
    $SMS_CONTENT=$_SESSION["LOGIN_USER_NAME"]._("提交请假申请，请批示！");
    $REMIND_URL="attendance/manage/confirm";
    if($SMS_REMIND=="on")
        send_sms("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,6,$SMS_CONTENT,$REMIND_URL);
    if($SMS2_REMIND=="on")
        send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,$SMS_CONTENT,6);
    header("location: ../");
}
?>

</body>
</html>
