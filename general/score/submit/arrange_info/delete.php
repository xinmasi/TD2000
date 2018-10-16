<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$COMMENT_ID=intval($COMMENT_ID);
 $query = "delete from DIARY_COMMENT where COMMENT_ID='$COMMENT_ID' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
 exequery(TD::conn(),$query);
 
 header("location: read.php?DIA_ID=$DIA_ID&USER_NAME=$USER_NAME&connstatus=1");
?>
