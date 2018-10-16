<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
//--------- ÉÏ´«¸½¼þ ----------
//echo $ATTACHMENT;
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

$query="update WORK_DETAIL set PROGRESS='$PROGRESS',WRITE_TIME='$WRITE_TIME',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME' where DETAIL_ID='$DETAIL_ID'";
exequery(TD::conn(),$query);

header("location: ../manage/add_opinion.php?PLAN_ID=$PLAN_ID");
?>
</body>
</html>
