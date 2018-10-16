<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
function deptchange($DEPT_NAME)
{
	   $query="select DEPT_ID from DEPARTMENT where DEPT_NAME='$DEPT_NAME' ";
   $cursor=exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
		$DEPT_ID=$ROW["DEPT_ID"];
	return $DEPT_ID;
}
function typechange($TYPE_NAME)
{
	   $query="select TYPE_ID from CP_ASSET_TYPE where TYPE_NAME='$TYPE_NAME' ";
   $cursor=exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
		$TYPE_ID=$ROW["TYPE_ID"];
	return $TYPE_ID;
}
function prcschange($PRCS_LONG_DESC)
{
		$query = "SELECT * from CP_PRCS_PROP  where left(PRCS_CLASS,1)='A' and PRCS_LONG_DESC like '%".$PRCS_LONG_DESC."%'";
	$cursor= exequery(TD::conn(),$query);
 	if($ROW=mysql_fetch_array($cursor))
		$PRCS_ID=$ROW["PRCS_ID"];
   return $PRCS_ID;
} 
?>