<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");
if(substr($W_EXPERIENCE_ID,-1)==",")
   $W_EXPERIENCE_ID = substr($W_EXPERIENCE_ID,0,-1);

$query="select W_EXPERIENCE_ID,ATTACHMENT_ID,ATTACHMENT_NAME from HR_STAFF_WORK_EXPERIENCE where W_EXPERIENCE_ID in ($W_EXPERIENCE_ID)";

$cursor= exequery(TD::conn(),$query);
$DELETE_STR="";
while($ROW=mysql_fetch_array($cursor))
{
  $W_EXPERIENCE_ID0=$ROW["W_EXPERIENCE_ID"];
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

  $DELETE_STR.=$W_EXPERIENCE_ID0.",";
  if($ATTACHMENT_NAME!="")
     delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}

$DELETE_STR=substr($DELETE_STR,0,-1);

$query="delete from HR_STAFF_WORK_EXPERIENCE where W_EXPERIENCE_ID in ($W_EXPERIENCE_ID)";
exequery(TD::conn(),$query);

header("location: index1.php?PAGE_START=$PAGE_START&connstatus=1");
?>
