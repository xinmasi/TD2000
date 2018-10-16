<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_cache.php");
include_once("../manage/sql_inc.php");
$query="update NOTIFY set READERS='' where NOTIFY_ID='$NOTIFY_ID'";
exequery(TD::conn(),$query);
delete_reader($NOTIFY_ID);
header("location: show_reader.php?NOTIFY_ID=$NOTIFY_ID&IS_MAIN=1");
?>
