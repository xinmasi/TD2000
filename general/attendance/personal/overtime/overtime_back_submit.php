<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("加班确认");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());

//----------- 合法性校验 ---------
if($START_TIME!="")
{
  $TIME_OK=is_date_time($START_TIME);

  if(!$TIME_OK)
  { 
  	Message(_("错误"),_("加班开始时间有问题，请核实"));
    Button_Back();
    exit;
  }
}

if($END_TIME!="")
{
  $TIME_OK=is_date_time($END_TIME);

  if(!$TIME_OK)
  { 
  	Message(_("错误"),_("加班结束时间有问题，请核实"));
    Button_Back();
    exit;
  }
}

if(compare_date_time($START_TIME,$END_TIME)>=0)
{ 
	 Message(_("错误"),_("加班结束时间应晚于加班开始时间"));
   Button_Back();
   exit;
}

$query="update ATTENDANCE_OVERTIME set ALLOW='3',CONFIRM_TIME='$CUR_TIME' where OVERTIME_ID='$OVERTIME_ID'";
exequery(TD::conn(),$query);

$SMS_CONTENT=$_SESSION["LOGIN_USER__NAME"]._("提交加班确认，请查看！");
$REMIND_URL="attendance/manage/confirm";
if($SMS_REMIND=="on")
   send_sms("",$_SESSION["LOGIN_USER_ID"],$APPROVE_ID,6,$SMS_CONTENT,$REMIND_URL);
Message(_("提示"),_("操作成功"));
?>
<center>
	<input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="javascript:window.close();">
</center>	
<?
header("location: index.php?connstatus=1");
?>

</body>
</html>
