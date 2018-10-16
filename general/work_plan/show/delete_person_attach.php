<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="select * from WORK_PERSON where AUTO_PERSON='$AUTO_PERSON'";
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
 

   $query="update WORK_PERSON set ATTACHMENT_ID='$ATTACHMENT_ID_NEW',ATTACHMENT_NAME='$ATTACHMENT_NAME_NEW' where AUTO_PERSON='$AUTO_PERSON'";
   exequery(TD::conn(),$query);
}

header("location: modify_resource.php?AUTO_PERSON=$AUTO_PERSON&USER_ID=$USER_ID&PLAN_ID=$PLAN_ID&USER_NAME=".urlencode($USER_NAME)."&NAME=".urlencode($NAME)."&URL_BEGIN_DATE=$URL_BEGIN_DATE&URL_END_DATE=$URL_END_DATE");
?>

</body>
</html>
