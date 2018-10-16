<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");

$SEND_TIME=date("Y-m-d H:i:s",time());

if($_SESSION["LOGIN_USER_PRIV"]!="1")
   $query = "SELECT * from VOTE_TITLE where PARENT_ID=0 and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
else
   $query = "SELECT * from VOTE_TITLE where PARENT_ID=0";

$DELETE_STR = td_trim($DELETE_STR);
if($DELETE_STR!="")   
   $query .= " and VOTE_ID in ($DELETE_STR)";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $VOTE_ID=$ROW["VOTE_ID"];
   $TO_ID=$ROW["TO_ID"];
   $PRIV_ID=$ROW["PRIV_ID"];
   $USER_ID=$ROW["USER_ID"];
   $SUBJECT=$ROW["SUBJECT"];
   $CONTENT=$ROW["CONTENT"];
   $TYPE=$ROW["TYPE"];
   $MAX_NUM=$ROW["MAX_NUM"];
   $MIN_NUM=$ROW["MIN_NUM"];
   $ANONYMITY=$ROW["ANONYMITY"];
   $VIEW_PRIV=$ROW["VIEW_PRIV"];
   $BEGIN_DATE=$ROW["BEGIN_DATE"];
   $END_DATE=$ROW["END_DATE"];
   $PUBLISH=$ROW["PUBLISH"];
   $VOTE_NO=$ROW["VOTE_NO"];
   
   $SUBJECT.=" - "._("¸´ÖÆ");
   $query="insert into VOTE_TITLE(FROM_ID,PARENT_ID,TO_ID,PRIV_ID,USER_ID,SUBJECT,CONTENT,SEND_TIME,BEGIN_DATE,END_DATE,TYPE,MAX_NUM,MIN_NUM,ANONYMITY,VIEW_PRIV,PUBLISH,VOTE_NO) values ('".$_SESSION["LOGIN_USER_ID"]."','0','$TO_ID','$PRIV_ID','$USER_ID','$SUBJECT','$CONTENT','$SEND_TIME','$BEGIN_DATE','$END_DATE','$TYPE','$MAX_NUM','$MIN_NUM','$ANONYMITY','$VIEW_PRIV','$PUBLISH','$VOTE_NO')";
   exequery(TD::conn(),$query);
   $VOTE_ID_NEW=mysql_insert_id();
   
   $query = "SELECT ITEM_NAME from VOTE_ITEM where VOTE_ID='$VOTE_ID' order by ITEM_ID";
   $cursor1= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor1))
   {
      $ITEM_NAME=$ROW["ITEM_NAME"];
      
      $query = "insert into VOTE_ITEM (VOTE_ID,ITEM_NAME) values ('$VOTE_ID_NEW','$ITEM_NAME')";
      exequery(TD::conn(),$query);
   }
   
   $query = "SELECT * from VOTE_TITLE where PARENT_ID='$VOTE_ID'";
   $cursor1= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor1))
   {
      $VOTE_ID_SUB=$ROW["VOTE_ID"];
      $SUBJECT=$ROW["SUBJECT"];
      $CONTENT=$ROW["CONTENT"];
      $TYPE=$ROW["TYPE"];
      $MAX_NUM=$ROW["MAX_NUM"];
      $MIN_NUM=$ROW["MIN_NUM"];
      $VOTE_NO=$ROW["VOTE_NO"];
      
      $query="insert into VOTE_TITLE(FROM_ID,PARENT_ID,SUBJECT,CONTENT,SEND_TIME,TYPE,MAX_NUM,MIN_NUM,VOTE_NO) values ('".$_SESSION["LOGIN_USER_ID"]."','$VOTE_ID_NEW','$SUBJECT','$CONTENT','$SEND_TIME','$TYPE','$MAX_NUM','$MIN_NUM','$VOTE_NO')";
      exequery(TD::conn(),$query);
      $VOTE_ID_SUB_NEW=mysql_insert_id();
      
      $query = "SELECT ITEM_NAME from VOTE_ITEM where VOTE_ID='$VOTE_ID_SUB' order by ITEM_ID";
      $cursor2= exequery(TD::conn(),$query);
      while($ROW=mysql_fetch_array($cursor2))
      {
         $ITEM_NAME=$ROW["ITEM_NAME"];
         
         $query = "insert into VOTE_ITEM (VOTE_ID,ITEM_NAME) values ('$VOTE_ID_SUB_NEW','$ITEM_NAME')";
         exequery(TD::conn(),$query);
      }
   }
}

header("location: index1.php?start=$start&IS_MAIN=1");
?>