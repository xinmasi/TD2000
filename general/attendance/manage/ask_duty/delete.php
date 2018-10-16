<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

if(substr($ASK_DUTY_ID,-1)==",")
   $ASK_DUTY_ID = substr($ASK_DUTY_ID,0,-1);

$query="delete from  ATTEND_ASK_DUTY where ASK_DUTY_ID in ($ASK_DUTY_ID)";
exequery(TD::conn(),$query);

header("location: index1.php?PAGE_START=$PAGE_START&connstatus=1");
?>
