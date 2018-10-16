<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$query = "delete from $ATTEND_TYPE where $ATTEND_ID = '$DELETE_ID'";
exequery(TD::conn(),$query);
header("location: index.php?connstatus=1");
?>