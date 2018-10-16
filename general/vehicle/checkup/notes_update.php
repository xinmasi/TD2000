<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_all.php");
$query="update VEHICLE_USAGE set VU_PARKING_FEES='$VU_PARKING_FEES',VU_MILEAGE_TRUE='$VU_MILEAGE_TRUE' where VU_ID='$VU_ID'";
exequery(TD::conn(),$query);
echo '<script>window.close();</script>';
?>