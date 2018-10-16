<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$query1 = "delete from calendar where CAL_ID='$CAL_ID'";
exequery(TD::conn(),$query1);
$query = "delete from PROJ_TASK where TASK_ID='$TASK_ID'";
exequery(TD::conn(),$query);

header("location:index.php?PROJ_ID=$PROJ_ID");
?>