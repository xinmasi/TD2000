<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("�Ӱ�ȷ��");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());

//----------- �Ϸ���У�� ---------
if($START_TIME!="")
{
  $TIME_OK=is_date_time($START_TIME);

  if(!$TIME_OK)
  { 
  	Message(_("����"),_("�Ӱ࿪ʼʱ�������⣬���ʵ"));
    Button_Back();
    exit;
  }
}

if($END_TIME!="")
{
  $TIME_OK=is_date_time($END_TIME);

  if(!$TIME_OK)
  { 
  	Message(_("����"),_("�Ӱ����ʱ�������⣬���ʵ"));
    Button_Back();
    exit;
  }
}

if(compare_date_time($START_TIME,$END_TIME)>=0)
{ 
	 Message(_("����"),_("�Ӱ����ʱ��Ӧ���ڼӰ࿪ʼʱ��"));
   Button_Back();
   exit;
}

$query="update ATTENDANCE_OVERTIME set ALLOW='3',CONFIRM_TIME='$CUR_TIME' where OVERTIME_ID='$OVERTIME_ID'";
exequery(TD::conn(),$query);

$SMS_CONTENT=$_SESSION["LOGIN_USER__NAME"]._("�ύ�Ӱ�ȷ�ϣ���鿴��");
$REMIND_URL="attendance/manage/confirm";
if($SMS_REMIND=="on")
   send_sms("",$_SESSION["LOGIN_USER_ID"],$APPROVE_ID,6,$SMS_CONTENT,$REMIND_URL);
Message(_("��ʾ"),_("�����ɹ�"));
?>
<center>
	<input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="javascript:window.close();">
</center>	
<?
header("location: index.php?connstatus=1");
?>

</body>
</html>
