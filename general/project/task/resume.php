<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$CUR_DATE=date("Y-m-d",time());

$query = "update PROJ_TASK set TASK_STATUS = '0' WHERE TASK_ID='$TASK_ID'";
exequery(TD::conn(),$query);

//header("location:task_finished.php");
?>