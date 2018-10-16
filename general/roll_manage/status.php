<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("ÐÞ¸Ä°¸¾í");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$CUR_TIME=date("Y-m-d H:i:s",time());

$query="select STATUS from RMS_ROLL WHERE ROLL_ID='$ROLL_ID'";
$cursor= exequery(TD::conn(),$query);
$ROW=mysql_fetch_array($cursor);
$STATUS=!$ROW["STATUS"];

$query="update RMS_ROLL set STATUS='$STATUS',MOD_USER='".$_SESSION["LOGIN_USER_ID"]."',MOD_TIME='$CUR_TIME' WHERE ROLL_ID='$ROLL_ID'";
exequery(TD::conn(),$query);
header("location: index1.php?CUR_PAGE=$CUR_PAGE&connstatus=1");
?>

</body>
</html>
