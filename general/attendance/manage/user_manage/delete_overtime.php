<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

if($_SESSION["LOGIN_USER_PRIV"]==1)
{
  $query="delete from ATTENDANCE_OVERTIME where RECORD_TIME='$RECORD_TIME' and USER_ID='$USER_ID'";
  exequery(TD::conn(),$query);
}

header("location: search.php?DATE1=$DATE1&DATE2=$DATE2&USER_ID=$USER_ID&connstatus=1");
?>
