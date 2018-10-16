<?
$SESSION_WRITE_CLOSE = 0;
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/flow_hook.php");
include_once("checkRange.php");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">
<?
//----------- 合法性校验 ---------
$CUR_TIME=date("Y-m-d H:i:s",time());
if($OUT_TIME1!="")
{
    $OUT_TIME11="1999-01-02 ".$OUT_TIME1.":02";
    $TIME_OK=is_date_time($OUT_TIME11);
    if(!$TIME_OK)
    {
        Message(_("错误"),_("时间有问题，请核实"));
        ?>
        <br><center><input type="button" class="BigButton" value="<?=_("返回")?>" onclick="location='new.php?FLAG=1&TO_ID=<?=$TO_ID2?>&OUT_TYPE=<?=$OUT_TYPE?>&OUT_DATE=<?=$OUT_DATE?>&OUT_TIME1=<?=$OUT_TIME1?>&OUT_TIME2=<?=$OUT_TIME2?>&SMS_REMIND_OUT=<?=$SMS_REMIND_OUT?>&SMS2_REMIND_OUT=<?=$SMS2_REMIND_OUT?>';"></center>
        <?
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
        ?>
        <br><center><input type="button" class="BigButton" value="<?=_("返回")?>" onclick="location='new.php?FLAG=1&TO_ID=<?=$TO_ID2?>&OUT_TYPE=<?=$OUT_TYPE?>&OUT_DATE=<?=$OUT_DATE?>&OUT_TIME1=<?=$OUT_TIME1?>&OUT_TIME2=<?=$OUT_TIME2?>&SMS_REMIND_OUT=<?=$SMS_REMIND_OUT?>&SMS2_REMIND_OUT=<?=$SMS2_REMIND_OUT?>';"></center>
        <?
        exit;
    }
}
if(compare_date_time($OUT_TIME11,$OUT_TIME22)>=0)
{
    Message(_("错误"),_("外出结束时间应晚于外出开始时间"));
    ?>
    <br><center><input type="button" class="BigButton" value="<?=_("返回")?>" onclick="location='new.php?FLAG=1&TO_ID=<?=$TO_ID2?>&OUT_TYPE=<?=$OUT_TYPE?>&OUT_DATE=<?=$OUT_DATE?>&OUT_TIME1=<?=$OUT_TIME1?>&OUT_TIME2=<?=$OUT_TIME2?>&SMS_REMIND_OUT=<?=$SMS_REMIND_OUT?>&SMS2_REMIND_OUT=<?=$SMS2_REMIND_OUT?>';"></center>
    <?
    exit;
}
//外出日期  比较时间是否冲突
if($OUT_DATE != "")
{
    if((empty($TO_ID) && $batch!="on") || (empty($COPY_TO_ID) && $batch=="on"))
    {
        $TO_ID = $_SESSION["LOGIN_USER_ID"];
    }
    //和请假时间做比较
    $b_time = $OUT_DATE." ".$OUT_TIME1;//开始时间
    $e_time = $OUT_DATE." ".$OUT_TIME2;//结束时间
    $sql    = "select * from ATTEND_LEAVE where  USER_ID = '".$TO_ID."' AND ((LEAVE_DATE1 >= '".$b_time."' AND LEAVE_DATE1 <= '".$e_time."') OR (LEAVE_DATE1 <= '".$b_time."'AND LEAVE_DATE2 >= '".$b_time."'))";
    $cursor = exequery(TD::conn(),$sql);
    if($ROW=mysql_fetch_array($cursor)){
        Message(_("错误"),_("您申请外出的时间和请假时间有冲突"));
        Button_Back();
        exit;
    }
    //和出差时间做比较
    $sql    = "select * from ATTEND_EVECTION where  USER_ID = '".$TO_ID."' AND (EVECTION_DATE1 <= '".$OUT_DATE."' AND EVECTION_DATE2 >= '".$OUT_DATE."')";
    $cursor = exequery(TD::conn(),$sql);
    if($ROW=mysql_fetch_array($cursor)){
        Message(_("错误"),_("您申请外出的时间和出差时间有冲突"));
        Button_Back();
        exit;
    }
    //在同一时间段是否重复提交外出申请
    $sql    = "select * from ATTEND_OUT where  USER_ID = '".$TO_ID."' AND ((concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) >= '".str_replace(' ','',$b_time)."' AND concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) <= '".str_replace(' ','',$e_time)."') OR (concat(LEFT (SUBMIT_TIME ,10),OUT_TIME2) >= '".str_replace(' ','',$b_time)."' AND concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) <= '".str_replace(' ','',$b_time)."'))";
    $cursor = exequery(TD::conn(),$sql);
    if($ROW=mysql_fetch_array($cursor)){
        Message(_("错误"),_("您此时间段已经申请过外出"));
        Button_Back();
        exit;
    }
    if($COPY_TO_ID!='' && $batch=="on")
    {
        $OUTER_USER_ID          = trim($COPY_TO_ID,',');
        $OUTER_USER_ID_ARRAY    = explode(',', $OUTER_USER_ID);
        $leave_count    = 0;
        $evection_count = 0;
        $out_count      = 0;
        $leave_user     = '';
        $evection_user  = '';
        $out_user='';
        for($i=0;$i<count($OUTER_USER_ID_ARRAY);$i++)
        {
            $OUTER_USER_ID1 = $OUTER_USER_ID_ARRAY[$i];
            //和请假时间做比较
            $sql    = "select * from ATTEND_LEAVE where  USER_ID = '".$OUTER_USER_ID1."' AND ((LEAVE_DATE1 >= '".$b_time."' AND LEAVE_DATE1 <= '".$e_time."') OR (LEAVE_DATE1 <= '".$b_time."'AND LEAVE_DATE2 >= '".$b_time."'))";
            $cursor = exequery(TD::conn(),$sql);
            if($ROW = mysql_fetch_array($cursor)){
                $leave_count++;
                $leave_user .=GetUserNameByUserId($OUTER_USER_ID1);
            }
            //和出差时间做比较
            $sql    = "select * from ATTEND_EVECTION where  USER_ID = '".$OUTER_USER_ID1."' AND (EVECTION_DATE1 <= '".$OUT_DATE."' AND EVECTION_DATE2 >= '".$OUT_DATE."')";
            $cursor = exequery(TD::conn(),$sql);
            if($ROW = mysql_fetch_array($cursor)){
                $evection_count++;
                $evection_user .=GetUserNameByUserId($OUTER_USER_ID1);
            }
            //在同一时间段是否重复提交外出申请
            $sql    = "select * from ATTEND_OUT where  USER_ID = '".$OUTER_USER_ID1."' AND ((concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) >= '".str_replace(' ','',$b_time)."' AND concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) <= '".str_replace(' ','',$e_time)."') OR (concat(LEFT (SUBMIT_TIME ,10),OUT_TIME2) >= '".str_replace(' ','',$b_time)."' AND concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) <= '".str_replace(' ','',$b_time)."'))";
            $cursor = exequery(TD::conn(),$sql);
            if($ROW = mysql_fetch_array($cursor)){
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
if($TO_ID2!="")
    $OUTER_USER_ID=$TO_ID2;
else
    $OUTER_USER_ID=$_SESSION["LOGIN_USER_ID"];

if($VU_START!="")
{
    $TIME_OK=is_date_time($VU_START);

    if(!$TIME_OK)
    { Message(_("错误"),_("起始时间格式不对，应形如 1999-1-2 09:30:00"));
        //Button_Back();
        ?>

        <br><center><input type="button" class="BigButton" value="<?=_("返回")?>" onclick="location='new.php?FLAG=1&TO_ID=<?=$TO_ID2?>&OUT_TYPE=<?=$OUT_TYPE?>&OUT_DATE=<?=$OUT_DATE?>&OUT_TIME1=<?=$OUT_TIME1?>&OUT_TIME2=<?=$OUT_TIME2?>&SMS_REMIND_OUT=<?=$SMS_REMIND_OUT?>&SMS2_REMIND_OUT=<?=$SMS2_REMIND_OUT?>';"></center>

        <?
        exit;
    }
}

if($VU_END!="")
{
    $TIME_OK=is_date_time($VU_END);

    if(!$TIME_OK)
    { Message(_("错误"),_("结束时间格式不对，应形如 1999-1-2 09:30:00"));
        //Button_Back();
        ?>

        <br><center><input type="button" class="BigButton" value="<?=_("返回")?>" onclick="location='new.php?FLAG=1&TO_ID=<?=$TO_ID2?>&OUT_TYPE=<?=$OUT_TYPE?>&OUT_DATE=<?=$OUT_DATE?>&OUT_TIME1=<?=$OUT_TIME1?>&OUT_TIME2=<?=$OUT_TIME2?>&SMS_REMIND_OUT=<?=$SMS_REMIND_OUT?>&SMS2_REMIND_OUT=<?=$SMS2_REMIND_OUT?>';"></center>

        <?
        exit;
    }
}

if($VU_START!=""&&$VU_END!=""&&$VU_START> $VU_END)
{
    Message(_("错误"),_("起始日期不能小于结束日期！"));
    //Button_Back();
    ?>

    <br><center><input type="button" class="BigButton" value="<?=_("返回")?>" onclick="location='new.php?FLAG=1&TO_ID=<?=$TO_ID2?>&OUT_TYPE=<?=$OUT_TYPE?>&OUT_DATE=<?=$OUT_DATE?>&OUT_TIME1=<?=$OUT_TIME1?>&OUT_TIME2=<?=$OUT_TIME2?>&SMS_REMIND_OUT=<?=$SMS_REMIND_OUT?>&SMS2_REMIND_OUT=<?=$SMS2_REMIND_OUT?>';"></center>

    <?
    exit;
}

if($VU_MILEAGE!=""&&!is_numeric($VU_MILEAGE))
{
    Message(_("错误"),_("申请里程应为数字！"));
    //Button_Back();
    ?>

    <br><center><input type="button" class="BigButton" value="<?=_("返回")?>" onclick="location='new.php?FLAG=1&TO_ID=<?=$TO_ID2?>&OUT_TYPE=<?=$OUT_TYPE?>&OUT_DATE=<?=$OUT_DATE?>&OUT_TIME1=<?=$OUT_TIME1?>&OUT_TIME2=<?=$OUT_TIME2?>&SMS_REMIND_OUT=<?=$SMS_REMIND_OUT?>&SMS2_REMIND_OUT=<?=$SMS2_REMIND_OUT?>';"></center>

    <?
    exit;
}

$V_ID = substr($V_ID,0,strpos($V_ID,"*"));
$SS = substr(check_car($VU_ID,$V_ID,$VU_START,$VU_END), 0, 1);
$VU_ID=intval($VU_ID);

if(is_number($SS))
{
    $_SESSION["VU_DRIVER"] = $VU_DRIVER;
    $_SESSION["VU_USER"] = $VU_USER;
    $DEPT_MANAGER=$TO_ID;
    $_SESSION["DEPT_MANAGER"] = $DEPT_MANAGER;
    $_SESSION["VU_DEPT"] = $VU_DEPT;
    $_SESSION["VU_SUITE"] = $VU_SUITE;
    $_SESSION["VU_DESTINATION"] = $VU_DESTINATION;
    $_SESSION["VU_REASON"] = $VU_REASON;
    $_SESSION["VU_REMARK"] = $VU_REMARK;
    $_SESSION["VU_MILEAGE"] = $VU_MILEAGE;

    Message(_("提示"),_("您所申请的车辆在使用中！"));

    ?>

    <br><center><input type="button" class="BigButton" value="<?=_("返回")?>" onclick="location='new.php?FLAG=1&TO_ID=<?=$TO_ID2?>&OUT_TYPE=<?=$OUT_TYPE?>&OUT_DATE=<?=$OUT_DATE?>&OUT_TIME1=<?=$OUT_TIME1?>&OUT_TIME2=<?=$OUT_TIME2?>&SMS_REMIND_OUT=<?=$SMS_REMIND_OUT?>&SMS2_REMIND_OUT=<?=$SMS2_REMIND_OUT?>';"></center>

    <?   exit;
}

unset($_SESSION["VU_DRIVER"]);
unset($_SESSION["VU_USER"]);
$DEPT_MANAGER=$TO_ID;
unset($_SESSION["DEPT_MANAGER"]);
unset($_SESSION["VU_DEPT"]);
unset($_SESSION["VU_SUITE"]);
unset($_SESSION["VU_DESTINATION"]);
unset($_SESSION["VU_OPERATOR"]);
unset($_SESSION["VU_REASON"]);
unset($_SESSION["VU_REMARK"]);
unset($_SESSION["VU_MILEAGE"]);


if($VU_MILEAGE=="")
    $VU_MILEAGE=0;

if($SMS_REMIND=="on")
    $SMS_REMIND="1";
else
    $SMS_REMIND="0";

if($SMS2_REMIND=="on")
    $SMS2_REMIND="1";
else
    $SMS2_REMIND="0";

if($SMS_REMIND_DRIVER=="on")
    $SMS_REMIND_DRIVER="1";
else
    $SMS_REMIND_DRIVER="0";

if($SMS2_REMIND_DRIVER=="on")
    $SMS2_REMIND_DRIVER="1";
else
    $SMS2_REMIND_DRIVER="0";

if($TO_ID!="")
    $SHOW_FLAG="0";
else
    $SHOW_FLAG="1";

if($TO_NAME1!="")
{
    $query1="select USER_ID from USER where USER_NAME='$TO_NAME1'";
    $cursor1= exequery(TD::conn(),$query1);
    $NUM=mysql_num_rows($cursor1);
    if($NUM==0)
        $VU_USER = $TO_NAME1;
    else
        $VU_USER = $TO_ID1;
}
$SUBMIT_TIME=$OUT_DATE." ".$OUT_TIME1;
$query="insert into ATTEND_OUT(USER_ID,LEADER_ID,OUT_TYPE,CREATE_DATE,SUBMIT_TIME,OUT_TIME1,OUT_TIME2,ALLOW,REGISTER_IP,ORDER_NO) values ('$OUTER_USER_ID','$LEADER_ID','$OUT_TYPE','$CUR_TIME','$SUBMIT_TIME','$OUT_TIME1','$OUT_TIME2','0','".get_client_ip()."','".$_SESSION["LOGIN_USER_ID"]."')";
exequery(TD::conn(),$query);
$attend_out_id = mysql_insert_id();

$query="insert into VEHICLE_USAGE(V_ID,VU_PROPOSER,VU_REQUEST_DATE,VU_USER,VU_SUITE,VU_MILEAGE,VU_REASON,VU_REMARK,VU_START,VU_END,VU_DEPT,VU_DESTINATION,VU_OPERATOR,VU_DRIVER,SMS_REMIND,SMS2_REMIND,DEPT_MANAGER,DMER_STATUS,SHOW_FLAG,SMS_REMIND_DRIVER,SMS2_REMIND_DRIVER,REMIND_CONTENT,IS_BACK) values('$V_ID','$VU_PROPOSER','$VU_REQUEST_DATE','$VU_USER','$VU_SUITE','$VU_MILEAGE','$VU_REASON','$VU_REMARK','$VU_START','$VU_END','$VU_DEPT','$VU_DESTINATION','$VU_OPERATOR','$VU_DRIVER','$SMS_REMIND','$SMS2_REMIND','$TO_ID','0','$SHOW_FLAG','$SMS_REMIND_DRIVER','$SMS2_REMIND_DRIVER','$REMIND_CONTENT',1)";
exequery(TD::conn(),$query);
$VU_ID=mysql_insert_id();

$query="SELECT V_NUM from VEHICLE where V_ID ='$V_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $V_NUM=$ROW["V_NUM"];

$OUTER_USER_NAME=getUserNamebyId($OUTER_USER_ID);
$data_array=array("KEY"=>"$attend_out_id","field"=>"OUT_ID","USER_ID"=>"$OUTER_USER_ID","USER_NAME"=>"$OUTER_USER_NAME","LEADER_ID"=>"$LEADER_ID","OUT_TYPE"=>"$OUT_TYPE","REASON"=>"$REASON","OUT_DATE"=>"$OUT_DATE","OUT_TIME1"=>"$OUT_TIME1","OUT_TIME2"=>"$OUT_TIME2");
$config= array("module"=>"attend_out");

$data_array1=array("KEY"=>"$VU_ID","field"=>"VU_ID","VU_PROPOSER"=>"$VU_PROPOSER","VU_DESTINATION"=>"$VU_DESTINATION","VU_MILEAGE"=>"$VU_MILEAGE","V_ID"=>"$V_NUM","VU_USER"=>"$VU_USER","VU_SUITE"=>"$VU_SUITE","VU_REASON"=>"$VU_REASON ","VU_DRIVER"=>"$VU_DRIVER","VU_REMARK"=>"$VU_REMARK ","VU_START"=>"$VU_START","VU_END"=>"$VU_END","VU_DEPT"=>"$VU_DEPT_FIELD_DESC","ATTACHMENT_ID"=>"$ATTACHMENT_ID","ATTACHMENT_NAME"=>"$ATTACHMENT_NAME","MODULE_SRC"=>"meeting","MODULE_DESC"=>"workflow");
$config1= array("module"=>"vehicle_apply");
$status=0;
run_hook($data_array,$config);
run_hook($data_array1,$config1);
if($status==0)
{

    if($TO_ID!="" && $TO_ID!=$_SESSION["LOGIN_USER_ID"])
    {
        $REMIND_URL="vehicle/dept_manage";
        if($SMS_REMIND1=="on")
            send_sms("",$VU_PROPOSER,$TO_ID,9,$VU_PROPOSER_NAME._("请求车辆申请部门审批，请批示！"),$REMIND_URL);

        if($SMS2_REMIND1=="on")
            send_mobile_sms_user("",$VU_PROPOSER,$TO_ID,$VU_PROPOSER_NAME._("请求车辆申请部门审批，请批示！"),9);

    }
    else if($VU_OPERATOR!=""&& $VU_OPERATOR!=$_SESSION["LOGIN_USER_ID"])
    {
        $REMIND_URL1="vehicle/checkup";
        if($SMS_REMIND1=="on")
            send_sms("",$VU_PROPOSER,$VU_OPERATOR,9,$VU_PROPOSER_NAME._("向您提交车辆申请，请批示！"),$REMIND_URL1);

        if($SMS2_REMIND1=="on")
            send_mobile_sms_user("",$VU_PROPOSER,$VU_OPERATOR,$VU_PROPOSER_NAME._("向您提交车辆申请，请批示！"),9);
    }
    else if($VU_OPERATOR1!=""&& $VU_OPERATOR1!=$_SESSION["LOGIN_USER_ID"])
    {
        $REMIND_URL1="vehicle/checkup";
        if($SMS_REMIND2=="on")
            send_sms("",$VU_PROPOSER,$VU_OPERATOR1,9,$VU_PROPOSER_NAME._("向您提交车辆申请，请批示！"),$REMIND_URL1);

        if($SMS2_REMIND2=="on")
            send_mobile_sms_user("",$VU_PROPOSER,$VU_OPERATOR1,$VU_PROPOSER_NAME._("向您提交车辆申请，请批示！"),9);
    }

    $SMS_CONTENT=$_SESSION["LOGIN_USER_NAME"]._("提交外出申请，请批示！");
    $REMIND_URL="attendance/manage/confirm/index.php";
    if($SMS_REMIND_OUT=="on")
        send_sms("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,6,$SMS_CONTENT,$REMIND_URL);

    if($SMS2_REMIND_OUT=="on")
        send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,$SMS_CONTENT,6);

    //Message(_("提示"),_("提交申请成功！"));
    //Button_Back();
    header("location: ../attendance/personal/out/index.php");
}
?>

</body>
</html>