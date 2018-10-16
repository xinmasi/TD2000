<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");

$PARA_ARRAY=array("OUT_REQUIREMENT" => "$OUT_REQUIREMENT");
set_sys_para($PARA_ARRAY);
header("location: ../#inputOut");
?>
