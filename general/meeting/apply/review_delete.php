<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$query="delete from MEETING_COMMENT where COMMENT_ID='$COMMENT_ID'";
exequery(TD::conn(),$query);
header("location: review.php?M_ID=$M_ID");
?>
