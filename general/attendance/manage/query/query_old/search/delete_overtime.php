<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

if($_SESSION["LOGIN_USER_PRIV"]==1)
{
  $query="delete from ATTENDANCE_OVERTIME where OVERTIME_ID='$OVERTIME_ID'";
  exequery(TD::conn(),$query);
}

header("location: overtime.php?DEPARTMENT1=$DEPARTMENT1&DATE1=$DATE1&DATE2=$DATE2&DUTY_TYPE=<?=$DUTY_TYPE?>");
?>
