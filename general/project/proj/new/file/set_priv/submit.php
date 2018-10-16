<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$query="update PROJ_FILE_SORT set NEW_USER='$new_user',VIEW_USER ='$view_user',MANAGE_USER='$manage_user',MODIFY_USER='$modify_user' where SORT_ID='$SORT_ID'";
exequery(TD::conn(), $query);

header("location:../index.php?PROJ_ID=$PROJ_ID");
?>
