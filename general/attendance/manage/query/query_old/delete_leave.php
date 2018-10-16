<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

if($_SESSION["LOGIN_USER_PRIV"]==1)
{
  $LEAVE_ID=intval($LEAVE_ID);
  $query="delete from ATTEND_LEAVE where leave_id='$LEAVE_ID'";
  exequery(TD::conn(),$query);
}

header("location: search.php?DEPARTMENT1=$DEPARTMENT1&DUTY_TYPE=$DUTY_TYPE&DATE1=$DATE1&DATE2=$DATE2&connstatus=1");
?>
