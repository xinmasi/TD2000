<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$query="insert into CP_ASSETCFG values('$DPCT_SORT','$BAL_SORT')";
exequery(TD::conn(),$query);

Header("location: index.php");
?>
