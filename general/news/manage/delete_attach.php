<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="select ATTACHMENT_ID,ATTACHMENT_NAME from NEWS where NEWS_ID='$NEWS_ID'";
if($_SESSION["LOGIN_USER_PRIV"]!="1")
   $query.=" and PROVIDER='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor=exequery(TD::conn(),$query);

if($ROW=mysql_fetch_array($cursor))
{
  $ATTACHMENT_ID_OLD=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME_OLD=$ROW["ATTACHMENT_NAME"];
}

if($ATTACHMENT_NAME!="")
{
   delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
   $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID_OLD);
   $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME_OLD);
       
   $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
   for($I=0;$I<$ARRAY_COUNT;$I++)
   {
       if($ATTACHMENT_ID_ARRAY[$I]==$ATTACHMENT_ID||$ATTACHMENT_ID_ARRAY[$I]=="")
          continue;
       $ATTACHMENT_ID1.=$ATTACHMENT_ID_ARRAY[$I].",";
       $ATTACHMENT_NAME1.=$ATTACHMENT_NAME_ARRAY[$I]."*";
   }
   $ATTACHMENT_ID=$ATTACHMENT_ID1;
   $ATTACHMENT_NAME=$ATTACHMENT_NAME1;
 

   $query="update NEWS set ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME' where NEWS_ID='$NEWS_ID'";
   exequery(TD::conn(),$query);
}
header("location: modify.php?NEWS_ID=$NEWS_ID&?start=$start&IS_MAIN=1");
?>

</body>
</html>
