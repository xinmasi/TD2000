<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$POST_PRIV = '0';
$query = "SELECT POST_PRIV FROM USER WHERE UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $POST_PRIV=$ROW["POST_PRIV"];
}

$sql = "SELECT PROVIDER FROM news WHERE NEWS_ID='$NEWS_ID'";
$cursor= exequery(TD::conn(),$sql);
if($arr=mysql_fetch_array($cursor))
{
    $PROVIDER=$arr["PROVIDER"];
}

$sql1 = "SELECT * FROM news_comment WHERE PARENT_ID = '$COMMENT_ID'";
$cursor= exequery(TD::conn(),$sql1);
if(mysql_affected_rows()>0)
{
    if($_SESSION["LOGIN_USER_PRIV"] == 1 || $PROVIDER==$_SESSION['LOGIN_USER_ID'])
    {
        $query="delete from NEWS_COMMENT where NEWS_ID='$NEWS_ID' and PARENT_ID='$COMMENT_ID'";
        exequery(TD::conn(),$query);
        
        $query = "delete from NEWS_COMMENT where NEWS_ID='$NEWS_ID' and COMMENT_ID='$COMMENT_ID'";
        $cursor = exequery(TD::conn(),$query);
    }
    
}
else
{
    $query = "delete from NEWS_COMMENT where NEWS_ID='$NEWS_ID' and COMMENT_ID='$COMMENT_ID'";
    $cursor = exequery(TD::conn(),$query);
}

header("location: re_news.php?NEWS_ID=$NEWS_ID&start=$start&MANAGE=1&IS_MAIN=1");
?>
