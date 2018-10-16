<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
if(count($_FILES)>1)
{
   $ATTACHMENTS=upload();
   $CONTENT=ReplaceImageSrc($CONTENT, $ATTACHMENTS);

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
$query="update VEHICLE set CAR_USER = '$TO_NAME', HISTORT = '$HISTORT', ATTACH_ID = '$ATTACHMENT_ID', ATTACH_NAME = '$ATTACHMENT_NAME' where V_ID = '$V_ID'";
$cursor=exequery(TD::conn(),$query);
?>
<script>window.location.href="archives.php?V_ID=<?=$V_ID?>";</script>