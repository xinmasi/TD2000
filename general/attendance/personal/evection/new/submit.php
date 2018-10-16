<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_org.php");
include_once("inc/flow_hook.php");

$HTML_PAGE_TITLE = _("新建出差登记");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
//----------- 合法性校验 ---------
if($EVECTION_DATE1!="")
{
    $TIME_OK=is_date($EVECTION_DATE1);

    if(!$TIME_OK)
    { Message(_("错误"),_("出差开始日期格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

if($EVECTION_DATE2!="")
{
    $TIME_OK=is_date($EVECTION_DATE2);

    if(!$TIME_OK)
    { Message(_("错误"),_("出差结束日期格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

if(compare_date($EVECTION_DATE1,$EVECTION_DATE2)==1)
{ Message(_("错误"),_("出差开始日期不能晚于出差结束日期"));
    Button_Back();
    exit;
}
//出差日期  比较时间是否冲突 spz 16.11.10
if($EVECTION_DATE1)
{
    if((empty($TO_ID) && $batch!="on") || (empty($COPY_TO_ID) && $batch=="on"))
    {
        $TO_ID = $_SESSION["LOGIN_USER_ID"];
    }
    // 和外出时间做比较
    $sql = "select *from ATTEND_OUT where  USER_ID = '".$TO_ID."' AND  cast(SUBMIT_TIME as date) <= '".$EVECTION_DATE2."' AND cast(SUBMIT_TIME as date) >= '".$EVECTION_DATE1."'";
    // echo $sql;
    // exit;
    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("错误"),_("您申请出差的时间和外出时间有冲突"));
        Button_Back();
        exit;
    }

    //和请假时间做比较
    $sql = "select *from ATTEND_LEAVE where  USER_ID = '".$TO_ID."' AND (cast(LEAVE_DATE1 as date) >= '".$EVECTION_DATE1."' AND cast(LEAVE_DATE1 as date) <= '".$EVECTION_DATE2."' OR (cast(LEAVE_DATE1 as date) <= '".$EVECTION_DATE1."' AND cast(LEAVE_DATE2 as date) >= '".$EVECTION_DATE1."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("错误"),_("您申请出差的时间和请假时间有冲突"));
        Button_Back();
        exit;
    }
    
    //在同一时间段是否重复提交出差申请
    $sql = "select *from ATTEND_EVECTION where  USER_ID = '".$TO_ID."' AND ((EVECTION_DATE1 >= '".$EVECTION_DATE1."' AND EVECTION_DATE1 <= '".$EVECTION_DATE2."') OR (EVECTION_DATE1 <= '".$EVECTION_DATE1."' AND EVECTION_DATE2 >= '".$EVECTION_DATE1."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("错误"),_("您此时间段已经申请过出差"));
        Button_Back();
        exit;
    }
    if($COPY_TO_ID!='' && $batch=="on")
    {
        $EVECTION_USER_ID=trim($COPY_TO_ID,',');
        $EVECTION_USER_ID_ARRAY= explode(',', $EVECTION_USER_ID);
        $leave_count=0;
        $out_count=0;
        $evection_count=0;
        $leave_user='';
        $out_user='';
        $evection_user='';
        for($i=0;$i<count($EVECTION_USER_ID_ARRAY);$i++)
        {
            $EVECTION_USER_ID1=$EVECTION_USER_ID_ARRAY[$i];
            // 和外出时间做比较
            $sql = "select *from ATTEND_OUT where  USER_ID = '".$EVECTION_USER_ID1."' AND  cast(SUBMIT_TIME as date) <= '".$EVECTION_DATE2."' AND cast(SUBMIT_TIME as date) >= '".$EVECTION_DATE1."'";
            // echo $sql;
            // exit;
            $cursor = exequery(TD::conn(),$sql);

            if($ROW=mysql_fetch_array($cursor)){

                $out_count++;
                $out_user .=GetUserNameByUserId($EVECTION_USER_ID1);
            }

            //和请假时间做比较
            $sql = "select *from ATTEND_LEAVE where  USER_ID = '".$EVECTION_USER_ID1."' AND (cast(LEAVE_DATE1 as date) >= '".$EVECTION_DATE1."' AND cast(LEAVE_DATE1 as date) <= '".$EVECTION_DATE2."' OR (cast(LEAVE_DATE1 as date) <= '".$EVECTION_DATE1."' AND cast(LEAVE_DATE2 as date) >= '".$EVECTION_DATE1."'))";

            $cursor = exequery(TD::conn(),$sql);

            if($ROW=mysql_fetch_array($cursor)){

                $leave_count++;
                $leave_user .=GetUserNameByUserId($EVECTION_USER_ID1);
            }
            
            //在同一时间段是否重复提交出差申请
            $sql = "select *from ATTEND_EVECTION where  USER_ID = '".$EVECTION_USER_ID1."' AND ((EVECTION_DATE1 >= '".$EVECTION_DATE1."' AND EVECTION_DATE1 <= '".$EVECTION_DATE2."') OR (EVECTION_DATE1 <= '".$EVECTION_DATE1."' AND EVECTION_DATE2 >= '".$EVECTION_DATE1."'))";

            $cursor = exequery(TD::conn(),$sql);

            if($ROW=mysql_fetch_array($cursor)){

                $evection_count++;
                $evection_user .=GetUserNameByUserId($EVECTION_USER_ID1);
            }
        }
        if($leave_count>0)
        {
            $leave_user = td_trim($leave_user);
            Message(_("错误"),$leave_user._("申请出差的时间和请假时间有冲突"));
        }
        if($out_count>0)
        {
            $out_user = td_trim($out_user);
            Message(_("错误"),$out_user._("申请出差的时间和出差时间有冲突"));
        }
        if($evection_count>0)
        {
            $evection_user = td_trim($evection_user);
            Message(_("错误"),$evection_user._("此时间段已经申请过出差"));
        }
        if($leave_count>0 || $out_count>0 || $evection_count>0)
        {
            Button_Back();
            exit;
        }
    }
}


$CUR_TIME=date("Y-m-d H:i:s",time());


//验证所选部门的人员是否在管理范围内
include_once("general/attendance/personal/attend_leave.php");

if($batch=="on")
{
    if($COPY_TO_ID!="")
    {
        $EVECTION_USER_ID=trim($COPY_TO_ID,',');
        $EVECTION_USER_ID_ARRAY= explode(',', $EVECTION_USER_ID);
        for($i=0;$i<count($EVECTION_USER_ID_ARRAY);$i++)
        {
            $EVECTION_USER_ID1=$EVECTION_USER_ID_ARRAY[$i];
            $query="insert into ATTEND_EVECTION(USER_ID,EVECTION_DEST,EVECTION_DATE1,EVECTION_DATE2,STATUS,LEADER_ID,ALLOW,REASON,REGISTER_IP,AGENT,RECORD_TIME) values ('$EVECTION_USER_ID1','$EVECTION_DEST','$EVECTION_DATE1','$EVECTION_DATE2','1','$LEADER_ID','0','$REASON','".get_client_ip()."','".$_SESSION["LOGIN_USER_ID"]."','$CUR_TIME')";
            exequery(TD::conn(),$query);
        }
    }
    else
    {
        $EVECTION_USER_ID=$_SESSION["LOGIN_USER_ID"];
        $query="insert into ATTEND_EVECTION(USER_ID,EVECTION_DEST,EVECTION_DATE1,EVECTION_DATE2,STATUS,LEADER_ID,ALLOW,REASON,REGISTER_IP,AGENT,RECORD_TIME) values ('$EVECTION_USER_ID','$EVECTION_DEST','$EVECTION_DATE1','$EVECTION_DATE2','1','$LEADER_ID','0','$REASON','".get_client_ip()."','".$_SESSION["LOGIN_USER_ID"]."','$CUR_TIME')";
        exequery(TD::conn(),$query);
    }
    $status=0;
}
else
{
    if($TO_ID!="")
        $EVECTION_USER_ID=$TO_ID;
    else
        $EVECTION_USER_ID=$_SESSION["LOGIN_USER_ID"];
    $query="insert into ATTEND_EVECTION(USER_ID,EVECTION_DEST,EVECTION_DATE1,EVECTION_DATE2,STATUS,LEADER_ID,ALLOW,REASON,REGISTER_IP,AGENT,RECORD_TIME) values ('$EVECTION_USER_ID','$EVECTION_DEST','$EVECTION_DATE1','$EVECTION_DATE2','1','$LEADER_ID','0','$REASON','".get_client_ip()."','".$_SESSION["LOGIN_USER_ID"]."','$CUR_TIME')";
    exequery(TD::conn(),$query);
    $ROW_ID=mysql_insert_id();
    $EVECTION_USER_NAME=td_trim(GetUserNameById($EVECTION_USER_ID));
    $LEADER_NAME = td_trim(GetUserNameById($LEADER_ID));
    $data_array=array("KEY"=>"$ROW_ID","field"=>"EVECTION_ID","USER_ID"=>"$EVECTION_USER_ID","USER_NAME"=>"$EVECTION_USER_NAME","EVECTION_DEST"=>"$EVECTION_DEST","REASON"=>"$REASON","EVECTION_DATE1"=>"$EVECTION_DATE1","EVECTION_DATE2"=>"$EVECTION_DATE2","LEADER_ID"=>"$LEADER_ID","LEADER_NAME"=>"$LEADER_NAME");
    $config= array("module"=>"attend_evection");
    $status=0;
    run_hook($data_array,$config);
}

if($status==0)
{
    //---------- 事务提醒 ----------
    $SMS_CONTENT=$_SESSION["LOGIN_USER_NAME"]._("提交出差申请，请批示！");
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