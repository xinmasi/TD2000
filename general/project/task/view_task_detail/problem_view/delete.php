<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="select ATTACHMENT_ID,ATTACHMENT_NAME from PROJ_BUG where BUG_ID = '$BUG_ID' and BEGIN_USER='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

  if($ATTACHMENT_NAME!="")
     delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}  
$query="delete from PROJ_BUG where BUG_ID='$BUG_ID' and BEGIN_USER='".$_SESSION["LOGIN_USER_ID"]."'";
exequery(TD::conn(),$query);

header("location: index.php?PROJ_ID=$PROJ_ID&TASK_ID=$TASK_ID");
?>
</body>
</html>
