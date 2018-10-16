<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");
if(substr($T_PLAN_ID,-1)==",")
   $T_PLAN_ID = substr($T_PLAN_ID,0,-1);

if(preg_match("/[^0-9,]+/", $T_PLAN_ID))
{
    echo _("无效的参数");
    exit;
}

$query="select T_PLAN_ID,ATTACHMENT_ID,ATTACHMENT_NAME from HR_TRAINING_PLAN where T_PLAN_ID in ($T_PLAN_ID)";
$cursor= exequery(TD::conn(),$query);
$DELETE_STR="";
while($ROW=mysql_fetch_array($cursor))
{
  $T_PLAN_IDO=$ROW["T_PLAN_ID"];
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

  $DELETE_STR.=$T_PLAN_IDO.",";
  if($ATTACHMENT_NAME!="")
     delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}

$DELETE_STR=substr($DELETE_STR,0,-1);
$query="delete from HR_TRAINING_PLAN where T_PLAN_ID in ($T_PLAN_ID)";
exequery(TD::conn(),$query);

header("location: index1.php?PAGE_START=$PAGE_START&connstatus=1");
?>
