<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");
if(substr($RECORD_ID,-1)==",")
   $RECORD_ID = substr($RECORD_ID,0,-1);



$query="delete from HR_TRAINING_RECORD where RECORD_ID in ($RECORD_ID)";
exequery(TD::conn(),$query);

header("location: index1.php");
?>
