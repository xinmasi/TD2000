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

$query="select ATTACHMENT_ID,ATTACHMENT_NAME from MEETING where M_ID in ($DELETE_STR)";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

   if($ATTACHMENT_ID!="")
      delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}

$query="delete from MEETING where M_ID in ($DELETE_STR)";
exequery(TD::conn(),$query);

$query="delete from MEETING_COMMENT where M_ID in ($DELETE_STR)";
exequery(TD::conn(),$query);

   
header("location:search.php");
?>
?>

</body>
</html>
