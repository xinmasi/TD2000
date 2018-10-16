<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$query="delete from attend_leave_param";
exequery(TD::conn(),$query);

header("location: index.php?connstatus=1");
?>
