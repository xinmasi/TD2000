<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("创建问题");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
$CUR_DATE=date("Y-m-d",time());

//--------- 上传附件 ----------
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

if($BUG_ID==""){
    $query="insert into PROJ_BUG (PROJ_ID,TASK_ID,BUG_NAME,BUG_DESC,LEVEL,CREAT_TIME,BEGIN_USER,DEAL_USER,DEAD_LINE,STATUS,ATTACHMENT_ID,ATTACHMENT_NAME) values ('$PROJ_ID','$TASK_ID','$BUG_NAME','$BUG_DESC','$LEVEL','$CUR_TIME','".$_SESSION["LOGIN_USER_ID"]."','$DEAL_USER','$DEAD_LINE','$SAVE_FLAG','$ATTACHMENT_ID','$ATTACHMENT_NAME')";
}else{
    $BUG_ID = intval($BUG_ID);
    $query="update PROJ_BUG set BUG_NAME='$BUG_NAME',BUG_DESC='$BUG_DESC',LEVEL='$LEVEL',DEAL_USER='$DEAL_USER',DEAD_LINE='$DEAD_LINE',STATUS='$SAVE_FLAG',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME' where BUG_ID=".$BUG_ID;
}
exequery(TD::conn(),$query);

//事务提醒
$SMS_CONTENT=_("有新的项目问题等待您处理。");

$REMIND_URL="1:project/bug/";
if($SMS_REMIND=="on")
    send_sms($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$DEAL_USER,42,$SMS_CONTENT,$REMIND_URL);

if($SMS2_REMIND=="on")
    send_mobile_sms_user($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$DEAL_USER,$SMS_CONTENT,42);

header("location: index.php?PROJ_ID=$PROJ_ID&TASK_ID=$TASK_ID");
?>
</body>
</html>