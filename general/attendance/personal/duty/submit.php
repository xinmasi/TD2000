<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("���°�Ǽ�");
include_once("inc/header.inc.php");
?>


<body class="bodycolor">

<?
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());


$sql = "SELECT duty_type FROM user_duty WHERE uid = '".$_SESSION["LOGIN_UID"]."' AND duty_date = '$CUR_DATE'";
$cursor= exequery(TD::conn(),$sql);
if($row=mysql_fetch_array($cursor))
{
    $DUTY_TYPE_USER = $row[0];
}


//---- ȡ�涨���°�ʱ�� -----
$query = "SELECT * from ATTEND_CONFIG where DUTY_TYPE='$DUTY_TYPE_USER'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    if($REGISTER_TYPE==1)
    {
        $DUTY_TIME   = $ROW["DUTY_TIME1"];
        $DUTY_TYPE   = $ROW["DUTY_TYPE1"];
        $DUTY_BEFORE = $ROW["DUTY_BEFORE1"];
        $DUTY_AFTER  = $ROW["DUTY_AFTER1"];

        $TIME_LATE   = $ROW["TIME_LATE1"];
        $TIME_EARLY  = 0;
    }
    elseif($REGISTER_TYPE==2)
    {
        $DUTY_TIME   = $ROW["DUTY_TIME2"];
        $DUTY_TYPE   = $ROW["DUTY_TYPE2"];
        $DUTY_BEFORE = $ROW["DUTY_BEFORE2"];
        $DUTY_AFTER  = $ROW["DUTY_AFTER2"];

        $TIME_LATE   = 0;
        $TIME_EARLY  = $ROW["TIME_EARLY2"];
    }
    elseif($REGISTER_TYPE==3)
    {
        $DUTY_TIME   = $ROW["DUTY_TIME3"];
        $DUTY_TYPE   = $ROW["DUTY_TYPE3"];
        $DUTY_BEFORE = $ROW["DUTY_BEFORE3"];
        $DUTY_AFTER  = $ROW["DUTY_AFTER3"];

        $TIME_LATE   = $ROW["TIME_LATE3"];
        $TIME_EARLY  = 0;
    }
    elseif($REGISTER_TYPE==4)
    {
        $DUTY_TIME   = $ROW["DUTY_TIME4"];
        $DUTY_TYPE   = $ROW["DUTY_TYPE4"];
        $DUTY_BEFORE = $ROW["DUTY_BEFORE4"];
        $DUTY_AFTER  = $ROW["DUTY_AFTER4"];

        $TIME_LATE   = 0;
        $TIME_EARLY  = $ROW["TIME_EARLY4"];
    }
    elseif($REGISTER_TYPE==5)
    {
        $DUTY_TIME   = $ROW["DUTY_TIME5"];
        $DUTY_TYPE   = $ROW["DUTY_TYPE5"];
        $DUTY_BEFORE = $ROW["DUTY_BEFORE5"];
        $DUTY_AFTER  = $ROW["DUTY_AFTER5"];

        $TIME_LATE   = $ROW["TIME_LATE5"];
        $TIME_EARLY  = 0;
    }
    elseif($REGISTER_TYPE==6)
    {
        $DUTY_TIME   = $ROW["DUTY_TIME6"];
        $DUTY_TYPE   = $ROW["DUTY_TYPE6"];
        $DUTY_BEFORE = $ROW["DUTY_BEFORE6"];
        $DUTY_AFTER  = $ROW["DUTY_AFTER6"];

        $TIME_LATE   = 0;
        $TIME_EARLY  = $ROW["TIME_EARLY6"];
    }
}

$s_time = strtotime($DUTY_TIME)-$DUTY_BEFORE*60;
$e_time = strtotime($DUTY_TIME)+$DUTY_AFTER*60;


$REGISTER_TIME=$CUR_DATE." ".$DUTY_TIME;

if(strtotime($CUR_TIME)< $s_time || strtotime($CUR_TIME) > $e_time)
{
    if($DUTY_TYPE%2!=0)
        Message(_("����"),sprintf(_("�涨ʱ��֮ǰ %s ���ӣ�֮�� %s ������ɽ����ϰ�Ǽ�!"), $DUTY_BEFORE, $DUTY_AFTER));
    else
        Message(_("����"),sprintf(_("�涨ʱ��֮ǰ %s ���ӣ�֮�� %s ������ɽ����°�Ǽ�!"), $DUTY_BEFORE, $DUTY_AFTER));
    Button_Back();
    exit;
}


/*$query = "SELECT a.DEPT_HR_MANAGER from HR_MANAGER a left join USER b on a.DEPT_ID=b.DEPT_ID where b.USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
$DEPT_HR_MANAGER="";
if($ROW=mysql_fetch_array($cursor))
{
    $DEPT_HR_MANAGER = td_trim($ROW["DEPT_HR_MANAGER"]);
}

if($CUR_TIME > $REGISTER_TIME && $REGISTER_TYPE%2!=0&&$DEPT_HR_MANAGER!="")
{
    $SMS_CONTENT = $_SESSION["LOGIN_USER_NAME"]._("�ٵ����Ǽ�ʱ��Ϊ��").$CUR_TIME;
    send_sms("",$_SESSION["LOGIN_USER_ID"],$DEPT_HR_MANAGER,0,$SMS_CONTENT,$REMIND_URL);
}
if($CUR_TIME < $REGISTER_TIME && $REGISTER_TYPE%2==0&&$DEPT_HR_MANAGER!="")
{
    $SMS_CONTENT = $_SESSION["LOGIN_USER_NAME"]._("���ˣ��Ǽ�ʱ��Ϊ��").$CUR_TIME;
    send_sms("",$_SESSION["LOGIN_USER_ID"],$DEPT_HR_MANAGER,0,$SMS_CONTENT,$REMIND_URL);
}*/



$query = "SELECT * from ATTEND_DUTY where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and REGISTER_TYPE='$REGISTER_TYPE' and to_days(REGISTER_TIME)=to_days('$CUR_TIME')";
$cursor= exequery(TD::conn(),$query);
if(!$ROW=mysql_fetch_array($cursor))
{
    $query="insert into ATTEND_DUTY(USER_ID,REGISTER_TYPE,REGISTER_TIME,REGISTER_IP,DUTY_TYPE,DUTY_TIME,TIME_LATE,TIME_EARLY) values ('".$_SESSION["LOGIN_USER_ID"]."','$REGISTER_TYPE','$CUR_TIME','".get_client_ip()."','$DUTY_TYPE_USER','$DUTY_TIME','$TIME_LATE','$TIME_EARLY')";
    exequery(TD::conn(),$query);
}
else
{
    $query="update ATTEND_DUTY set REGISTER_TIME='$CUR_TIME',REGISTER_IP='".get_client_ip()."' where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and REGISTER_TYPE='$REGISTER_TYPE' and to_days(REGISTER_TIME)=to_days('$CUR_TIME')";
    exequery(TD::conn(),$query);
}
header("location: index.php?connstatus=1");
?>

</body>
</html>
