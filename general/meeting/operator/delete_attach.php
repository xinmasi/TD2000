<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
$query="select * from meeting_rule";
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
   $ATTACHMENT_ID_NEW=$ATTACHMENT_ID1;
   $ATTACHMENT_NAME_NEW=$ATTACHMENT_NAME1;
 
   $query="update meeting_rule set ATTACHMENT_ID='$ATTACHMENT_ID_NEW',ATTACHMENT_NAME='$ATTACHMENT_NAME_NEW'";
   exequery(TD::conn(),$query);
}

header("location: index.php");
?>
</body>
</html>
