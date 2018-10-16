<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("上下班登记");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">

<?
 $CUR_DATE=date("Y-m-d",time());
 $CUR_TIME=date("Y-m-d H:i:s",time());

 $query1="SELECT * from USER_EXT,USER where USER.UID=USER_EXT.UID and USER.USER_ID='$USER_ID'";
 $cursor1= exequery(TD::conn(),$query1);
 if($ROW=mysql_fetch_array($cursor1))
    $DUTY_TYPE=$ROW["DUTY_TYPE"];


 //---- 取规定上下班时间 -----
 $query = "SELECT * from ATTEND_CONFIG where DUTY_TYPE='$DUTY_TYPE'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    if($REGISTER_TYPE==1)
       {$DUTY_TIME=$ROW["DUTY_TIME1"];$DUTY_TYPE=$ROW["DUTY_TYPE1"];}
    elseif($REGISTER_TYPE==2)
       {$DUTY_TIME=$ROW["DUTY_TIME2"];$DUTY_TYPE=$ROW["DUTY_TYPE2"];}
    elseif($REGISTER_TYPE==3)
       {$DUTY_TIME=$ROW["DUTY_TIME3"];$DUTY_TYPE=$ROW["DUTY_TYPE3"];}
    elseif($REGISTER_TYPE==4)
       {$DUTY_TIME=$ROW["DUTY_TIME4"];$DUTY_TYPE=$ROW["DUTY_TYPE4"];}
    elseif($REGISTER_TYPE==5)
       {$DUTY_TIME=$ROW["DUTY_TIME5"];$DUTY_TYPE=$ROW["DUTY_TYPE5"];}
    elseif($REGISTER_TYPE==6)
       {$DUTY_TIME=$ROW["DUTY_TIME6"];$DUTY_TYPE=$ROW["DUTY_TYPE6"];}
 }

$DUTY_INTERVAL_BEFORE="DUTY_INTERVAL_BEFORE".$DUTY_TYPE;
$query = "SELECT * from SYS_PARA where PARA_NAME='$DUTY_INTERVAL_BEFORE'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $DUTY_INTERVAL_BEFORE=$ROW["PARA_VALUE"];

$DUTY_INTERVAL_AFTER="DUTY_INTERVAL_AFTER".$DUTY_TYPE;
$query = "SELECT * from SYS_PARA where PARA_NAME='$DUTY_INTERVAL_AFTER'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $DUTY_INTERVAL_AFTER=$ROW["PARA_VALUE"];


$REGISTER_TIME=$CUR_DATE." ".$DUTY_TIME;

if(strtotime($REGISTER_TIME)-strtotime($CUR_TIME)> $DUTY_INTERVAL_BEFORE*60 || strtotime($CUR_TIME)-strtotime($REGISTER_TIME)>$DUTY_INTERVAL_AFTER*60)
{

   if($DUTY_TYPE=="1")Message(_("警告"),sprintf(_("规定时间之前 %s 分钟，之后 %s 分钟起可进行上班登记!"), $DUTY_INTERVAL_BEFORE, $DUTY_INTERVAL_AFTER));
   if($DUTY_TYPE=="2")Message(_("警告"),sprintf(_("规定时间之前 %s 分钟，之后 %s 分钟起可进行下班登记!"), $DUTY_INTERVAL_BEFORE, $DUTY_INTERVAL_AFTER));
   Button_Back();
   exit;
}

$query = "SELECT a.DEPT_HR_MANAGER from HR_MANAGER a left join USER b on a.DEPT_ID=b.DEPT_ID where b.USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
$DEPT_HR_MANAGER="";
if($ROW=mysql_fetch_array($cursor))
   $DEPT_HR_MANAGER = td_trim($ROW["DEPT_HR_MANAGER"]);
if($CUR_TIME > $REGISTER_TIME && $REGISTER_TYPE%2!=0&&$DEPT_HR_MANAGER!="")
{
   $SMS_CONTENT = $_SESSION["LOGIN_USER_NAME"]._("迟到，登记时间为：").$CUR_TIME;
	 send_sms("",$_SESSION["LOGIN_USER_ID"],$DEPT_HR_MANAGER,0,$SMS_CONTENT,$REMIND_URL);
}
if($CUR_TIME < $REGISTER_TIME && $REGISTER_TYPE%2==0&&$DEPT_HR_MANAGER!="")
{
   $SMS_CONTENT = $_SESSION["LOGIN_USER_NAME"]._("早退，登记时间为：").$CUR_TIME;
	 send_sms("",$_SESSION["LOGIN_USER_ID"],$DEPT_HR_MANAGER,0,$SMS_CONTENT,$REMIND_URL);
}
//轮班考勤登记  qpp by 2012-06-15
$query = "SELECT * from SYS_PARA where PARA_NAME='SHIFT_USER'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $SHIFT_USER=$ROW["PARA_VALUE"];

if(!find_id($SHIFT_USER,$_SESSION["LOGIN_USER_ID"]))
{
   $query = "SELECT * from ATTEND_DUTY where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and REGISTER_TYPE='$REGISTER_TYPE' and to_days(REGISTER_TIME)=to_days('$CUR_TIME')";
   $cursor= exequery(TD::conn(),$query);
   if(!$ROW=mysql_fetch_array($cursor))
   {
     $query="insert into ATTEND_DUTY(USER_ID,REGISTER_TYPE,REGISTER_TIME,REGISTER_IP) values ('".$_SESSION["LOGIN_USER_ID"]."','$REGISTER_TYPE','$CUR_TIME','".get_client_ip()."')";
     exequery(TD::conn(),$query);
   }
   else
   {
     $query="update ATTEND_DUTY set REGISTER_TIME='$CUR_TIME',REGISTER_IP='".get_client_ip()."' where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and REGISTER_TYPE='$REGISTER_TYPE' and to_days(REGISTER_TIME)=to_days('$CUR_TIME')";
     exequery(TD::conn(),$query);
   }
}
else
{
   $query = "SELECT * from ATTEND_SHIFT where USER_ID='".$_SESSION["LOGIN_USER_ID"]." and REGISTER_TYPE='$REGISTER_TYPE'";
   $cursor= exequery(TD::conn(),$query);
   if(!$ROW=mysql_fetch_array($cursor))
   {
     $query="insert into ATTEND_SHIFT(USER_ID,REGISTER_TYPE,REGISTER_TIME,REGISTER_IP) values ('".$_SESSION["LOGIN_USER_ID"]."','$REGISTER_TYPE','$CUR_TIME','".get_client_ip()."')";
     exequery(TD::conn(),$query);
   }
   else
   {
     $query="update ATTEND_SHIFT set REGISTER_TIME='$CUR_TIME',REGISTER_IP='".get_client_ip()."' where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and REGISTER_TYPE='$REGISTER_TYPE' and to_days(REGISTER_TIME)=to_days('$CUR_TIME')";
     exequery(TD::conn(),$query);
   }
}
}
header("location: index.php?connstatus=1");
?>

</body>
</html>
