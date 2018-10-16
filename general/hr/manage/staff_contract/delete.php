<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");
if(substr($CONTRACT_ID,-1)==",")
   $CONTRACT_ID = substr($CONTRACT_ID,0,-1);

if($CONTRACT_ID == '' || !td_verify_ids($CONTRACT_ID))
{
    echo _T("CONTRACT_ID参数无效");
    exit;
}

$query="select CONTRACT_ID,ATTACHMENT_ID,ATTACHMENT_NAME from HR_STAFF_CONTRACT where CONTRACT_ID in ($CONTRACT_ID)";
$cursor= exequery(TD::conn(),$query);
$DELETE_STR="";
while($ROW=mysql_fetch_array($cursor))
{
  $CONTRACT_ID0=$ROW["CONTRACT_ID"];
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

  $DELETE_STR.=$CONTRACT_ID0.",";
  if($ATTACHMENT_NAME!="")
     delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}

$DELETE_STR=substr($DELETE_STR,0,-1);

$query="delete from HR_STAFF_CONTRACT where CONTRACT_ID in ($CONTRACT_ID)";
exequery(TD::conn(),$query);

header("location: index1.php?PAGE_START=$PAGE_START&connstatus=1");
?>
