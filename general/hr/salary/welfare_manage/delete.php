<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

if(substr($WELFARE_ID,-1)==",")
   $WELFARE_ID = substr($WELFARE_ID,0,-1);


$query="delete from  HR_WELFARE_MANAGE where WELFARE_ID in ($WELFARE_ID)";
exequery(TD::conn(),$query);

header("location: index1.php?PAGE_START=$PAGE_START&connstatus=1");
?>

