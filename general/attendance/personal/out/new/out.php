<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_org.php");
include_once("inc/flow_hook.php");

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

//外出日期  比较时间是否冲突 spz 16.11.10
if($OUT_DATE != "")
{
    if((empty($TO_ID) && $batch!="on") || (empty($COPY_TO_ID) && $batch=="on"))
    {
        $TO_ID = $_SESSION["LOGIN_USER_ID"];
    }
    
    //和请假时间做比较
    $b_time=$OUT_DATE." ".$OUT_TIME1;//开始时间
    $e_time=$OUT_DATE." ".$OUT_TIME2;//结束时间
    $sql = "select *from ATTEND_LEAVE where  USER_ID = '".$TO_ID."' AND ((LEAVE_DATE1 >= '".$b_time."' AND LEAVE_DATE1 <= '".$e_time."') OR (LEAVE_DATE1 <= '".$b_time."'AND LEAVE_DATE2 >= '".$b_time."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("错误"),_("您申请外出的时间和请假时间有冲突"));
        Button_Back();
        exit;
    }

    //和出差时间做比较
    $sql = "select *from ATTEND_EVECTION where  USER_ID = '".$TO_ID."' AND (EVECTION_DATE1 <= '".$OUT_DATE."' AND EVECTION_DATE2 >= '".$OUT_DATE."')";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("错误"),_("您申请外出的时间和出差时间有冲突"));
        Button_Back();
        exit;
    }
    
    //在同一时间段是否重复提交外出申请
    $sql = "select *from ATTEND_OUT where  USER_ID = '".$TO_ID."' AND ((concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) >= '".str_replace(' ','',$b_time)."' AND concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) <= '".str_replace(' ','',$e_time)."') OR (concat(LEFT (SUBMIT_TIME ,10),OUT_TIME2) >= '".str_replace(' ','',$b_time)."' AND concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) <= '".str_replace(' ','',$b_time)."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("错误"),_("您此时间段已经申请过外出"));
        Button_Back();
        exit;
    }
    if($COPY_TO_ID!='' && $batch=="on")
    {
            
        $OUTER_USER_ID=trim($COPY_TO_ID,',');
        $OUTER_USER_ID_ARRAY= explode(',', $OUTER_USER_ID);
        $leave_count=0;
        $evection_count=0;
        $out_count=0;
        $leave_user='';
        $evection_user='';
        $out_user='';
        for($i=0;$i<count($OUTER_USER_ID_ARRAY);$i++)
        {
            $OUTER_USER_ID1=$OUTER_USER_ID_ARRAY[$i];
            //和请假时间做比较
            $sql = "select *from ATTEND_LEAVE where  USER_ID = '".$OUTER_USER_ID1."' AND ((LEAVE_DATE1 >= '".$b_time."' AND LEAVE_DATE1 <= '".$e_time."') OR (LEAVE_DATE1 <= '".$b_time."'AND LEAVE_DATE2 >= '".$b_time."'))";

            $cursor = exequery(TD::conn(),$sql);

            if($ROW=mysql_fetch_array($cursor)){
                $leave_count++;
                $leave_user .=GetUserNameByUserId($OUTER_USER_ID1);
            }

            //和出差时间做比较
            $sql = "select *from ATTEND_EVECTION where  USER_ID = '".$OUTER_USER_ID1."' AND (EVECTION_DATE1 <= '".$OUT_DATE."' AND EVECTION_DATE2 >= '".$OUT_DATE."')";

            $cursor = exequery(TD::conn(),$sql);

            if($ROW=mysql_fetch_array($cursor)){

                $evection_count++;
                $evection_user .=GetUserNameByUserId($OUTER_USER_ID1);
            }
            
            //在同一时间段是否重复提交外出申请
            $sql = "select *from ATTEND_OUT where  USER_ID = '".$OUTER_USER_ID1."' AND ((concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) >= '".str_replace(' ','',$b_time)."' AND concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) <= '".str_replace(' ','',$e_time)."') OR (concat(LEFT (SUBMIT_TIME ,10),OUT_TIME2) >= '".str_replace(' ','',$b_time)."' AND concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) <= '".str_replace(' ','',$b_time)."'))";

            $cursor = exequery(TD::conn(),$sql);

            if($ROW=mysql_fetch_array($cursor)){

                $out_count++;
                $out_user .=GetUserNameByUserId($OUTER_USER_ID1);
            }
        }
        if($leave_count>0)
        {
            $leave_user = td_trim($leave_user);
            Message(_("错误"),$leave_user._("申请外出的时间和请假时间有冲突"));
        }
        if($evection_count>0)
        {
            $evection_user = td_trim($evection_user);
            Message(_("错误"),$evection_user._("申请外出的时间和出差时间有冲突"));
            
        }
        if($out_count>0)
        {
            $out_user = td_trim($out_user);
            Message(_("错误"),$out_user._("此时间段已经申请过外出"));
            
        }
        if($leave_count>0 || $evection_count>0 || $out_count)
        {
            Button_Back();
            exit;
        }
        
    }
}

