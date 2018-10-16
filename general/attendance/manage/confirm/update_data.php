<?
include_once("inc/auth.inc.php");
ob_end_clean();

$query="update ATTEND_LEAVE set STATUS='2',LEAVE_DATE2='$MY_SET_TIME' where LEAVE_ID='$LEAVE_ID'";
exequery(TD::conn(),$query);
echo "+OK";
?>