<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$query="SELECT * FROM vehicle_usage WHERE VU_ID='$VU_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $VU_PROPOSER    = $ROW["VU_PROPOSER"];
    $VU_STATUS1     = $ROW["VU_STATUS"];
    $SMS_REMIND     = $ROW["SMS_REMIND"];
    $SMS2_REMIND    = $ROW["SMS2_REMIND"];
    $VU_DRIVERALL   = $ROW["VU_DRIVER"];
    $VU_USER        = $ROW["VU_USER"];
    $VU_DESTINATION = $ROW["VU_DESTINATION"];
    
    $V_ID           = $ROW['V_ID'];
    $VU_START       = $ROW['VU_START'];
    $VU_END         = $ROW['VU_END'];
    
    $VU_OPERATOR1_SMS_TYPE = $ROW["VU_OPERATOR1_SMS_TYPE"];
    $VU_OPERATOR1          = $ROW["VU_OPERATOR1"];
}

$SS = substr(check_car($VU_ID,$V_ID,$VU_START,$VU_END), 0, 1);

if($DMER_STATUS == 1 && is_number($SS))
{
    message(_("提示"),_("该车辆在此时间段已被申请使用"));
    Button_Back();
    Exit;
}

$DRIVER_PHONE_NO="";
$query="SELECT MOBIL_NO,USER_ID from USER where USER_NAME='$VU_DRIVER'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $DRIVER_PHONE_NO    = $ROW["MOBIL_NO"];
    $DRIVER_USER_ID     = $ROW["USER_ID"];
}

$query = "SELECT V_DRIVER,V_PHONE_NO from VEHICLE where V_DRIVER='$VU_DRIVERALL'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $DRIVER_PHONE_NOSTR = $ROW["V_PHONE_NO"];
    $DRIVER_PHONE_NOARR = explode(',',$DRIVER_PHONE_NOSTR);
    $TEMDRIVERARR = explode(',',$ROW["V_DRIVER"]);
    foreach($TEMDRIVERARR as $key => $value)
    {
        if($value == $VU_DRIVER)
        {
            $DRIVER_PHONE_NO = $DRIVER_PHONE_NOARR[$key];
        }
    }
}

$query = "SELECT USER_NAME from USER where USER_ID='$VU_USER'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $VU_USER_NAME = $ROW["USER_NAME"];
}
else
{
    $VU_USER_NAME = $VU_USER;
}
   
$query = "SELECT VU_START from VEHICLE_USAGE where VU_ID='$VU_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $VU_START =$ROW["VU_START"];
}

$MSG = sprintf(_("需要用车去%s,用车开始时间为：%s"),$VU_DESTINATION,$VU_START);
$DRIVER_SMS_CONTENT=$VU_USER_NAME.$MSG.$REMIND_CONTENT;

//给司机发手机短信
if($DRIVER_PHONE_NO!="" && $VU_STATUS==1 && $_POST['SMS2_REMIND']=="on")
{
    send_mobile_sms("",$_SESSION["LOGIN_USER_ID"],$DRIVER_PHONE_NO,$DRIVER_SMS_CONTENT);
}
//给司机发事务提醒
if($DRIVER_USER_ID!="" && $VU_STATUS==1 && $_POST['SMS_REMIND']=="on")
{
    send_sms("",$_SESSION["LOGIN_USER_ID"],$DRIVER_USER_ID,9,$DRIVER_SMS_CONTENT,"vehicle/usage_detail.php?VU_ID=$VU_ID");
}

if($VU_STATUS!=3)
{
    $query="update VEHICLE_USAGE set VU_STATUS='$VU_STATUS',OPERATOR_REASON='$OPERATOR_REASON' where VU_ID='$VU_ID'";
}
else
{
    $query="update VEHICLE_USAGE set VU_STATUS='$VU_STATUS' where VU_ID='$VU_ID'";
}
exequery(TD::conn(),$query);
if($VU_STATUS==4)
{
    //记录收回的实际时间
    $FINAL_TIME = date("Y-m-d H:i:s",time());
    $query="update VEHICLE set USEING_FLAG='0' where V_ID='$V_ID'";
    exequery(TD::conn(),$query);    
    
    $query="update VEHICLE_USAGE set VU_FINAL_END ='$FINAL_TIME',IS_BACK='2',VU_OPERATOR1_SMS_TYPE=0 where VU_ID='$VU_ID'";
    exequery(TD::conn(),$query);
}

