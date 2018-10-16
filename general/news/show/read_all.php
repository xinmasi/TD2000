<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");

$query = "update NEWS set READERS=concat(READERS,'".$_SESSION["LOGIN_USER_ID"].",'),CLICK_COUNT=CLICK_COUNT+1 where not find_in_set('".$_SESSION["LOGIN_USER_ID"]."',READERS) and PUBLISH='1' and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)".dept_other_sql("TO_ID")." or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)".priv_other_sql("PRIV_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID))";
exequery(TD::conn(),$query);

header("location:news.php");
?>
