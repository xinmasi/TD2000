<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$query = "insert into SCORE_ITEM (ITEM_NAME,GROUP_ID,MAX,MIN) values ('$ITEM_NAME','$GROUP_ID','$MAX','$MIN')";
exequery(TD::conn(),$query);
header("location: index.php?GROUP_ID=".$GROUP_ID."& CUR_PAGE=".$CUR_PAGE);
?>