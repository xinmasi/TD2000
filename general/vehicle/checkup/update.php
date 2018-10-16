<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
//----------- 合法性校验 ---------
$CUR_TIME=date("Y-m-d H:i:s",time());

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

if($VU_START!="" && $VU_END!="" && compare_date_time($VU_END,$VU_START)<=0)
{
    Message(_("错误"),_("结束时间不能小于起始时间！"));
    Button_Back();
    exit;
}

if($VU_MILEAGE!=""&&!is_numeric($VU_MILEAGE))
{
    Message(_("错误"),_("申请里程应为数字！"));
    Button_Back();
    exit;
}
$VU_ID=intval($VU_ID);
$query="select VU_STATUS from VEHICLE_USAGE where VU_ID='$VU_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $VU_STATUS1=$ROW["VU_STATUS"];

$V_ID=substr($V_ID,0,strpos($V_ID,"*"));

if($VU_STATUS1==2 && compare_date_time($VU_START,$CUR_TIME)==1)
{
    $MY_STR="VU_STATUS='1',";
    $query1="update VEHICLE set USEING_FLAG=0 where V_ID='$V_ID'";
    exequery(TD::conn(),$query1);
}

if($VU_MILEAGE=="")
    $VU_MILEAGE=0;



if($VU_ID=="")
    $query="insert into VEHICLE_USAGE (V_ID,VU_PROPOSER,VU_REQUEST_DATE,VU_USER,VU_MILEAGE,VU_REASON,VU_REMARK,VU_START,VU_END,VU_DEPT,VU_DESTINATION,VU_OPERATOR,VU_OPERATOR1,VU_DRIVER) values('$V_ID','$VU_PROPOSER','$VU_REQUEST_DATE','$VU_USER','$VU_MILEAGE','$VU_REASON','$VU_REMARK','$VU_START','$VU_END','$VU_DEPT','$VU_DESTINATION','$VU_OPERATOR','$VU_OPERATOR1','$VU_DRIVER')";
else
{
    $where = "";
    if($VU_STATUS==3)
    {
        $where = " ,VU_STATUS='0'";
    }
    $query="update VEHICLE_USAGE set ".$MY_STR."V_ID='$V_ID',VU_PROPOSER='$VU_PROPOSER',VU_REQUEST_DATE='$VU_REQUEST_DATE',VU_USER='$VU_USER',VU_MILEAGE='$VU_MILEAGE',VU_REASON='$VU_REASON',VU_REMARK='$VU_REMARK',VU_START='$VU_START',VU_END='$VU_END',VU_DEPT='$VU_DEPT',VU_DESTINATION='$VU_DESTINATION',VU_OPERATOR='$VU_OPERATOR',VU_OPERATOR1='$VU_OPERATOR1',VU_DRIVER='$VU_DRIVER'".$where." where VU_ID='$VU_ID'";

}

exequery(TD::conn(),$query);
if($VU_ID=="")
    $VU_ID=mysql_insert_id();
$SYS_PARA_ARRAY=get_sys_para("SMS_REMIND");
$PARA_VALUE=$SYS_PARA_ARRAY["SMS_REMIND"];
$SMS_REMIND1=substr($PARA_VALUE,0,strpos($PARA_VALUE,"|"));

$REMIND_URL="vehicle/checkup";
if($VU_OPERATOR!="" && $VU_OPERATOR!=$_SESSION["LOGIN_USER_ID"] && find_id($SMS_REMIND1,9))
    send_sms("",$VU_PROPOSER,$VU_OPERATOR,9,$VU_PROPOSER_NAME._("向您提交车辆申请，请批示！"),$REMIND_URL);

header("location: query.php?VU_STATUS=$VU_STATUS");
?>

</body>
</html>
