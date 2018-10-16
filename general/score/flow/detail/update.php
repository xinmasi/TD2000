<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
if($ITEM_ID=="")
   exit;
    $ITEM_ID=intval($ITEM_ID);
$query = "update SCORE_ITEM set ITEM_NAME='$ITEM_NAME',MAX='$MAX',MIN='$MIN' where ITEM_ID='$ITEM_ID'";
exequery(TD::conn(),$query);
header("location: index.php?GROUP_ID=".$GROUP_ID."& CUR_PAGE=".$CUR_PAGE);
?>