<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$query="delete from COUNTDOWN where ROW_ID='$ROW_ID'";
exequery(TD::conn(),$query);

header("location: index.php?IS_MAIN=1");
?>