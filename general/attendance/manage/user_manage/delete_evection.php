<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

if($_SESSION["LOGIN_USER_PRIV"]==1)
{
  $EVECTION_ID=intval($EVECTION_ID);
  $query="delete from ATTEND_EVECTION where EVECTION_ID='$EVECTION_ID'";
  exequery(TD::conn(),$query);
}

header("location: search.php?DATE1=$DATE1&DATE2=$DATE2&USER_ID=$USER_ID");
?>