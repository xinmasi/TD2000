<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("修改保存");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
if($CREATE_TIME=="")
	$CREATE_TIME=$CUR_TIME;

$query="UPDATE HR_INTEGRAL_DATA SET INTEGRAL_REASON='$INTEGRAL_REASON',USER_ID='$USER_ID',INTEGRAL_DATA='$INTEGRAL_DATA',CREATE_PERSON='$CREATE_PERSON',CREATE_TIME='$CREATE_TIME',INTEGRAL_TIME='$INTEGRAL_TIME' WHERE ID = '$ID'";
exequery(TD::conn(),$query);

//------- 事务提醒 --------
$SMS_CONTENT=_("您的积分变动，请查看。");
$url="1:hr/self_find/integral/detail.php?ID=$ID&from=email";
if($SMS_REMIND=="on")
   send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID,78,$SMS_CONTENT,$url,$ID);

if($SMS2_REMIND=="on")
	 send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$USER_ID,$SMS_CONTENT,78);

header("location:manage.php?connstatus=1&INTEGRAL_TYPE1=".$INTEGRAL_TYPE1);
?>
</body>
</html>
