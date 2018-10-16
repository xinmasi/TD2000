<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
$query = "SELECT POST_PRIV FROM USER where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $POST_PRIV=$ROW["POST_PRIV"];
}
if($DELETE_STR=="")
   $DELETE_STR=0;
elseif(substr($DELETE_STR,-1,1)==",")
   $DELETE_STR=substr($DELETE_STR,0,-1);

$query="update VOTE_TITLE set TOP='0' where VOTE_ID in ($DELETE_STR)";
if($_SESSION["LOGIN_USER_PRIV"]!="1"&&$POST_PRIV!="1")
   $query.=" and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
exequery(TD::conn(),$query);
header("location: index1.php?start=$start&IS_MAIN=1");
?>
</body>
</html>
