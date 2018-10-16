<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");

$SEND_TIME=date("Y-m-d H:i:s",time());

if($_SESSION["LOGIN_USER_PRIV"]!="1")
   $query = "SELECT VOTE_ID from VOTE_TITLE where PARENT_ID=0 and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
else
   $query = "SELECT VOTE_ID from VOTE_TITLE where PARENT_ID=0";

$DELETE_STR = td_trim($DELETE_STR);
if($DELETE_STR!="")   
   $query .= " and VOTE_ID in ($DELETE_STR)";
   
$update_query="update VOTE_TITLE set READERS='' where ".substr($query,37);
exequery(TD::conn(),$update_query);

$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $VOTE_ID=$ROW["VOTE_ID"];

   $query_v = "select ITEM_ID from VOTE_ITEM where VOTE_ID = '$VOTE_ID'";
   $cursor_v= exequery(TD::conn(),$query_v);
   while($ROW=mysql_fetch_array($cursor_v))
   {
      $ITEM_ID_SUB=$ROW["ITEM_ID"];
      $query = "delete from VOTE_DATA where ITEM_ID='$ITEM_ID_SUB'";
      exequery(TD::conn(),$query);
   }

   $query = "update VOTE_DATA set USER_ID='',FIELD_NAME='',FIELD_DATA='' where ITEM_ID='$VOTE_ID'";
   exequery(TD::conn(),$query);
   $query = "update VOTE_ITEM set VOTE_COUNT=0, VOTE_USER='' where VOTE_ID='$VOTE_ID'";
   exequery(TD::conn(),$query);
   
   $query = "SELECT VOTE_ID from VOTE_TITLE where PARENT_ID='$VOTE_ID'";
   $cursor1= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor1))
   {
      $VOTE_ID_SUB=$ROW["VOTE_ID"];
      
      $query_v = "select ITEM_ID from VOTE_ITEM where VOTE_ID = '$VOTE_ID_SUB'";
      $cursor_v= exequery(TD::conn(),$query_v);
      while($ROW=mysql_fetch_array($cursor_v))
      {
         $ITEM_ID_SUB=$ROW["ITEM_ID"];
         $query = "delete from VOTE_DATA where ITEM_ID='$ITEM_ID_SUB'";
         exequery(TD::conn(),$query);
      }
      
      $query = "update VOTE_DATA set USER_ID='',FIELD_NAME='',FIELD_DATA='' where ITEM_ID='$VOTE_ID_SUB'";
      exequery(TD::conn(),$query);
      $query = "update VOTE_ITEM set VOTE_COUNT=0, VOTE_USER='' where VOTE_ID='$VOTE_ID_SUB'";
      exequery(TD::conn(),$query);
   }
   
   $query = "update VOTE_TITLE set READERS='' where PARENT_ID='$VOTE_ID'";
   exequery(TD::conn(),$query);
}
header("location: index1.php?start=$start&IS_MAIN=1");
?>