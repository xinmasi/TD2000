<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");

$NO_DUTY_USER=$COPY_TO_ID;

$PARA_ARRAY=array("NO_DUTY_USER" => "$NO_DUTY_USER");
set_sys_para($PARA_ARRAY);

header("location: ../");
?>
