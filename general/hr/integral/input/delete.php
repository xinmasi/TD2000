<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");
if(substr($ID,-1)==",")
   $ID = substr($ID,0,-1);

$query="delete from HR_INTEGRAL_DATA where ID in ($ID)";
exequery(TD::conn(),$query);
header("location: manage.php?PAGE_START=$PAGE_START&connstatus=1&INTEGRAL_TYPE1=".$INTEGRAL_TYPE1);
?>
