<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_all.php");
ob_start();

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$MANAGE_PRIV=0;
$ATTACHMENT_NAME_OLD="";
$query="select SORT_ID,UPLOAD_USER,ATTACHMENT_ID,ATTACHMENT_NAME from PROJ_FILE where FILE_ID='$FILE_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $SORT_ID=$ROW["SORT_ID"];
   $UPLOAD_USER=$ROW["UPLOAD_USER"];
   $ATTACHMENT_ID_OLD=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME_OLD=$ROW["ATTACHMENT_NAME"];
   
   $query = "SELECT MANAGE_USER from PROJ_FILE_SORT where SORT_ID='$SORT_ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
      $MANAGE_USER=$ROW["MANAGE_USER"];
      $MANAGE_PRIV= find_id($MANAGE_USER,$_SESSION["LOGIN_USER_ID"]);
   }
}

if($UPLOAD_USER!=$_SESSION["LOGIN_USER_ID"] && !$MANAGE_PRIV)
   exit;

if($ATTACHMENT_NAME!="")
{
   delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
   $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID_OLD);
   $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME_OLD);

   $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
   for($I=0;$I<$ARRAY_COUNT;$I++)
   {
       if($ATTACHMENT_ID_ARRAY[$I]==$ATTACHMENT_ID||$ATTACHMENT_ID_ARRAY[$I]=="")
          continue;
       $ATTACHMENT_ID1.=$ATTACHMENT_ID_ARRAY[$I].",";
       $ATTACHMENT_NAME1.=$ATTACHMENT_NAME_ARRAY[$I]."*";
   }
   $ATTACHMENT_ID=$ATTACHMENT_ID1;
   $ATTACHMENT_NAME=$ATTACHMENT_NAME1;

   $query="update PROJ_FILE set ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME' where FILE_ID='$FILE_ID'";
   exequery(TD::conn(),$query);
}

header("location: ./new/?PROJ_ID=$PROJ_ID&SORT_ID=$SORT_ID&FILE_ID=$FILE_ID&start=$start");
?>

</body>
</html>
