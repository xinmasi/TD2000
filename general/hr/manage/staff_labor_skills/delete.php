<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");
if(substr($SKILLS_ID,-1)==",")
   $SKILLS_ID = substr($SKILLS_ID,0,-1);

$query="select SKILLS_ID,ATTACHMENT_ID,ATTACHMENT_NAME from HR_STAFF_LABOR_SKILLS where SKILLS_ID in ($SKILLS_ID)";

$cursor= exequery(TD::conn(),$query);
$DELETE_STR="";
while($ROW=mysql_fetch_array($cursor))
{
  $SKILLS_ID0=$ROW["SKILLS_ID"];
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

  $DELETE_STR.=$SKILLS_ID0.",";
  if($ATTACHMENT_NAME!="")
     delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}

$DELETE_STR=substr($DELETE_STR,0,-1);

$query="delete from HR_STAFF_LABOR_SKILLS where SKILLS_ID in ($SKILLS_ID)";
exequery(TD::conn(),$query);

header("location: index1.php?PAGE_START=$PAGE_START&connstatus=1");
?>
