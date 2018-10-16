<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_sms1.php");
$CUR_TIME=date("Y-m-d H:i:s",time());
if($BEFORE_DAY != "" && !is_numeric($BEFORE_DAY))
{
    Message(_("错误"),_("提前提醒天数应为整数！"));
    Button_Back();
    exit;
}

if($BEFORE_HOUR != "" && !is_numeric($BEFORE_HOUR))
{
    Message(_("错误"),_("提前提醒小时应为整数！"));
    Button_Back();
    exit;
}

if($BEFORE_MIN != "" && !is_numeric($BEFORE_MIN))
{
    Message(_("错误"),_("提前提醒分钟应为整数！"));
    Button_Back();
    exit;
}

$BEFORE_DAY=intval($BEFORE_DAY);
$BEFORE_HOUR=intval($BEFORE_HOUR);
$BEFORE_MIN=intval($BEFORE_MIN)==0 ? 10 : intval($BEFORE_MIN);

$REMIND_TIME=date("Y-m-d H:i:s",strtotime("- $BEFORE_DAY days - $BEFORE_HOUR hours - $BEFORE_MIN minutes",strtotime($VM_REQUEST_DATE)));
if($REMIND_TIME < $CUR_TIME)
    $REMIND_TIME = $CUR_TIME;

$query2="select CODE_NAME,CODE_EXT from SYS_CODE where PARENT_NO='VEHICLE_REPAIR_TYPE' and CODE_NO ='$VM_TYPE'";
$cursor2= exequery(TD::conn(),$query2);
if($ROW2=mysql_fetch_array($cursor2))
{
    $VM_TYPE_NAME=$ROW2["CODE_NAME"];
    $CODE_EXT=unserialize($ROW["CODE_EXT"]);
    if(is_array($CODE_EXT) && $CODE_EXT[MYOA_LANG_COOKIE] != "")
        $VM_TYPE_NAME = $CODE_EXT[MYOA_LANG_COOKIE];
}

$query = "SELECT V_MODEL from VEHICLE where V_ID='$V_ID'";
$cursor1= exequery(TD::conn(),$query);
if($ROW1=mysql_fetch_array($cursor1))
    $V_MODEL=$ROW1["V_MODEL"];

if($SMS_REMIND1=="on" && $VM_REQUEST_DATE!="NULL" && $VU_OPERATOR!="")
{
    $MSG1 = sprintf(_("车辆维护：%s需要在%s进行维护，维护类型：(%s)原因:%s"),$V_MODEL,$VM_REQUEST_DATE,$VM_TYPE_NAME,$VM_REASON);
    send_sms($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$VU_OPERATOR,48,$MSG1);
}

if($SMS2_REMIND1=="on" && $VM_REQUEST_DATE!="NULL" && $VU_OPERATOR!="")
{
    $MSG2 = sprintf(_("车辆维护：%s需要在%s进行维护，维护类型：(%s)原因:%s"),$V_MODEL,$VM_REQUEST_DATE,$VM_TYPE_NAME,$VM_REASON);
    send_mobile_sms_user($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$_SESSION["LOGIN_USER_ID"],$MSG2,5);
}
Message("",_("保存成功"));
?>
<center>
    <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();">
</center>