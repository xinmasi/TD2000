<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");
if(substr($RELATIVES_ID,-1)==",")
   $RELATIVES_ID = substr($RELATIVES_ID,0,-1);

$query="select RELATIVES_ID,ATTACHMENT_ID,ATTACHMENT_NAME from HR_STAFF_RELATIVES where RELATIVES_ID in ($RELATIVES_ID)";

$cursor= exequery(TD::conn(),$query);
$DELETE_STR="";
while($ROW=mysql_fetch_array($cursor))
{
  $RELATIVES_ID0=$ROW["RELATIVES_ID"];
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

  $DELETE_STR.=$RELATIVES_ID0.",";
  if($ATTACHMENT_NAME!="")
     delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}

$DELETE_STR=substr($DELETE_STR,0,-1);

$query="delete from HR_STAFF_RELATIVES where RELATIVES_ID in ($RELATIVES_ID)";
exequery(TD::conn(),$query);

header("location: index1.php?PAGE_START=$PAGE_START&connstatus=1");
?>
