<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");
$COMMENT_ID=intval($COMMENT_ID);
$query="select * from BBS_COMMENT where COMMENT_ID='$COMMENT_ID'";
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

   $query="update BBS_COMMENT set ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME' where COMMENT_ID='$COMMENT_ID'";
   exequery(TD::conn(),$query);
}


header("location:  edit.php?BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START");
?>
