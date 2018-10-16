<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");


$PLAN_ID = td_trim($PLAN_ID);
$query = "SELECT * FROM HR_RECRUIT_POOL L,HR_RECRUIT_PLAN N WHERE N.PLAN_ID in ($PLAN_ID) and L.PLAN_NO=N.PLAN_NO";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if(mysql_fetch_array($cursor))
{
   Message(_("错误"),sprintf(_("招聘计划中存在档案无法删除")));
   Button_Back();
   exit;
}

if(substr($PLAN_ID,-1)==",")
   $PLAN_ID = substr($PLAN_ID,0,-1);
$query="select PLAN_ID,ATTACHMENT_ID,ATTACHMENT_NAME from HR_RECRUIT_PLAN where PLAN_ID in ($PLAN_ID)";
$cursor= exequery(TD::conn(),$query);
$DELETE_STR="";
while($ROW=mysql_fetch_array($cursor))
{
   $PLAN_IDO=$ROW["PLAN_ID"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

   $DELETE_STR.=$PLAN_IDO.",";
   if($ATTACHMENT_NAME!="")
      delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}

$DELETE_STR=substr($DELETE_STR,0,-1);
$query="delete from HR_RECRUIT_PLAN where PLAN_ID in ($PLAN_ID)";
exequery(TD::conn(),$query);

header("location: index1.php?PAGE_START=$PAGE_START&connstatus=1");
?>
