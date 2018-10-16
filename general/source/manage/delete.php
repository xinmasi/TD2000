<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
if($SOURCEID!="")
{
	if(!$EMPTY)
	{
	   $query = "delete from OA_SOURCE where SOURCEID = '$SOURCEID'";
	   exequery(TD::conn(),$query);
	}
	
		$query = "delete from OA_SOURCE_USED where SOURCEID = '$SOURCEID'";
		exequery(TD::conn(),$query);
		
}
if($DELETE_STR != "")
{
	$DELETE_STR=substr($DELETE_STR,0,-1);
	$query="delete from OA_SOURCE where SOURCEID in ($DELETE_STR)";
	exequery(TD::conn(),$query);
}
Header("location:index.php?start=$start");
?>
