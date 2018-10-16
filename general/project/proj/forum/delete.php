<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
if($DELETE_STR=="")
   $DELETE_STR=$MSG_ID;

$query = "SELECT PROJ_OWNER from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $PROJ_OWNER = $ROW["PROJ_OWNER"];

$query="select * from PROJ_FORUM where (find_in_set(MSG_ID,'$DELETE_STR') or find_in_set(PARENT,'$DELETE_STR'))";
if($PROJ_OWNER!=$_SESSION["LOGIN_USER_ID"] && $_SESSION["LOGIN_USER_PRIV"]!=1)
   $query.=" AND USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

   if($ATTACHMENT_NAME!="")
   {
      $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
      $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);

      $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
      for($I=0;$I<$ARRAY_COUNT;$I++)
      {
         if($ATTACHMENT_ID_ARRAY[$I]!="")
            delete_attach($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]);
      }
   }
}

$query="select *  from PROJ_FORUM where find_in_set(MSG_ID,'$DELETE_STR')";
$cursor = exequery(TD::conn(), $query);
while($ROW=mysql_fetch_array($cursor))
{
	 $PARENT=$ROW["PARENT"];
	 
   if($PARENT!=0)
   {
      $PARENT = intval($PARENT);
      $query="update PROJ_FORUM set REPLY_CONT=REPLY_CONT-1 where MSG_ID=".$PARENT;
      exequery(TD::conn(), $query);
   }	 
}

$query="delete from PROJ_FORUM where (find_in_set(MSG_ID,'$DELETE_STR') or find_in_set(PARENT,'$DELETE_STR'))";
if($_SESSION["LOGIN_USER_PRIV"]!=1 && $_SESSION["LOGIN_USER_PRIV"]!=$PROJ_OWNER)
   $query .=" AND USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
exequery(TD::conn(), $query);

if($PARENT==0)
   header("location: index.php?PROJ_ID=$PROJ_ID&PAGE_START=$PAGE_START&start=$start");
else
   header("location: comment.php?PROJ_ID=$PROJ_ID&MSG_ID=$PARENT&PAGE_START=$PAGE_START");
?>

</body>
</html>