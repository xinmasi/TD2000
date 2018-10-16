<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
ob_start();

$query="delete from PICTURE where PIC_ID='$PIC_ID'";
exequery(TD::conn(),$query);

header("location: index.php?IS_MAIN=1");
?>
