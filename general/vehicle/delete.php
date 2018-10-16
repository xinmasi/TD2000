<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
$query="delete from VEHICLE_USAGE where VU_ID='$VU_ID'";
exequery(TD::conn(),$query);

header("location: query.php?DMER_STATUS=$DMER_STATUS&VU_STATUS=$VU_STATUS");
?>