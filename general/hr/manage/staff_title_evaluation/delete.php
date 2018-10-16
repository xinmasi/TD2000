<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

if(substr($EVALUATION_ID,-1)==",")
   $EVALUATION_ID = substr($EVALUATION_ID,0,-1);

$query="delete from  HR_STAFF_TITLE_EVALUATION where EVALUATION_ID in ($EVALUATION_ID)";
exequery(TD::conn(),$query);

header("location: index1.php?PAGE_START=$PAGE_START&connstatus=1");
?>
