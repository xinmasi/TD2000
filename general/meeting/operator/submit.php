<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
if(!isset($SUMMARY_APPROVE))
{
    $SUMMARY_APPROVE = 0;
}

$MEETING_OPERATOR=$COPY_TO_ID;

//--------- 上传附件 ----------
if(count($_FILES)>1)
{
    $ATTACHMENTS=upload();
    $CONTENT=ReplaceImageSrc($CONTENT, $ATTACHMENTS);
    $ATTACHMENT_ID = $ATTACHMENT_ID_OLD.$ATTACHMENTS["ID"];
    $ATTACHMENT_NAME = $ATTACHMENT_NAME_OLD.$ATTACHMENTS["NAME"];
}
else
{
    $ATTACHMENT_ID = $ATTACHMENT_ID_OLD;
    $ATTACHMENT_NAME = $ATTACHMENT_NAME_OLD;
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

$C = preg_match('/<img.*?\ssrc=\\\"\/inc\/attach.php\?(.*)MODULE=upload_temp/i',$MR_RULE);
$MR_RULE = replace_attach_url($MR_RULE);
if($C==1)
{
    $ATTACHMENT_ID=move_attach($ATTACHMENT_ID,$ATTACHMENT_NAME,"","",true).",";
}

if($RULE_ID)
{
    $query="update meeting_rule set MEETING_ROOM_RULE='$MR_RULE',MEETING_OPERATOR='$MEETING_OPERATOR',MEETING_IS_APPROVE='$IS_APPROVE',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME',SUMMARY_APPROVE='$SUMMARY_APPROVE' where RULE_ID='$RULE_ID'";
    exequery(TD::conn(),$query);
}
else
{
    $query="insert into meeting_rule(MEETING_ROOM_RULE,MEETING_OPERATOR,MEETING_IS_APPROVE,ATTACHMENT_ID,ATTACHMENT_NAME,SUMMARY_APPROVE) values ('$MR_RULE','$MEETING_OPERATOR','$IS_APPROVE','$ATTACHMENT_ID','$ATTACHMENT_NAME','$SUMMARY_APPROVE')";
    exequery(TD::conn(),$query);
}

set_sys_para(array("MEETING_OPERATOR" => "$MEETING_OPERATOR","MEETING_ROOM_RULE"=>"$MR_RULE","MEETING_IS_APPROVE"=>"$IS_APPROVE","SUMMARY_APPROVE"=>"$SUMMARY_APPROVE"));

Message(_("提示"),_("保存成功"));
Button_Back();
?>
</body>

</html>