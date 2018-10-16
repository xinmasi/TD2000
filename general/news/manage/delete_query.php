<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
if($DELETE_STR=="")
   $DELETE_STR=0;
else
   $DELETE_STR=substr($DELETE_STR,0,-1);

if($FROM_FLAG==1)
   $DELETE_STR=$NEWS_ID;

$query="select NEWS_ID,ATTACHMENT_ID,ATTACHMENT_NAME from NEWS where NEWS_ID in ($DELETE_STR)";
if($_SESSION["LOGIN_USER_PRIV_TYPE"]!="1")
   $query.=" and PROVIDER='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
$DELETE_STR="";
while($ROW=mysql_fetch_array($cursor))
{
   
  $NEWS_ID=$ROW["NEWS_ID"];
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

  $DELETE_STR.=$NEWS_ID.",";
  if($ATTACHMENT_NAME!="")
     delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}
$DELETE_STR=substr($DELETE_STR,0,-1);
if($DELETE_STR != ""){
    $query="delete from NEWS_COMMENT where NEWS_ID in ($DELETE_STR)";
    exequery(TD::conn(),$query);

    $query="delete from NEWS where NEWS_ID in ($DELETE_STR)";
    exequery(TD::conn(),$query);
}
 header("location: search.php");
 ?>
</body>
</html>
