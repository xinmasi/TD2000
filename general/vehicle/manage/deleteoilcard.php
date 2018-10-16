<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");

if($delete_all)
{
	$ID = substr($ID, 0, -1);
}

$query="delete from VEHICLE_OIL_CARD where ID in ($ID)";
exequery(TD::conn(),$query);

header("location: oilcard.php");
?>
