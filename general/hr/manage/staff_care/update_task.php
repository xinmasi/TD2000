<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("新建关怀提醒");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
if($SMS_REMIND=="on")
   send_sms("",$_SESSION["LOGIN_USER_ID"],$REMINDER,0,$CONTENT);
if($SMS2_REMIND=="on")
   send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$REMINDER,$CONTENT,0);

Message(_("提示"),_("操作成功"));
?>
<center>
  <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='care_remind.php?connstatus=1'">
</center>
</body>
</html>
