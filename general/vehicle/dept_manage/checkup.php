<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$query="select * from VEHICLE_USAGE where VU_ID='$VU_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $VU_OPERATOR=$ROW["VU_OPERATOR"];
   $DEPT_MANAGER=$ROW["DEPT_MANAGER"];
   $VU_PROPOSER=$ROW["VU_PROPOSER"];
   $DMER_STATUS1=$ROW["DMER_STATUS"];
   $VU_DRIVER=$ROW["VU_DRIVER"];
   $REMIND_CONTENT=$ROW["REMIND_CONTENT"];
   $SMS_REMIND_DRIVER=$ROW["SMS_REMIND_DRIVER"];
   $SMS2_REMIND_DRIVER=$ROW["SMS2_REMIND_DRIVER"];
   $VU_START=$ROW["VU_START"];
   $VU_END=$ROW["VU_END"];
   $V_ID=$ROW["V_ID"];

   $query="select V_MODEL from VEHICLE where V_ID='$V_ID'";
   $cursor=exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $V_MODEL=$ROW["V_MODEL"];
}

$SS = substr(check_car($VU_ID,$V_ID,$VU_START,$VU_END), 0, 1);
if($DMER_STATUS == 1 && is_number($SS))
{
   message(_("提示"),_("该车辆在此时间段已被申请使用"));
   Button_Back();
   Exit;
}
$query = "SELECT USER_NAME from USER where USER_ID='$VU_OPERATOR'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $VU_OPERATOR_NAME=$ROW["USER_NAME"];

$query = "SELECT USER_NAME from USER where USER_ID='$DEPT_MANAGER'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $DEPT_MANAGER_NAME=$ROW["USER_NAME"];

$query = "SELECT USER_NAME from USER where USER_ID='$VU_PROPOSER'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $VU_PROPOSER_NAME=$ROW["USER_NAME"];

$query = "SELECT USER_ID from USER where USER_NAME='$VU_DRIVER'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$VU_DRIVER_ID=$ROW["USER_ID"];
}

$query="select PARA_VALUE from SYS_PARA where PARA_NAME='SMS_REMIND'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $PARA_VALUE=$ROW["PARA_VALUE"];
$SMS_REMIND=substr($PARA_VALUE,0,strpos($PARA_VALUE,"|"));
$SMS2_REMIND_TMP=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);
$SMS2_REMIND=substr($SMS2_REMIND_TMP,0,strpos($SMS2_REMIND_TMP,"|"));

if($DMER_STATUS==0)
   $VALUE_STR="DMER_STATUS='$DMER_STATUS',SHOW_FLAG='0'";

if($DMER_STATUS==1)
{
   $VALUE_STR="DMER_STATUS='$DMER_STATUS',SHOW_FLAG='1',DEPT_REASON='$DEPT_REASON'";
   $SMS_CONTENT = sprintf(_("部门领导%s批准了车辆申请!"),$DEPT_MANAGER_NAME);
   $SMS2_CONTENT= sprintf(_("%s批准了%s提交的车辆申请，车牌号为：%s，%s"),$DEPT_MANAGER_NAME,$VU_PROPOSER_NAME,$V_MODEL,$REMIND_CONTENT);
   $REMIND_URL1="vehicle/checkup";
	 if(find_id($SMS_REMIND,9))
      send_sms("",$VU_PROPOSER,$VU_OPERATOR,9,$SMS_CONTENT,$REMIND_URL1);
   if($SMS_REMIND_DRIVER==1 && $VU_DRIVER_ID!="")
      send_sms("",$VU_PROPOSER,$VU_DRIVER_ID,9,$SMS2_CONTENT,$REMIND_URL1);
   if(find_id($SMS2_REMIND,9)){
   	  $MSG = sprintf(_("%s批准了%s提交的车辆申请！"),$DEPT_MANAGER_NAME,$VU_PROPOSER_NAME);
      send_mobile_sms_user("",$VU_PROPOSER,$VU_OPERATOR,$MSG,9);
   }
//   if($SMS2_REMIND_DRIVER=1 && $VU_DRIVER_ID!=""){
//      send_mobile_sms("",$VU_PROPOSER,$V_PHONE_NO,$SMS2_CONTENT);
//   }
}
if($DMER_STATUS==3)
{
   $VALUE_STR   = "DMER_STATUS='$DMER_STATUS'";
   $SMS_CONTENT = sprintf(_("部门领导%s未批准车辆申请!"),$DEPT_MANAGER_NAME);
   $REMIND_URL  = "vehicle/query.php?VU_STATUS=0&DMER_STATUS=3";
   if(find_id($SMS_REMIND,9)){
       send_sms("",$DEPT_MANAGER,$VU_PROPOSER,9,$SMS_CONTENT,$REMIND_URL);
   }
   if(find_id($SMS2_REMIND,9)){
   	  $MSG1 = sprintf(_("%s未批准%s提交的车辆申请！"),$DEPT_MANAGER_NAME,$VU_PROPOSER_NAME);
      send_mobile_sms_user("",$VU_PROPOSER,$VU_OPERATOR,$MSG1,9);
   }
}

$query="update VEHICLE_USAGE set ".$VALUE_STR." where VU_ID='$VU_ID'";
exequery(TD::conn(),$query);

if($DMER_STATUS==3)
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
   header("location: query.php?DMER_STATUS=$DMER_STATUS1");
?>
</body>
</html>


<?
function check_car($VU_ID,$V_ID,$VU_START,$VU_END)
{
   $query="select VU_START,VU_END,VU_ID from vehicle_usage where VU_ID!='$VU_ID' and V_ID='$V_ID' and (VU_STATUS in('0','1','2') and DMER_STATUS='1' or VU_STATUS in('1','2') and DMER_STATUS='0') and SHOW_FLAG='1'";
   $cursor=exequery(TD::conn(),$query);
   $COUNT=0;
   while($ROW=mysql_fetch_array($cursor))
   {
     $VU_START1=$ROW["VU_START"];
     $VU_END1=$ROW["VU_END"];
     if(($VU_START1 >= $VU_START and $VU_END1 <= $VU_END) or ($VU_START1 < $VU_START and $VU_END1 > $VU_START) or ($VU_START1 < $VU_END and $VU_END1>$VU_END) or ($VU_START1 < $VU_START and $VU_END1 > $VU_END))
     {
     	  $COUNT++;
        $VU_IDD = $VU_IDD.$ROW["VU_ID"].",";
     }
   }
   $VU_ID = $VU_IDD;
   if($COUNT >= 1)
      return $VU_ID;
   else
      return "#";
}
?>