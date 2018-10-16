<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$query="delete from CP_ASSET_TYPE";
exequery(TD::conn(),$query);

header("location: index.php");
?>
