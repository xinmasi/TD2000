<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("销假");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$CUR_DATE=date("Y-m-d H:i:s",time());
 
if($STATUS=="")
{
   if($CONFIRM==2)
      $query="update ATTEND_LEAVE set ALLOW='$CONFIRM',REASON='$REASON',HANDLE_TIME='$CUR_DATE' where LEAVE_ID='$LEAVE_ID'";
   else
   {
      $query1 = "SELECT LEAVE_TYPE from ATTEND_LEAVE where LEAVE_ID='$LEAVE_ID'";
      $cursor1= exequery(TD::conn(),$query1);
      if($ROW1=mysql_fetch_array($cursor1)) 
         $LEAVE_TYPE1=$ROW1["LEAVE_TYPE"];

      if(csubstr($LEAVE_TYPE1, 0, 6)==_("补假："))
         $query="update ATTEND_LEAVE set ALLOW='3',STATUS='2',HANDLE_TIME='$CUR_DATE' where LEAVE_ID='$LEAVE_ID'"; 
      else   
         $query="update ATTEND_LEAVE set ALLOW='$CONFIRM',HANDLE_TIME='$CUR_DATE' where LEAVE_ID='$LEAVE_ID'"; 
   } 
}  
else
{
   $LEAVE_ID=intval($LEAVE_ID);
   $query="update ATTEND_LEAVE set STATUS='2',HANDLE_TIME='$CUR_DATE' where LEAVE_ID='$LEAVE_ID'";
}
exequery(TD::conn(),$query);

//---------- 事务提醒 ----------
$query="select * from SYS_PARA where PARA_NAME='SMS_REMIND'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $PARA_VALUE=$ROW["PARA_VALUE"];
$SMS_REMIND1=substr($PARA_VALUE,0,strpos($PARA_VALUE,"|"));
//$SMS2_REMIND1=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);
$SMS2_REMIND1_TMP=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);
$SMS2_REMIND1=substr($SMS2_REMIND1_TMP,0,strpos($SMS2_REMIND1_TMP,"|"));

if($STATUS=="")
{
   if($CONFIRM==1)
      $SMS_CONTENT=_("您的请假申请已被批准！");
   else
      $SMS_CONTENT=_("您的请假申请未被批准，原因：").$REASON;
}
else
   $SMS_CONTENT=_("您的销假申请已被批准，已销假！");

if(find_id($SMS_REMIND1,6))
{
   $REMIND_URL="attendance/personal/leave";
   send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID,6,$SMS_CONTENT,$REMIND_URL);
}

if($MOBILE_FLAG=="1")
{
   if($CONFIRM==1)
      $SMS_CONTENT=_("您的请假申请已被批准！");
   else
      $SMS_CONTENT=_("您的请假申请未被批准！");
      
   if(find_id($SMS2_REMIND1,6))      
      send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$USER_ID,$SMS_CONTENT,6);
}

if($CONFIRM==2)
{
	 Message(_("提示"),_("操作成功"));
?>
<br>
<script>
   if(opener.location.href.indexOf("connstatus") < 0 ){
		opener.location.href = opener.location.href+"?connstatus=1";
	}else{
		opener.location.reload();
	}
</script>
<center>
	<input type="button" class="BigButton" value="<?=_("关闭")?>" onClick="window.close();" title="<?=_("关闭窗口")?>">
</center>
<?
}
else
   header("location: index.php?connstatus=1");
?>

</body>
</html>
