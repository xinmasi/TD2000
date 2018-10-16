<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("编辑联系人");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
//--------- 上传附件 ----------
if($ATTACHMENT!="")
{
   if($ATTACHMENT_ID_OLD!=""&&$ATTACHMENT_NAME_OLD!="")
      delete_attach($ATTACHMENT_ID_OLD,$ATTACHMENT_NAME_OLD);
   $ATTACHMENTS=upload();
   $ATTACHMENT_ID=trim($ATTACHMENTS["ID"],",");
   $ATTACHMENT_NAME=trim($ATTACHMENTS["NAME"],"*");
}
else
{
   $ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
   $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
}

//------------------- 保存 -----------------------
$query ="update ADDRESS set GROUP_ID='$GROUP_ID',PSN_NAME='$PSN_NAME',SEX='$SEX',BIRTHDAY='$BIRTHDAY',NICK_NAME='$NICK_NAME',";
$query.="MINISTRATION='$MINISTRATION',MATE='$MATE',CHILD='$CHILD',DEPT_NAME='$DEPT_NAME',ADD_DEPT='$ADD_DEPT',PSN_NO='$PSN_NO',";
$query.="POST_NO_DEPT='$POST_NO_DEPT',TEL_NO_DEPT='$TEL_NO_DEPT',FAX_NO_DEPT='$FAX_NO_DEPT',ADD_HOME='$ADD_HOME',";
$query.="POST_NO_HOME='$POST_NO_HOME',TEL_NO_HOME='$TEL_NO_HOME',MOBIL_NO='$MOBIL_NO',BP_NO='$BP_NO',EMAIL='$EMAIL',OICQ_NO='$OICQ_NO',ICQ_NO='$ICQ_NO',NOTES='$NOTES',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME' ";
$query.="where ADD_ID=$ADD_ID";

exequery(TD::conn(),$query);

save_field_data("ADDRESS",$ADD_ID,$_POST);
?>

<script>
location="index.php?GROUP_ID=<?=$GROUP_ID?>&start=<?=$start?>";
</script>

</body>
</html>
