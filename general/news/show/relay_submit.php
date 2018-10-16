<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$CUR_TIME=date("Y-m-d H:i:s",time());

if($AUTHOR_NAME=="USER_ID")
   $NICK_NAME="";
$query="insert into NEWS_COMMENT(NEWS_ID,PARENT_ID,USER_ID,NICK_NAME,CONTENT,RE_TIME) values ('$NEWS_ID','$PARENT_ID','".$_SESSION["LOGIN_USER_ID"]."','$NICK_NAME','$CONTENT','$CUR_TIME')";
exequery(TD::conn(),$query);

header("location: re_news.php?NEWS_ID=$NEWS_ID&MANAGE=$MANAGE&IS_MAIN=1");
?>