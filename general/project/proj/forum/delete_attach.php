<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");

$MSG_ID = intval($MSG_ID);
$query="select * from PROJ_FORUM where MSG_ID=".$MSG_ID;
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

   $query="update PROJ_FORUM set ATTACHMENT_ID=".$ATTACHMENT_ID.",ATTACHMENT_NAME='$ATTACHMENT_NAME' where MSG_ID=".$MSG_ID;
   exequery(TD::conn(),$query);
}


header("location:  edit.php?PROJ_ID=$PROJ_ID&MSG_ID=$MSG_ID&PAGE_START=$PAGE_START");
?>
