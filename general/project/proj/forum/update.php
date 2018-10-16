<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
if(count($_FILES)>1)
{
   $ATTACHMENTS=upload();

   $ATTACHMENT_ID=$ATTACHMENT_ID_OLD.$ATTACHMENTS["ID"];
   $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD.$ATTACHMENTS["NAME"];
}
else
{
   $ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
   $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
}
 
$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

if($SUBJECT=="")
   $SUBJECT=_("ÎÞ±êÌâ");

$MSG_ID = intval($MSG_ID);
$query = "update PROJ_FORUM set SUBJECT='".$SUBJECT."', CONTENT='".$CONTENT."', ATTACHMENT_ID='".$ATTACHMENT_ID."', ATTACHMENT_NAME='".$ATTACHMENT_NAME."' where MSG_ID='".$MSG_ID."'";
exequery(TD::conn(), $query);

$query="select *  from PROJ_FORUM where MSG_ID=".$MSG_ID;
$cursor = exequery(TD::conn(), $query);
if($ROW=mysql_fetch_array($cursor))
 	 $PARENT=$ROW["PARENT"];

if($PARENT==0)
   $PARENT=$MSG_ID;

header("location: comment.php?PROJ_ID=$PROJ_ID&MSG_ID=$PARENT&PAGE_START=$PAGE_START");
?>

</body>
</html>