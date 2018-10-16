<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
if($CYCID!="")
{
	$query = "delete from OA_CYCLESOURCE_USED where CYCID ='$CYCID'";
	exequery(TD::conn(),$query);
}
if($DELETE_STR != "")
{
	$DELETE_STR=substr($DELETE_STR,0,-1);
	$query="delete from OA_CYCLESOURCE_USED where CYCID in ($DELETE_STR)";
	exequery(TD::conn(),$query);
}
Header("location:index.php?start=$start");
?>
