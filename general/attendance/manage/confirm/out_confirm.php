<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("外出登记批示");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$CUR_DATE=date("Y-m-d H:i:s",time());

if($CONFIRM==1)
{
   $query="select * from ATTEND_OUT where OUT_ID='$OUT_ID' and ALLOW='0'";
   $cursor=exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
      $SUBMIT_TIME=$ROW["SUBMIT_TIME"];	
      $OUT_TIME1=$ROW["OUT_TIME1"];
      $OUT_TIME2=$ROW["OUT_TIME2"];
      $OUT_TYPE=$ROW["OUT_TYPE"];
      $USER_ID1=$ROW["USER_ID"];
      
      $OUT_DAY = substr($SUBMIT_TIME,0,10);
      $CAL_TIME = $OUT_DAY." ".$OUT_TIME1;
      $END_TIME = $OUT_DAY." ".$OUT_TIME2;
      $URL = '/general/attendance/personal/out/';  

      $query="insert into CALENDAR(USER_ID,CAL_TIME,END_TIME,CAL_TYPE,CAL_LEVEL,CONTENT,OVER_STATUS,FROM_MODULE,URL) values ('$USER_ID1','".strtotime($CAL_TIME)."','".strtotime($END_TIME)."','1','','$OUT_TYPE','0','1','$URL')";
      exequery(TD::conn(),$query);        
   }
}

if($CONFIRM==2)
   $query="update ATTEND_OUT set ALLOW='$CONFIRM',REASON='$REASON',HANDLE_TIME='$CUR_DATE' where OUT_ID='$OUT_ID'";
else
   $query="update ATTEND_OUT set ALLOW='$CONFIRM',HANDLE_TIME='$CUR_DATE' where OUT_ID='$OUT_ID'";   
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

if($CONFIRM==1)
   $SMS_CONTENT=_("您的外出申请已被批准！");
else
   $SMS_CONTENT=_("您的外出申请未被批准！");
$REMIND_URL="attendance/personal/out";
if(find_id($SMS_REMIND1,6))
   send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID,6,$SMS_CONTENT,$REMIND_URL);

if($MOBILE_FLAG=="1")
{
  if($CONFIRM==1)
   $SMS_CONTENT=_("您的外出申请已被批准！");
  else
   $SMS_CONTENT=_("您的外出申请未被批准！");
   
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
