<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="select ATTACHMENT_ID,ATTACHMENT_NAME from PROJ_TASK_LOG where LOG_ID='$LOG_ID'";
if($_SESSION["LOGIN_USER_PRIV"]!="1")
   $query.=" and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor=exequery(TD::conn(),$query);

if($ROW=mysql_fetch_array($cursor))
{
  $ATTACHMENT_ID_OLD=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME_OLD=$ROW["ATTACHMENT_NAME"];
}

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
   $ATTACHMENT_ID_NEW=$ATTACHMENT_ID1;
   $ATTACHMENT_NAME_NEW=$ATTACHMENT_NAME1;
 
   $query="update PROJ_TASK_LOG set ATTACHMENT_ID='$ATTACHMENT_ID_NEW',ATTACHMENT_NAME='$ATTACHMENT_NAME_NEW' where LOG_ID='$LOG_ID'";
   exequery(TD::conn(),$query);
}

header("location: detail.php?LOG_ID=<?=$LOG_ID1?>&PROJ_ID=<?=$PROJ_ID?>&TASK_ID=<?=$TASK_ID?>");
?>

</body>
</html>
