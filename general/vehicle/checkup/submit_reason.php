<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="update VEHICLE_USAGE  set OPERATOR_REASON='$OPERATOR_REASON' where VU_ID='$VU_ID'";
exequery(TD::conn(),$query);

header("location: checkup.php?VU_ID=$VU_ID&VU_STATUS=3");
?>
</body>
</html>