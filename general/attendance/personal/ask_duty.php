<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
$CUR_TIME=date("Y-m-d H:i:s", time());

$query="UPDATE ATTEND_ASK_DUTY SET EXPLANATION='$EXPLANATION',RECORD_TIME='$CUR_TIME' where ASK_DUTY_ID='$ASK_DUTY_ID'";
exequery(TD::conn(),$query);

header("location: index.php?connstatus=1");
?>