$SYS_PARA_ARRAY = get_sys_para("SMS_REMIND");
$PARA_VALUE = $SYS_PARA_ARRAY["SMS_REMIND"];
$SMS_REMIND1 = substr($PARA_VALUE,0,strpos($PARA_VALUE,"|"));
//$SMS2_REMIND1=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);
$SMS2_REMIND1_TMP=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);
$SMS2_REMIND1=substr($SMS2_REMIND1_TMP,0,strpos($SMS2_REMIND1_TMP,"|"));
$REMIND_URL="vehicle"; 
if($VU_STATUS==0)
{
    $SMS_CONTENT=_("您的车辆申请已被撤销!");
    $REMIND_URL="vehicle/query.php?VU_STATUS=3&DMER_STATUS=3";
}
else if($VU_STATUS==1)
{
    $SMS_CONTENT=_("您的车辆申请已被批准!");
    $REMIND_URL="vehicle/query.php?VU_STATUS=1";
}

if($VU_STATUS==3)
{
    $SMS_CONTENT=_("您的车辆申请未被批准!");
    $REMIND_URL="vehicle/query.php?VU_STATUS=3&DMER_STATUS=3";
}

if(($VU_STATUS==0 || $VU_STATUS==1 || $VU_STATUS==3) && ($SMS_REMIND==1 || find_id($SMS_REMIND1,9)))
{
    send_sms("",$_SESSION["LOGIN_USER_ID"],$VU_PROPOSER,9,$SMS_CONTENT,$REMIND_URL);
}

if(($VU_STATUS==0 || $VU_STATUS==1 || $VU_STATUS==3) && ($SMS2_REMIND==1 || find_id($SMS2_REMIND1,9)))
{
    send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$VU_PROPOSER,$SMS_CONTENT,9);
}
if($VU_STATUS==1 && $VU_OPERATOR1_SMS_TYPE!=0 && $VU_OPERATOR1!="")
{
    $user_name   = td_trim(GetUserNameById($VU_PROPOSER));
    $SMS_CONTENT = _($user_name."申请的车辆审批通过，请注意回收");
    $REMIND_URL  = "vehicle/checkup/query.php?VU_STATUS=1&DMER_STATUS=2";
    
    if($VU_OPERATOR1_SMS_TYPE==3 || $VU_OPERATOR1_SMS_TYPE==1)
    {
        send_sms("",$_SESSION["LOGIN_USER_ID"],$VU_OPERATOR1,9,$SMS_CONTENT,$REMIND_URL);
    }
    
    if($VU_OPERATOR1_SMS_TYPE==3 || $VU_OPERATOR1_SMS_TYPE==2)
    {
        send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$VU_OPERATOR1,$SMS_CONTENT,9);
    }
}
   
if($VU_STATUS==3)
{
    Message(_("提示"),_("操作成功"));
?>
<br>
<script>
    opener.location.reload();
</script>

<center>
    <input type="button" class="BigButton" value="<?=_("关闭")?>" onClick="window.close();" title="<?=_("关闭窗口")?>">
</center>
<?
}
else
{
    header("location: query.php?VU_STATUS=$VU_STATUS1");
}

function check_car($VU_ID,$V_ID,$VU_START,$VU_END)
{
    $COUNT = 0;
    $CUR_TIME = date("Y-m-d H:i:s",time());
    $query = "select VU_START,VU_END,VU_ID from vehicle_usage where VU_ID!='$VU_ID' and V_ID='$V_ID' and (VU_STATUS in('1','2') and DMER_STATUS='1' or VU_STATUS in('1','2') and DMER_STATUS='0') and SHOW_FLAG='1'";
    $cursor = exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $VU_START1  = $ROW["VU_START"];
        $VU_END1    = $ROW["VU_END"];
        
        if(($VU_START1 >= $VU_START and $VU_END1 <= $VU_END) or ($VU_START1 < $VU_START and $VU_END1 > $VU_START) or ($VU_START1 < $VU_END and $VU_END1>$VU_END) or ($VU_START1 < $VU_START and $VU_END1 > $VU_END))
        {
            $COUNT++;
            $VU_IDD = $ROW["VU_ID"];
            break;
        }
    }
    
    $VU_ID = $VU_IDD;
    if($COUNT >= 1)
    {
        return $VU_ID;
    }
    else
    {
        return "#";
    }
}
?>

</body>
</html>
