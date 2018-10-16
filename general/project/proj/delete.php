<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_project.php");
ob_end_clean();
$PROJ_ID_STR_OLD = $PROJ_ID_STR;
$PROJ_ID_STR = '';
$delete_auth = "AND (".$_SESSION['LOGIN_SYS_ADMIN']." = 1 OR PROJ_OWNER='".$_SESSION["LOGIN_USER_ID"]."' OR PROJ_MANAGER='".$_SESSION["LOGIN_USER_ID"]."' OR PROJ_LEADER='".$_SESSION["LOGIN_USER_ID"]."' )";
$query="select PROJ_ID from PROJ_PROJECT WHERE PROJ_ID IN ($PROJ_ID_STR_OLD) $delete_auth";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $PROJ_ID_STR.=$ROW['PROJ_ID'].",";
}
$PROJ_ID_STR = td_trim($PROJ_ID_STR);
if(!$PROJ_ID_STR)
{
    echo 0;
    exit;
}
$query="select ATTACHMENT_ID,ATTACHMENT_NAME from PROJ_BUG WHERE PROJ_ID IN ($PROJ_ID_STR)";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

  if($ATTACHMENT_NAME!="")
     delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}

$query="select ATTACHMENT_ID,ATTACHMENT_NAME from PROJ_FILE WHERE PROJ_ID IN ($PROJ_ID_STR)";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

  if($ATTACHMENT_NAME!="")
     delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}

$query="select ATTACHMENT_ID,ATTACHMENT_NAME from PROJ_FORUM WHERE PROJ_ID IN ($PROJ_ID_STR)";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

  if($ATTACHMENT_NAME!="")
     delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}

$query="select TASK_ID from PROJ_TASK WHERE PROJ_ID IN ($PROJ_ID_STR)";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
   $TASK_STR .= $ROW["TASK_ID"].",";

if($TASK_STR!="")
{
  $TASK_STR=substr($TASK_STR,0,-1);
  $query="select ATTACHMENT_ID,ATTACHMENT_NAME from PROJ_TASK_LOG WHERE TASK_ID IN ($TASK_STR)";
  $cursor= exequery(TD::conn(),$query);
  while($ROW=mysql_fetch_array($cursor))
  {
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
  
    if($ATTACHMENT_NAME!="")
       delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
  }
  
  $query = "delete from PROJ_TASK_LOG WHERE TASK_ID IN ($TASK_STR)";
  exequery(TD::conn(),$query);
}
$query = "delete from PROJ_FILE WHERE PROJ_ID IN ($PROJ_ID_STR)";
exequery(TD::conn(),$query);

$query = "delete from PROJ_FILE_SORT WHERE PROJ_ID IN ($PROJ_ID_STR)";
exequery(TD::conn(),$query);

$query = "delete from PROJ_BUG WHERE PROJ_ID IN ($PROJ_ID_STR)";
exequery(TD::conn(),$query);

$query = "delete from PROJ_FORUM WHERE PROJ_ID IN ($PROJ_ID_STR)";
exequery(TD::conn(),$query);

$delete_news = "delete from proj_news where PROJ_ID IN ($PROJ_ID_STR)";
exequery(TD::conn(),$delete_news);

$query = "delete from PROJ_TASK WHERE PROJ_ID IN ($PROJ_ID_STR)";
exequery(TD::conn(),$query);

$query = "delete from PROJ_COMMENT WHERE PROJ_ID IN ($PROJ_ID_STR)";
exequery(TD::conn(),$query);

$query = "delete from PROJ_PROJECT WHERE PROJ_ID IN ($PROJ_ID_STR)";
exequery(TD::conn(),$query);
echo $PROJ_ID_STR;
?>