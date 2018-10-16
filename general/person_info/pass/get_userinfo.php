<?
include_once("inc/auth.inc.php");
include_once("inc/utility_cache.php");
ob_end_clean();
$query="update USER set KEY_SN='$KEY_SN' where UID='".$_SESSION["LOGIN_UID"]."'";
exequery(TD::conn(),$query);

updateUserCache($_SESSION["LOGIN_UID"]);

$query = "SELECT PASSWORD from USER where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $PASSWORD=MD5($ROW["PASSWORD"]);

echo $_SESSION["LOGIN_USER_ID"].",".$PASSWORD;
?>
