<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$LOG_ID = intval($LOG_ID);
$query="select ATTACHMENT_ID,ATTACHMENT_NAME from PROJ_TASK_LOG where LOG_ID=".$LOG_ID;
if($_SESSION["LOGIN_USER_PRIV"]!="1")
   $query.=" and LOG_USER='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

  if($ATTACHMENT_NAME!="")
     delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}
     
$query="delete from PROJ_TASK_LOG where LOG_ID='$LOG_ID'";
if($_SESSION["LOGIN_USER_PRIV"]!="1")
   $query.=" and LOG_USER='".$_SESSION["LOGIN_USER_ID"]."'";
exequery(TD::conn(),$query);

//更新任务总进度
$query="select 1 from PROJ_TASK_LOG where LOG_ID>'$LOG_ID'";
$cursor= exequery(TD::conn(),$query);
if(!mysql_fetch_array($cursor))
{
   $query="select PERCENT from PROJ_TASK_LOG WHERE TASK_ID='$TASK_ID' ORDER BY LOG_ID LIMIT 1";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $PERCENT_MAX=$ROW["PERCENT"];
      
	 $query="UPDATE PROJ_TASK SET TASK_PERCENT_COMPLETE='$PERCENT_MAX' WHERE TASK_ID='$TASK_ID'";
   exequery(TD::conn(),$query);
}

header("location: detail.php?PROJ_ID=$PROJ_ID&TASK_ID=$TASK_ID");
?>

</body>
</html>