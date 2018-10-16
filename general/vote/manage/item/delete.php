<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

if($ITEM_ID=="")
   exit;
$VOTE_ID=intval($VOTE_ID);
$query="select VOTE_ID from VOTE_TITLE where VOTE_ID='$VOTE_ID'";
if($_SESSION["LOGIN_USER_PRIV"]!="1")
   $query.=" and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor=exequery(TD::conn(),$query);
if(!$ROW=mysql_fetch_array($cursor))
{
   Message(_("½ûÖ¹"),_("ÄúÎÞÈ¨·ÃÎÊ"));
   exit;
}

$query = "delete from VOTE_ITEM where ITEM_ID='$ITEM_ID' and  VOTE_ID='$VOTE_ID'";
$cursor=exequery(TD::conn(),$query);
if(mysql_affected_rows($cursor) > 0)
{
   $query = "delete from VOTE_DATA where ITEM_ID='$ITEM_ID'";
   exequery(TD::conn(),$query);
}

$query_title   = "SELECT * FROM vote_title WHERE parent_id='$VOTE_ID'";
$result_titele = exequery(TD::conn(),$query_title);

$query_item    = "SELECT * FROM vote_item WHERE vote_id='$VOTE_ID'";
$result_item   = exequery(TD::conn(),$query_item);

if(mysql_num_rows($result_item)==0 && mysql_num_rows($result_titele)==0)
{
	$query="UPDATE vote_title SET publish=0 WHERE vote_id='$VOTE_ID'";
	exequery(TD::conn(),$query);
}
header("location: index.php?VOTE_ID=$VOTE_ID&IS_MAIN=1");
?>