$SUBMIT_TIME=$OUT_DATE." ".$OUT_TIME1;


//验证所选部门的人员是否在管理范围内
include_once("general/attendance/personal/attend_leave.php");


if($batch=="on")
{
    if($COPY_TO_ID!="")
    {
        $OUTER_USER_ID=trim($COPY_TO_ID,',');
        $OUTER_USER_ID_ARRAY= explode(',', $OUTER_USER_ID);
        for($i=0;$i<count($OUTER_USER_ID_ARRAY);$i++)
        {
            $OUTER_USER_ID1=$OUTER_USER_ID_ARRAY[$i];
            $query="insert into ATTEND_OUT(USER_ID,LEADER_ID,OUT_TYPE,CREATE_DATE,SUBMIT_TIME,OUT_TIME1,OUT_TIME2,ALLOW,REGISTER_IP,ORDER_NO) values ('$OUTER_USER_ID1','$LEADER_ID','$OUT_TYPE','$CUR_TIME','$SUBMIT_TIME','$OUT_TIME1','$OUT_TIME2','0','".get_client_ip()."','".$_SESSION["LOGIN_USER_ID"]."')";
            exequery(TD::conn(),$query);
        }
    }
    else
    {
        $OUTER_USER_ID=$_SESSION["LOGIN_USER_ID"];
        $query="insert into ATTEND_OUT(USER_ID,LEADER_ID,OUT_TYPE,CREATE_DATE,SUBMIT_TIME,OUT_TIME1,OUT_TIME2,ALLOW,REGISTER_IP,ORDER_NO) values ('$OUTER_USER_ID','$LEADER_ID','$OUT_TYPE','$CUR_TIME','$SUBMIT_TIME','$OUT_TIME1','$OUT_TIME2','0','".get_client_ip()."','".$_SESSION["LOGIN_USER_ID"]."')";
        exequery(TD::conn(),$query);
    }
    $status=0;
}
else
{
    if($TO_ID!="")
        $OUTER_USER_ID=$TO_ID;
    else
        $OUTER_USER_ID=$_SESSION["LOGIN_USER_ID"];
    $query="insert into ATTEND_OUT(USER_ID,LEADER_ID,OUT_TYPE,CREATE_DATE,SUBMIT_TIME,OUT_TIME1,OUT_TIME2,ALLOW,REGISTER_IP,ORDER_NO) values ('$OUTER_USER_ID','$LEADER_ID','$OUT_TYPE','$CUR_TIME','$SUBMIT_TIME','$OUT_TIME1','$OUT_TIME2','0','".get_client_ip()."','".$_SESSION["LOGIN_USER_ID"]."')";
    exequery(TD::conn(),$query);
    $attend_out_id = mysql_insert_id();
    $OUTER_USER_NAME=getUserNamebyId($OUTER_USER_ID);
    $LEADER_NAME = td_trim(GetUserNameById($LEADER_ID));
    $data_array=array("KEY"=>"$attend_out_id","field"=>"OUT_ID","USER_ID"=>"$OUTER_USER_ID","USER_NAME"=>"$OUTER_USER_NAME","LEADER_ID"=>"$LEADER_ID","LEADER_NAME"=>"$LEADER_NAME","OUT_TYPE"=>"$OUT_TYPE","REASON"=>"$REASON","OUT_DATE"=>"$OUT_DATE","OUT_TIME1"=>"$OUT_TIME1","OUT_TIME2"=>"$OUT_TIME2");
    $config= array("module"=>"attend_out");
    $status=0;
    run_hook($data_array,$config);
}

if($status==0)
{
    //---------- 事务提醒 ----------
    $SMS_CONTENT=$_SESSION["LOGIN_USER_NAME"]._("提交外出申请，请批示！");
    $REMIND_URL="attendance/manage/confirm/index.php";
    if($SMS_REMIND=="on")
        send_sms("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,6,$SMS_CONTENT,$REMIND_URL);

    if($SMS2_REMIND=="on")
        send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,$SMS_CONTENT,6);

    header("location: ../index.php");
}

?>

</body>
</html>
