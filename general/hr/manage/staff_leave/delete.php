<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");

if(substr($LEAVE_ID,-1)==",")
   $LEAVE_ID = substr($LEAVE_ID,0,-1);

$query="select LEAVE_ID,ATTACHMENT_ID,ATTACHMENT_NAME from HR_STAFF_LEAVE where LEAVE_ID in ($LEAVE_ID)";

$cursor= exequery(TD::conn(),$query);
$DELETE_STR="";
while($ROW=mysql_fetch_array($cursor))
{
  $LEAVE_ID0=$ROW["LEAVE_ID"];
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

  $DELETE_STR.=$LEAVE_ID0.",";
  if($ATTACHMENT_NAME!="")
     delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}

$DELETE_STR=substr($DELETE_STR,0,-1);
   
$query="delete from  HR_STAFF_LEAVE where LEAVE_ID in ($LEAVE_ID)";
exequery(TD::conn(),$query);
if(isset($PAGE_FROM) && 'query_current_month' == $PAGE_FROM){
    header("location: query_current_month.php?PAGE_START=$PAGE_START&connstatus=1");
}else{
    header("location: index1.php?PAGE_START=$PAGE_START&connstatus=1");
}

?>
