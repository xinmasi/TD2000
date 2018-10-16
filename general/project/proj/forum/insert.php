<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_all.php");
$CUR_TIME=date("Y-m-d H:i:s",time());

//------ 回复文章，增加被回复文章的回复计数 ------
if($REPLY=="1")
{
    $MSG_ID = intval($MSG_ID);
    $query="update PROJ_FORUM set REPLY_CONT=REPLY_CONT+1,SUBMIT_TIME='$CUR_TIME' where MSG_ID=".$MSG_ID;
    exequery(TD::conn(), $query);
}

//--------- 上传附件 ----------
if(count($_FILES)>1)
{
    $ATTACHMENTS=upload();

    $ATTACHMENT_ID=$ATTACHMENTS["ID"];
    $ATTACHMENT_NAME=$ATTACHMENTS["NAME"];
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

//--------- 保存文章 ----------
if($REPLY=="1")
    $PARENT=$MSG_ID;
else
    $PARENT=0;

if($REPLY==1)
{
    $CONTENT = str_replace(" ","&nbsp;",$CONTENT);
    $CONTENT = str_replace("\n","<br />",$CONTENT);
}

if($SUBJECT=="")
    $SUBJECT=_("无标题");
$query="insert into PROJ_FORUM(PROJ_ID, USER_ID, SUBJECT, CONTENT, ATTACHMENT_ID, ATTACHMENT_NAME, SUBMIT_TIME, REPLY_CONT,PARENT,OLD_SUBMIT_TIME) values ( '$PROJ_ID', '".$_SESSION["LOGIN_USER_ID"]."', '$SUBJECT', '$CONTENT', '$ATTACHMENT_ID', '$ATTACHMENT_NAME', '$CUR_TIME', 0 ,'$PARENT','$CUR_TIME')";
exequery(TD::conn(), $query);
$MSG_ID_NEW=mysql_insert_id();

if($SMS_REMIND=="on" || $SMS2_REMIND=="on")
{
    $ARRAY_PARA_VALUE = array();
    $PARA_ARRAY=get_sys_para("SMS_REMIND");
    $PARA_VALUE=$PARA_ARRAY["SMS_REMIND"];
    $ARRAY_PARA_VALUE = explode("|",$PARA_VALUE);

    //短信允许提醒的串中
    $SMS_REMIND1 = $ARRAY_PARA_VALUE[2];

    //手机短信允许提醒的串中
    $SMS2_REMIND1 = $ARRAY_PARA_VALUE[1];

    $REMIND_URL="1:project/proj/forum/comment.php?PROJ_ID=".$PROJ_ID."&MSG_ID=".$MSG_ID_NEW."&PAGE_START=0";

    $query = "SELECT PROJ_USER from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
    $cursor = exequery(TD::conn(), $query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $PROJ_USER=$ROW["PROJ_USER"];
        $PROJ_USER=str_replace("|","",$PROJ_USER);

        $query1="select USER_ID from USER where NOT_LOGIN='0' AND FIND_IN_SET(USER_ID,'$PROJ_USER') AND USER_ID!='".$_SESSION["LOGIN_USER_ID"]."'";
        $cursor1=exequery(TD::conn(),$query1);
        while($ROW1=mysql_fetch_array($cursor1))
            $USER_ID_STR.=$ROW1["USER_ID"].",";
    }
    if($REPLY=="1")
    {
        $MSG_ID = intval($MSG_ID);
        $query2="select USER_ID,SUBJECT from PROJ_FORUM where MSG_ID=".$MSG_ID;
        $cursor2 = exequery(TD::conn(), $query2);
        if($ROW2=mysql_fetch_array($cursor2))
        {
            $USER_ID=$ROW2["USER_ID"];
            $SUBJECT=$ROW2["SUBJECT"];
        }

        //echo $USER_ID."--".$_SESSION["LOGIN_USER_ID"]."--".$SUBJECT."<br />";
        //echo $SMS_REMIND."--".find_id_plus($SMS_REMIND1,42,"|");
        // exit;
        if($SMS_REMIND=="on" )
        {
            send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,42,$SUBJECT._("得到回复，请查看！"),$REMIND_URL,$PROJ_ID);
        }
        else if( $_SESSION["LOGIN_USER_ID"]!=$USER_ID && find_id_plus($SMS_REMIND1,42,"|")){
            send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID,42,$SUBJECT._("得到回复，请查看！"),$REMIND_URL,$PROJ_ID);
        }
        if($SMS2_REMIND=="on"  && $_SESSION["LOGIN_USER_ID"]!=$USER_ID && find_id($SMS2_REMIND1,42))
            send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$USER_ID,$SUBJECT._("得到回复，请查看！"),42);
    }
    else
    {
        //echo $USER_ID_STR;exit;
        if($SMS_REMIND=="on")
            send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,42,$_SESSION["LOGIN_USER_NAME"]._("在项目讨论区发贴，主题为：").$SUBJECT,$REMIND_URL,$PROJ_ID);
        if($SMS2_REMIND=="on")
            send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,$_SESSION["LOGIN_USER_NAME"]._("在项目讨论区发贴，主题为：").$SUBJECT,42);
    }
}
if($REPLY=="1")
    header("location: comment.php?PROJ_ID=$PROJ_ID&MSG_ID=$MSG_ID&PAGE_START=$PAGE_START&start=$start");
else
    header("location: index.php?PROJ_ID=$PROJ_ID&PAGE_START=$PAGE_START&start=$start");
?>