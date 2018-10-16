<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$query="delete from ATTEND_MANAGER where MANAGER_ID='$MANAGER_ID'";
exequery(TD::conn(),$query);

header("location: index.php?connstatus=1");
?>
