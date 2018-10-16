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
if($VU_START!="")
{
  $TIME_OK=is_date_time($VU_START);

  if(!$TIME_OK)
  { Message(_("错误"),_("起始时间格式不对，应形如 1999-1-2 09:30:00"));
    Button_Back();
    exit;
  }
}

if($VU_END!="")
{
  $TIME_OK=is_date_time($VU_END);

  if(!$TIME_OK)
  { Message(_("错误"),_("结束时间格式不对，应形如 1999-1-2 09:30:00"));
    Button_Back();
    exit;
  }
}

if($VU_START!=""&&$VU_END!=""&&$VU_START> $VU_END)
{
   Message(_("错误"),_("起始日期不能小于结束日期！"));
   Button_Back();
   exit;
}

if($VU_MILEAGE!=""&&!is_numeric($VU_MILEAGE))
{
   Message(_("错误"),_("申请里程应为数字！"));
   Button_Back();
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
    Button_Back();
    exit;
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

if($SMS_REMIND2=="on" && $SMS2_REMIND2=="on")
{
    $VU_OPERATOR1_SMS_TYPE = '3';
}
else if($SMS_REMIND2=="on")
{
    $VU_OPERATOR1_SMS_TYPE = '1';
}
else if($SMS2_REMIND2=="on")
{
    $VU_OPERATOR1_SMS_TYPE = '2';
}
   
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
if($VU_ID=="")
   $query="insert into VEHICLE_USAGE(V_ID,VU_PROPOSER,VU_REQUEST_DATE,VU_USER,VU_SUITE,VU_MILEAGE,VU_REASON,VU_REMARK,VU_START,VU_END,VU_DEPT,VU_DESTINATION,VU_OPERATOR,VU_DRIVER,SMS_REMIND,SMS2_REMIND,DEPT_MANAGER,DMER_STATUS,SHOW_FLAG,SMS_REMIND_DRIVER,SMS2_REMIND_DRIVER,REMIND_CONTENT,IS_BACK,VU_OPERATOR1,VU_OPERATOR1_SMS_TYPE) values('$V_ID','$VU_PROPOSER','$VU_REQUEST_DATE','$VU_USER','$VU_SUITE','$VU_MILEAGE','$VU_REASON','$VU_REMARK','$VU_START','$VU_END','$VU_DEPT','$VU_DESTINATION','$VU_OPERATOR','$VU_DRIVER','$SMS_REMIND','$SMS2_REMIND','$TO_ID','0','$SHOW_FLAG','$SMS_REMIND_DRIVER','$SMS2_REMIND_DRIVER','$REMIND_CONTENT',1,'$VU_OPERATOR1','$VU_OPERATOR1_SMS_TYPE')";
else
{	
   $query="update VEHICLE_USAGE set SHOW_FLAG='$SHOW_FLAG',V_ID='$V_ID',VU_PROPOSER='$VU_PROPOSER',VU_REQUEST_DATE='$VU_REQUEST_DATE',VU_USER='$VU_USER',VU_SUITE='$VU_SUITE',VU_MILEAGE='$VU_MILEAGE',VU_REASON='$VU_REASON',VU_REMARK='$VU_REMARK',VU_START='$VU_START',VU_END='$VU_END',VU_DEPT='$VU_DEPT',VU_DESTINATION='$VU_DESTINATION',VU_OPERATOR='$VU_OPERATOR',VU_DRIVER='$VU_DRIVER',SMS_REMIND='$SMS_REMIND',SMS2_REMIND='$SMS2_REMIND',DEPT_MANAGER='$TO_ID',DMER_STATUS='0',SMS_REMIND_DRIVER='$SMS_REMIND_DRIVER',SMS2_REMIND_DRIVER='$SMS2_REMIND_DRIVER',REMIND_CONTENT='$REMIND_CONTENT',VU_OPERATOR1='$VU_OPERATOR1',VU_OPERATOR1_SMS_TYPE='$VU_OPERATOR1_SMS_TYPE',VU_STATUS='0' where VU_ID='$VU_ID'";
}
   exequery(TD::conn(),$query);
if($VU_ID=="")
   $VU_ID=mysql_insert_id();

$query="SELECT V_NUM from VEHICLE where V_ID ='$V_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $V_NUM=$ROW["V_NUM"];

$data_array=array("KEY"=>"$VU_ID","field"=>"VU_ID","VU_PROPOSER"=>"$VU_PROPOSER","VU_DESTINATION"=>"$VU_DESTINATION","VU_MILEAGE"=>"$VU_MILEAGE","V_ID"=>"$V_NUM","VU_USER_ID"=>"$TO_ID1","VU_USER"=>"$TO_NAME1","VU_SUITE"=>"$VU_SUITE","VU_REASON"=>"$VU_REASON ","VU_DRIVER"=>"$VU_DRIVER","VU_REMARK"=>"$VU_REMARK ","VU_START"=>"$VU_START","VU_END"=>"$VU_END","VU_DEPT"=>"$VU_DEPT_FIELD_DESC","ATTACHMENT_ID"=>"$ATTACHMENT_ID","ATTACHMENT_NAME"=>"$ATTACHMENT_NAME","MODULE_SRC"=>"meeting","MODULE_DESC"=>"workflow");
$config= array("module"=>"vehicle_apply");
$status=0;
run_hook($data_array,$config);
if($status==0)
{
    $vehicle_hooked = get_sys_para("VEHICLE_HOOKED");
    $vehicle_hooked = $vehicle_hooked["VEHICLE_HOOKED"];
    $is_hook_vehicle = 0;
    
    if($vehicle_hooked)
    {
        $params = array(
            'TO_ID' => $TO_ID,
            'VU_OPERATOR' => $VU_OPERATOR,
            'VU_ID' => $VU_ID,
            'VU_SMS_REMIND' => $SMS_REMIND,
            'VU_PROPOSER' => $VU_PROPOSER
        );
        $is_hook_vehicle = bmp_hook_vehicle($params);
    }
    
    if(!$is_hook_vehicle)
    {
        if($TO_ID !="" && $TO_ID != $_SESSION["LOGIN_USER_ID"])
        {
            $REMIND_URL="vehicle/dept_manage";
            if($SMS_REMIND1=="on")
                send_sms("",$VU_PROPOSER,$TO_ID,9,$VU_PROPOSER_NAME._("请求车辆申请部门审批，请批示！"),$REMIND_URL);

            if($SMS2_REMIND1=="on")
                send_mobile_sms_user("",$VU_PROPOSER,$TO_ID,$VU_PROPOSER_NAME._("请求车辆申请部门审批，请批示！"),9);
        }
        if($VU_OPERATOR !=""&& $VU_OPERATOR != $_SESSION["LOGIN_USER_ID"] && $TO_ID =="")
        {
            $REMIND_URL1="vehicle/checkup";
            if($SMS_REMIND1=="on")
                send_sms("",$VU_PROPOSER,$VU_OPERATOR,9,$VU_PROPOSER_NAME._("向您提交车辆申请，请批示！"),$REMIND_URL1);

            if($SMS2_REMIND1=="on")
                send_mobile_sms_user("",$VU_PROPOSER,$VU_OPERATOR,$VU_PROPOSER_NAME._("向您提交车辆申请，请批示！"),9);
        }
    }

    if($_GET['VU_ID']=="")
    {
        Message(_("提示"),_("提交申请成功！")); 
    }
    else
    {
        Message(_("提示"),_("修改成功！"));
    }
?>
    <center><input type="button"  class="BigButtonA" value="返回" onclick="location.href='./query.php?VU_STATUS=0'" style="margin-top:20px;"/></center>
<?
}
?>
</body>
</html>