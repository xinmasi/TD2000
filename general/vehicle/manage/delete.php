<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");
if($delete_all){
	$V_ID = substr($V_ID, 0, -1);
}
$query = "SELECT * from VEHICLE where V_ID in ($V_ID)";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

   if($ATTACHMENT_NAME!="")
      delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}

$query="delete from VEHICLE_USAGE where V_ID in ($V_ID)";
exequery(TD::conn(),$query);

$query="delete from VEHICLE_MAINTENANCE where V_ID in ($V_ID)";
exequery(TD::conn(),$query);

$query="delete from VEHICLE where V_ID in ($V_ID)";
exequery(TD::conn(),$query);

header("location: manage.php");
?>
