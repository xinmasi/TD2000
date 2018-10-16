<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");

if(substr($DELETE_STR,-1,1)==",")
   $DELETE_STR=substr($DELETE_STR,0,-1);

$query="select ATTACHMENT_ID,ATTACHMENT_NAME from BOOK_INFO where BOOK_ID in ($DELETE_STR)";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
  
  if($ATTACHMENT_NAME!="")
     delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}
  
$query="delete from BOOK_INFO where BOOK_ID in ($DELETE_STR)";
exequery(TD::conn(),$query);

header("location: list.php?TYPE_ID=$TYPE_ID&BOOK_NAME=$BOOK_NAME&AUTHOR=$AUTHOR&ISBN=$ISBN&PUB_HOUSE=$PUB_HOUSE&AREA=$AREA&DEPT_ID=$DEPT_ID&ORDER_FIELD=$ORDER_FIELD&PAGE_START=$PAGE_START");

?>


