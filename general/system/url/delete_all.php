<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$query="delete from URL where USER=''";
exequery(TD::conn(),$query);

header("location: index.php?IS_MAIN=1");
?>
