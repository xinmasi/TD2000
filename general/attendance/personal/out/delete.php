<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$CUR_DATE=date("Y-m-d",time());

$query="delete from ATTEND_OUT where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and OUT_ID='$OUT_ID' and STATUS='0'";
exequery(TD::conn(),$query);

header("location: index.php?connstatus=1");
?>
