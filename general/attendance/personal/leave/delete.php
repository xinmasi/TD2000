<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$LEAVE_ID=intval($LEAVE_ID);
$query="delete from ATTEND_LEAVE where LEAVE_ID='$LEAVE_ID'";
exequery(TD::conn(),$query);

header("location: index.php?connstatus=1");
?>
