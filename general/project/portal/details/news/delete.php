<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("../../../proj/proj_priv.php");

$i_proj_id=$_GET['PROJ_ID'];
$news_id = $_GET["NEWS_ID"];
$login_uid = $_SESSION['LOGIN_UID'];

$query="delete from proj_news where id='$news_id' and uid='$login_uid'";
exequery(TD::conn(),$query);

header("location: index.php?PROJ_ID=$i_proj_id");
?>