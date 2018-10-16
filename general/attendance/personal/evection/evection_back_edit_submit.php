<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("修改出差记录");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
//----------- 合法性校验 ---------
if($EVECTION_DATE1!="")
{
  $TIME_OK=is_date($EVECTION_DATE1);

  if(!$TIME_OK)
  { Message(_("错误"),_("出差开始日期格式不对，应形如 1999-1-2"));
    Button_Back();
    exit;
  }
}

if($EVECTION_DATE2!="")
{
  $TIME_OK=is_date($EVECTION_DATE2);

  if(!$TIME_OK)
  { Message(_("错误"),_("出差结束日期格式不对，应形如 1999-1-2"));
    Button_Back();
    exit;
  }
}

if(compare_date($EVECTION_DATE1,$EVECTION_DATE2)==1)
{ Message(_("错误"),_("出差开始日期不能晚于出差结束日期"));
  Button_Back();
  exit;
}  

$sql="update ATTEND_EVECTION set EVECTION_DATE2='$CUR_DATE',STATUS='2' where EVECTION_ID='$EVECTION_ID'";
exequery(TD::conn(),$sql);


$query="update ATTEND_EVECTION set REASON='$REASON',EVECTION_DEST='$EVECTION_DEST',EVECTION_DATE1='$EVECTION_DATE1',EVECTION_DATE2='$EVECTION_DATE2' where EVECTION_ID='$EVECTION_ID'";
$cursor = exequery(TD::conn(),$query);

$SMS_CONTENT=$_SESSION["LOGIN_USER__NAME"]._("出差归来，请查看！");
$REMIND_URL="1:attendance/manage/records/evection_edit.php?EVECTION_ID=".$EVECTION_ID;
if($SMS_REMIND=="on")
   send_sms("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,6,$SMS_CONTENT,$REMIND_URL,$EVECTION_ID);
if($cursor != false) {	
?>
<script>
	window.close();
	if(window.opener.location.href.indexOf("connstatus") < 0 ){
		window.opener.location.href = window.opener.location.href+"?connstatus=1";
	}else{
		window.opener.location.reload();
	}
</script>	
<?
}
?>	
</body>
</html>
