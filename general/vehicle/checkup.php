<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

</body>
</html>
<?
$query="select * from VEHICLE_USAGE where VU_ID='$VU_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $V_ID=$ROW["V_ID"];
   $VU_STATUS1=$ROW["VU_STATUS"];
}
$query="update VEHICLE_USAGE set VU_STATUS='$VU_STATUS' where VU_ID='$VU_ID'";
exequery(TD::conn(),$query);

$query="update VEHICLE set USEING_FLAG='0' where V_ID='$V_ID'";
exequery(TD::conn(),$query);

header("location: query.php?VU_STATUS=$VU_STATUS1");
?>