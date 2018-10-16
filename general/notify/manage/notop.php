<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_cache.php");

include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
$POST_PRIV=GetUserInfoByUID($_SESSION["LOGIN_UID"],"POST_PRIV");
if($DELETE_STR=="")
   $DELETE_STR=0;
elseif(substr($DELETE_STR,-1,1)==",")
   $DELETE_STR=substr($DELETE_STR,0,-1);

$query="update NOTIFY set TOP='0' where NOTIFY_ID in ($DELETE_STR)";
if($_SESSION["LOGIN_USER_PRIV"]!="1"&&$POST_PRIV!="1")
   $query.=" and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
exequery(TD::conn(),$query);

if($SEARCH==1)
{
   header("location: search.php?start=$start&SEARCH=1&SEND_TIME_MIN=$SEND_TIME_MIN&SEND_TIME_MAX=$SEND_TIME_MAX&SUBJECT=$SUBJECT&CONTENT=$CONTENT&FORMAT=$FORMAT&TYPE_ID=$TYPE_ID&PUBLISH=$PUBLISH&TOP=$TOP&TO_ID=$TO_ID&STAT=$STAT&IS_MAIN=1");
}
else
   header("location: index1.php?start=$start&IS_MAIN=1");
?>
</body>
</html>
