<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("���°�Ǽ�");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$SXB = $_GET["SXB"];
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());

$query="insert into ATTEND_DUTY_SHIFT(USER_ID,REGISTER_TYPE,REGISTER_TIME,REGISTER_IP,SXB) values ('".$_SESSION["LOGIN_USER_ID"]."','$REGISTER_TYPE','$CUR_TIME','".get_client_ip()."','$SXB')";
exequery(TD::conn(),$query);

Message("",_("�Ǽǳɹ���"));
Button_Back();
?>

</body>
</html>

