<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("讨论区帖子审批");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?

$CUR_DATE=date("Y-m-d",time());
if($DELETE_STR == "" && $COMMENT_ID != "")
    $DELETE_STR = $COMMENT_ID;
elseif(substr($DELETE_STR,-1,1)==",")
    $DELETE_STR=substr($DELETE_STR,0,-1);
if ($OP=="0")
{
    $query="update BBS_COMMENT set IS_CHECK='2' where COMMENT_ID in ($DELETE_STR) AND BOARD_ID='$BOARD_ID'";
    exequery(TD::conn(),$query);

    $query="select COMMENT_ID,BOARD_ID,SUBJECT,SUBMIT_TIME,USER_ID,AUTHOR_NAME from BBS_COMMENT where COMMENT_ID in ($DELETE_STR) AND BOARD_ID='$BOARD_ID'";
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $COMMENT_ID=$ROW["COMMENT_ID"];
        $USER_ID=$ROW["USER_ID"];
        $SUBJECT=$ROW["SUBJECT"];
        $SUBMIT_TIME=$ROW["SUBMIT_TIME"];
        $REMIND_URL="1:bbs/check_comment.php?COMMENT_ID=".$COMMENT_ID."&BOARD_ID=".$BOARD_ID."";
        $SUBJECT=str_replace("'","\'",$SUBJECT);
        $SMS_CONTENT1=sprintf(_("您提交的讨论区帖子通知，标题：%s 审批未通过。"),csubstr($SUBJECT,0,100));
        send_sms($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID,18,$SMS_CONTENT1,$REMIND_URL,$BOARD_ID);
    }
    echo "<script>history.go(-1)</script>";
}
else
{
    $query="update BBS_COMMENT set IS_CHECK='1' where COMMENT_ID in ($DELETE_STR) AND BOARD_ID='$BOARD_ID'";
    exequery(TD::conn(),$query);
    $query="select * from BBS_COMMENT where COMMENT_ID in ($DELETE_STR) AND BOARD_ID='$BOARD_ID'";
    $cursor= exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $USER_ID=$ROW["USER_ID"];
        $SUBJECT=$ROW["SUBJECT"];
        $AUTHOR_NAME=$ROW["AUTHOR_NAME"];
        $COMMENT_ID=$ROW["COMMENT_ID"];
        $FROM_USER=$ROW["FROM_USER"];
        $AUTHOR_NAME_TMEP=$ROW["AUTHOR_NAME_TMEP"];
        $NICK_NAME=$ROW["NICK_NAME"];
        $PARENT=$ROW["PARENT"];
        $TO_ID_STR1=$ROW["TO_ID_STR1"];
        $TO_ID_STR2=$ROW["TO_ID_STR2"];
        $SUBMIT_TIME=$ROW["SUBMIT_TIME"];

        $query_name="select USER_NAME from USER where USER_ID='$USER_ID'";
        $cursor_name=exequery(TD::conn(),$query_name);
        if($ROW=mysql_fetch_array($cursor_name))
            $USER_NAME=$ROW["USER_NAME"];
        $SUBJECT=str_replace("'","\'",$SUBJECT);
        $SMS_CONTENT=_("请查看讨论区通知！")."\n"._("标题：").csubstr($SUBJECT,0,100);
        $REMIND_URL="1:bbs/check_comment.php?COMMENT_ID=".$COMMENT_ID."&BOARD_ID=".$BOARD_ID."";
        $SMS_CONTENT1=sprintf(_("您提交的讨论区帖子通知，标题：%s 审批已通过。"),csubstr($SUBJECT,0,100));
        send_sms($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID,18,$SMS_CONTENT1,$REMIND_URL,$BOARD_ID);

        if ($PARENT!="0")
        {
            $COMMENT_ID=$PARENT;
            $sql="SELECT SUBJECT FROM BBS_COMMENT WHERE COMMENT_ID='$PARENT'";
            $cursor_sql=exequery(TD::conn(), $sql);
            if ($ROW_SQL=mysql_fetch_array($cursor_sql));
            $SUBJECT=$ROW_SQL["SUBJECT"];
        }

        $REMIND_URL2="bbs/comment.php?BOARD_ID=".$BOARD_ID."&COMMENT_ID=".$COMMENT_ID."&PAGE_START=1";

        if($PARENT=="0")
        {
            if($AUTHOR_NAME_TMEP=="1")
                $MSG_CONTENT = sprintf(_("%s发了新贴，主题为《%s》"),$AUTHOR_NAME,$SUBJECT);
            else
                $MSG_CONTENT = sprintf(_("昵称：%s发了新贴，主题为《%s》"),$NICK_NAME,$SUBJECT);
        }else{
            if($AUTHOR_NAME_TMEP=="1")
                $MSG_CONTENT =sprintf(_("《%s》得到回复，请查看！"),$SUBJECT);
            else
                $MSG_CONTENT = sprintf(_("昵称：%s回复了《%s》，请查看！"),$NICK_NAME,$SUBJECT);
        }

        if($TO_ID_STR1!="")
            send_sms("",$FROM_USER,$TO_ID_STR1,18,$MSG_CONTENT,$REMIND_URL2,$BOARD_ID);
        if($TO_ID_STR2!="")
            send_mobile_sms_user("",$FROM_USER,$TO_ID_STR2,$MSG_CONTENT,18);


        if($SMS_REMIND=="on")
        {
            if($USER_ID_STR!="")
                send_sms($SEND_TIME,$FROM_ID,$USER_ID_STR,18,$SMS_CONTENT,$REMIND_URL,$BOARD_ID);
        }

        if($SMS2_REMIND=="on")
        {
            $SMS_CONTENT=sprintf(_("OA公告,来自%s:%s"),$USER_NAME,$SUBJECT);
            if($USER_ID_STR!="")
                send_mobile_sms_user($SEND_TIME,$FROM_ID,$USER_ID_STR,$SMS_CONTENT,1);
        }
    }
    header("location:check_manage.php?BOARD_ID=$BOARD_ID&IS_MAIN=1");
//echo "<script>history.go(-1)</script>";
}
?>
<body>