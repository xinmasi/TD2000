<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_all.php");

$CUR_TIME=date("Y-m-d H:i:s",time());
include_once("inc/header.inc.php");
mysql_set_charset(MYOA_DB_CHARSET, TD::conn());
?>

<body class="bodycolor">

<?
$COMMENT_ID=intval($COMMENT_ID);
if($SIGNED_YN=="on")
    $SIGNED_YN=1;
else
    $SIGNED_YN=0;

if($NEED_CHECK==0)
{
    $IS_CHECK=9;
    $qu="update BBS_COMMENT set IS_CHECK='$IS_CHECK' where COMMENT_ID='$COMMENT_ID'";
    exequery(TD::conn(),$qu);
}

$AUTHOR_NAME_TMEP=$AUTHOR_NAME;

$NICK_NAME=trim($NICK_NAME);
if($AUTHOR_NAME==2 && $NICK_NAME!="" && $NICK_NAME!=$_SESSION["LOGIN_USER_NAME"])
{
    $query = "SELECT USER_NAME from USER where USER_NAME = '$NICK_NAME' LIMIT 0 , 1";
    $cursor = exequery(TD::conn(), $query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $MSG=sprintf(_("不能用“%s”昵称!"),$NICK_NAME);
        Message("",$MSG);
        Button_Back();
        exit;
    }
}

if($AUTHOR_NAME=="1")
    $AUTHOR_NAME = $USER_NAME;
else
    $AUTHOR_NAME = $NICK_NAME;

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

$C = preg_match('/<img.*?\ssrc=\\\"\/inc\/attach.php\?(.*)MODULE=upload_temp/i',$CONTENT);
$CONTENT = replace_attach_url($CONTENT);
if($C==1)
{
    $ATTACHMENT_ID=move_attach($ATTACHMENT_ID,$ATTACHMENT_NAME,"","",true).",";//file_put_contents('22.txt', $ATTACHMENT_ID);
}
if($SUBJECT=="")
    $SUBJECT=_("无标题");
if(mb_detect_encoding($SUBJECT,array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')) == "CP936"){
    $SUBJECT = stripslashes($SUBJECT);
}
$query = "SELECT USER_ID from BBS_COMMENT where COMMENT_ID='$COMMENT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $NEW_USER_ID=$ROW["USER_ID"];

$UPDATE_STR = "";
if($NEW_USER_ID==$_SESSION["LOGIN_USER_ID"])
    $UPDATE_STR=",AUTHOR_NAME='$AUTHOR_NAME',AUTHOR_NAME_TMEP='{$_POST["AUTHOR_NAME"]}'";

if($ATTACHMENT_NAME!="")
    $query = "update BBS_COMMENT set SUBJECT='$SUBJECT', TYPE='$TYPE',CONTENT='$CONTENT', ATTACHMENT_ID='$ATTACHMENT_ID', ATTACHMENT_NAME='$ATTACHMENT_NAME',SIGNED_YN='$SIGNED_YN',UPDATE_PERSON='".$_SESSION["LOGIN_USER_ID"]."',UPDATE_TIME='$CUR_TIME' ".$UPDATE_STR." where COMMENT_ID='$COMMENT_ID'";
else
    $query = "update BBS_COMMENT set SUBJECT='$SUBJECT', TYPE='$TYPE',CONTENT='$CONTENT', SIGNED_YN='$SIGNED_YN',UPDATE_PERSON='".$_SESSION["LOGIN_USER_ID"]."',UPDATE_TIME='$CUR_TIME' ".$UPDATE_STR."  where COMMENT_ID='$COMMENT_ID'";

exequery(TD::conn(), $query);

//--------------------------
$SYS_PARA_ARRAY = get_sys_para("SMS_REMIND");
$PARA_VALUE=$SYS_PARA_ARRAY["SMS_REMIND"];
$SMS_REMIND1=substr($PARA_VALUE,0,strpos($PARA_VALUE,"|"));
$SMS2_REMIND1=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);

$REMIND_URL="bbs/comment.php?BOARD_ID=".$BOARD_ID_OLD."&COMMENT_ID=".$COMMENT_ID."&PAGE_START=1";

