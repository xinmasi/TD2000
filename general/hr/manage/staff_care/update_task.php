<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("�½��ػ�����");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
if($SMS_REMIND=="on")
   send_sms("",$_SESSION["LOGIN_USER_ID"],$REMINDER,0,$CONTENT);
if($SMS2_REMIND=="on")
   send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$REMINDER,$CONTENT,0);

Message(_("��ʾ"),_("�����ɹ�"));
?>
<center>
  <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='care_remind.php?connstatus=1'">
</center>
</body>
</html>
