<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");

$DUTY_MACHINE=$KAOQIN;

$PARA_ARRAY=array("DUTY_MACHINE" => "$DUTY_MACHINE");
set_sys_para($PARA_ARRAY);

header("location: ../");
?>
