<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$query = "insert into SCORE_ITEM (ITEM_NAME,GROUP_ID,MAX,MIN,ITEM_EXPLAIN) values ('$ITEM_NAME','$GROUP_ID','$MAX','$MIN','$ITEM_EXPLAIN')";
exequery(TD::conn(),$query);
header("location: index.php?GROUP_ID=".$GROUP_ID."& CUR_PAGE=".$CUR_PAGE."&connstatus=1");
?>