if($AUTHOR_NAME_TMEP=="1")
    $FROM_USER = $_SESSION["LOGIN_USER_ID"];
else
    $FROM_USER = "admin"; //匿名短消息显示为admin发送的
if($AUTHOR_NAME=="1")
    $AUTHOR_NAME = $USER_NAME;
else
    $AUTHOR_NAME = $NICK_NAME;

if($SEND_TITLE_FLAG==1 && ($SMS_SELECT_REMIND=="1" || $SMS_SELECT_REMIND_TO_ID!="" || $SMS2_SELECT_REMIND=="1" || $SMS2_SELECT_REMIND_TO_ID!=""))
{

    $query = "SELECT DEPT_ID,PRIV_ID,USER_ID,BOARD_HOSTER from BBS_BOARD where BOARD_ID='$BOARD_ID'";
    $cursor = exequery(TD::conn(), $query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $TENP_DEPT_ID=$ROW["DEPT_ID"];
        $TENP_PRIV_ID=$ROW["PRIV_ID"];
        $TENP_USER_ID=$ROW["USER_ID"];
        $BOARD_HOSTER=$ROW["BOARD_HOSTER"];

        if($TENP_DEPT_ID=="ALL_DEPT")
            $query1="select USER_ID from USER where NOT_LOGIN!='1'";
        else
            $query1="select USER_ID from USER where NOT_LOGIN!='1' and (find_in_set(DEPT_ID,'$TENP_DEPT_ID') or find_in_set(USER_PRIV,'$TENP_PRIV_ID') or find_in_set(USER_ID,'$TENP_USER_ID') or find_in_set(USER_ID,'$BOARD_HOSTER'))";
        $cursor1=exequery(TD::conn(),$query1);
        while($ROW1=mysql_fetch_array($cursor1))
            $USER_ID_STR.=$ROW1["USER_ID"].",";
    }

    $TO_ID_STR=$USER_ID_STR;
    $TO_ID_STR1 = $SMS_SELECT_REMIND=="1" ? $TO_ID_STR : check_id($TO_ID_STR, $SMS_SELECT_REMIND_TO_ID, true);
    $TO_ID_STR2 = $SMS2_SELECT_REMIND=="1" ? $TO_ID_STR : check_id($TO_ID_STR, $SMS2_SELECT_REMIND_TO_ID, true);
    $REMIND_URL="bbs/comment.php?BOARD_ID=".$BOARD_ID_OLD."&COMMENT_ID=".$COMMENT_ID."&PAGE_START=1";
    if($AUTHOR_NAME_TMEP=="1")
        $MSG_CONTENT = sprintf(_("%s编辑贴子，主题为《%s》"),$_SESSION["LOGIN_USER_NAME"],$SUBJECT);
    else
        $MSG_CONTENT = sprintf(_("%s编辑贴子，主题为《%s》"),$NICK_NAME,$SUBJECT);

    if($TO_ID_STR1!="")
        send_sms("",$FROM_USER,$TO_ID_STR1,18,$MSG_CONTENT,$REMIND_URL,$BOARD_ID_OLD);
    if($TO_ID_STR2!="")
        send_mobile_sms_user("",$FROM_USER,$TO_ID_STR2,$MSG_CONTENT,18);
}

$query="select *  from BBS_COMMENT where COMMENT_ID='$COMMENT_ID'";
$cursor = exequery(TD::conn(), $query);
if($ROW=mysql_fetch_array($cursor))
{
    $PARENT=$ROW["PARENT"];
}

if($PARENT==0)
    $PARENT=$COMMENT_ID;
if($OP=="0")
    header("location: edit.php?BOARD_ID=$BOARD_ID&PAGE_START=$PAGE_START&COMMENT_ID=$COMMENT_ID&IS_MAIN=1");
else
{
    if($BOARD_ID_OLD!="-1")
    {
        header("location: comment.php?BOARD_ID=$BOARD_ID_OLD&COMMENT_ID=$PARENT&PAGE_START=$PAGE_START&IS_MAIN=1");
    }else
    {
        echo "<script>window.close();</script>";
    }
}
?>

</body>
</html>