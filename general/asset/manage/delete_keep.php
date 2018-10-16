<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
if(isset($KEEP_ID))
{
   $query="delete from CP_ASSET_KEEP where KEEP_ID='$KEEP_ID'";
	 exequery(TD::conn(),$query);
	 header("location:keep.php?CPTL_ID=$CPTL_ID");		
}

?>