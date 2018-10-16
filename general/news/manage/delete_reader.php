<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$query="update NEWS set READERS='' where NEWS_ID='$NEWS_ID'";
if($_SESSION["LOGIN_USER_PRIV"]!="1")
   $query.=" and PROVIDER='".$_SESSION["LOGIN_USER_ID"]."'";
exequery(TD::conn(),$query);

header("location: show_reader.php?NEWS_ID=$NEWS_ID&IS_MAIN=1");
?>
