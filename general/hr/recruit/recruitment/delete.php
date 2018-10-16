<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");
if(substr($RECRUITMENT_ID,-1)==",")
   $RECRUITMENT_ID = substr($RECRUITMENT_ID,0,-1);



$query="delete from HR_RECRUIT_RECRUITMENT where RECRUITMENT_ID in ($RECRUITMENT_ID)";
exequery(TD::conn(),$query);

header("location: index1.php?connstatus=1");
?>
