<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
$AFF_ID=td_trim($AFF_ID);
$query="delete from AFFAIR where AFF_ID in ($AFF_ID)";
exequery(TD::conn(),$query);
if($SEARCH==1)
	header("location: search.php?PAGE_START=$PAGE_START&SEND_TIME_MIN=$SEND_TIME_MIN&SEND_TIME_MAX=$SEND_TIME_MAX&TYPE=$TYPE&CONTENT=$CONTENT&IS_MAIN=1");
else
	header("location: index.php?PAGE_START=$PAGE_START&IS_MAIN=1");
?>
