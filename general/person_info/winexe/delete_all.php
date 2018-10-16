<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$query="delete from WINEXE where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
exequery(TD::conn(),$query);

header("location: index.php?IS_MAIN=1");
?>
