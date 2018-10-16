<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$query="delete from CP_ASSETCFG";
exequery(TD::conn(),$query);

Header("location: index.php");
?>
