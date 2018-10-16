<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$query="delete from URL where USER='".$_SESSION["LOGIN_USER_ID"]."' and URL_TYPE!='2'";
exequery(TD::conn(),$query);

header("location: index.php?IS_MAIN=1");
?>
