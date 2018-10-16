<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");

if(substr($TRANSFER_ID,-1)==",")
   $TRANSFER_ID = substr($TRANSFER_ID,0,-1);

$query="select TRANSFER_ID,ATTACHMENT_ID,ATTACHMENT_NAME from HR_STAFF_TRANSFER where TRANSFER_ID in ($TRANSFER_ID)";
if($_SESSION["LOGIN_USER_PRIV"]!="1")
   $query.=" and CREATE_USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
$DELETE_STR="";
while($ROW=mysql_fetch_array($cursor))
{
  $TRANSFER_ID0=$ROW["TRANSFER_ID"];
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

  $DELETE_STR.=$TRANSFER_ID0.",";
  if($ATTACHMENT_NAME!="")
     delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}

$DELETE_STR=substr($DELETE_STR,0,-1);

$query="delete from HR_STAFF_TRANSFER where TRANSFER_ID in ($TRANSFER_ID)";
exequery(TD::conn(),$query);

header("location: index1.php?PAGE_START=$PAGE_START&connstatus=1");
?>
