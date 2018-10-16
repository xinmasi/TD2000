<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");
if(substr($EXPERT_ID,-1)==",")
   $EXPERT_ID = substr($EXPERT_ID,0,-1);

$query="select EXPERT_ID,ATTACHMENT_ID,ATTACHMENT_NAME from HR_RECRUIT_POOL where EXPERT_ID in ($EXPERT_ID)";
$cursor= exequery(TD::conn(),$query);
$DELETE_STR="";
while($ROW=mysql_fetch_array($cursor))
{
  $EXPERT_ID0=$ROW["EXPERT_ID"];
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

  $DELETE_STR.=$EXPERT_ID0.",";
  if($ATTACHMENT_NAME!="")
     delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}

$DELETE_STR=substr($DELETE_STR,0,-1);

$query="delete from HR_RECRUIT_POOL where EXPERT_ID in ($EXPERT_ID)";
exequery(TD::conn(),$query);

header("location: index1.php?PAGE_START=$PAGE_START&connstatus=1");
?>
