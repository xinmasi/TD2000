<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_all.php");

$query="select V_ID from VEHICLE_USAGE where VU_ID='$VU_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $V_ID=$ROW["V_ID"];
}

$VU_FINAL_END = date("Y-m-d H:i:s",time());

$query="update VEHICLE_USAGE set VU_STATUS='4',	VU_FINAL_END='$VU_FINAL_END',VU_PARKING_FEES='$VU_PARKING_FEES',VU_MILEAGE_TRUE='$VU_MILEAGE_TRUE' where VU_ID='$VU_ID'";
exequery(TD::conn(),$query);

$query="update VEHICLE set USEING_FLAG='0' where V_ID='$V_ID'";
exequery(TD::conn(),$query);

echo '<script>window.parent.opener.location.reload();window.close();</script>';
?>