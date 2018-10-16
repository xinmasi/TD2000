<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$CUR_DATE=date("Y-m-d",time());

$query="delete from ATTENDANCE_OVERTIME where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and RECORD_TIME='$RECORD_TIME'";
exequery(TD::conn(),$query);

header("location: index.php?connstatus=1");
?>
