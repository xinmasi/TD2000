<?
include_once("inc/auth.inc.php");
include_once("inc/utility_cache.php");
ob_end_clean();

$query = "update USER SET USING_FINGER='$_GET[USING_FINGER]' where UID='".$_SESSION["LOGIN_UID"]."'";
exequery(TD::conn(),$query);

updateUserCache($_SESSION["LOGIN_UID"]);
?>