<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$query="delete from ATTEND_HOLIDAY where HOLIDAY_ID='$HOLIDAY_ID'";
exequery(TD::conn(),$query);

header("location: index.php?connstatus=1");
?>
