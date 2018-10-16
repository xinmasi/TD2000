<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_cache.php");
$EXCLUDE_UID_STR = td_trim(my_exclude_uid());

$TO_ID_MERGE = $TO_ID . $COPY_TO_ID . $SECRET_TO_ID;
if($EXCLUDE_UID_STR != "")
{
    $query="select USER_ID from USER where UID in ($EXCLUDE_UID_STR)";
    $cursor=exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
        $EXCLUDE_USER_ID_STR.=$ROW["USER_ID"].",";

    $TO_ID = check_id($EXCLUDE_USER_ID_STR, $TO_ID, false);
    $COPY_TO_ID = check_id($EXCLUDE_USER_ID_STR, $COPY_TO_ID, false);
    $SECRET_TO_ID = check_id($EXCLUDE_USER_ID_STR, $SECRET_TO_ID, false);
    $TO_ID_MERGE2 = $TO_ID . $COPY_TO_ID . $SECRET_TO_ID;

    //被排除掉的用户ID串
    if($TO_ID_MERGE2 != "")
        $TO_ID_MERGE_NOT = check_id($TO_ID_MERGE2, $TO_ID_MERGE, false);
    if($TO_ID_MERGE_NOT != "")
        $TO_NAME_NOT_STR = td_trim(GetUserNameById($TO_ID_MERGE_NOT));

    if ($TO_ID_MERGE_NOT != "")
    {
        if ($TO_ID == "")
        {
            $msg=sprintf(_("您不能给%s发送邮件，不在其通讯范围内"),$TO_NAME_NOT_STR);
            Message(_("提示"),$msg);

            ?>    <center>
            <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='../outbox/?BOX_ID=0&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>'">
        </center>
            <?
            exit;
        }
        else
        {
            $msg1=sprintf(_("您不能给%s发送邮件，不在其通讯范围内。其他用户邮件已发送。"),$TO_NAME_NOT_STR);
            Message(_("提示"), $msg1);
        }
    }
}

$HTML_PAGE_TITLE = _("发送邮件");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
//词语过滤
$SUBJECT=censor($SUBJECT,"0");
$CENSOR_CONTENT=censor(strip_tags($CONTENT),"0");
if($SUBJECT == "BANNED" || $CENSOR_CONTENT == "BANNED")
{
    Button_Back();
    exit;
}
else if($SUBJECT == "MOD" || $CENSOR_CONTENT == "MOD")
{
    $SEND_FLAG="0";
    $CENSOR_FLAG=1;
}

if(($REPLAY!=""||$FW!="")&&$ATTACHMENT_ID_OLD!="")
{
    $ATTACHMENT_ID_OLD=copy_attach($ATTACHMENT_ID_OLD,$ATTACHMENT_NAME_OLD,"","",true).",";
}

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
$SIZE=0;
$ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
$ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);
for($I=0;$I<sizeof($ATTACHMENT_ID_ARRAY)-1;$I++)
    $SIZE+=attach_size($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]);
//exit;

if(trim($SUBJECT)=="")
    $SUBJECT=_("[无主题]");
if($SMS_REMIND=="on")
    $SMS_REMIND="1";
else
    $SMS_REMIND="0";
//------------------- 保存或发送 -----------------------
$SEND_TIME=time();
$CONTENT = stripslashes($CONTENT);
$CONTENT_STRIP = strip_tags($CONTENT); //这个是存储的内容已经过滤html标签了
$COMPRESS_CONTENT = bin2hex(gzcompress($CONTENT));
//存储内容、压缩内容存储 edit  2012-05-29.....
$CONTENT_SIZE=strlen($CONTENT);
$CONTENT_SIZE1=strlen($CONTENT_STRIP);
$COMPRESS_CONTENT_SIZE=strlen($COMPRESS_CONTENT);
if($CONTENT_SIZE<($CONTENT_SIZE1+$COMPRESS_CONTENT_SIZE)) //如果正文大于过滤掉html标签与压缩内容之合
{
    $CONTENT_STRIP=addslashes($CONTENT);
    $COMPRESS_CONTENT="''";
}
else
{
    $CONTENT_STRIP=addslashes($CONTENT_STRIP);
    $COMPRESS_CONTENT='0x'.$COMPRESS_CONTENT;
}
$RECEIPT = $RECEIPT == "on" ? "1" : "0";

if($SEND_FLAG=="1")
{
    $SUBJECT1=$SUBJECT;
    $TO_ID2=$TO_ID;

    if($BODY_ID==""||$REPLAY!=""||$FW!="")
        $query="insert into EMAIL_BODY(FROM_ID,TO_ID2,COPY_TO_ID,SECRET_TO_ID,SUBJECT,CONTENT,SEND_TIME,ATTACHMENT_ID,ATTACHMENT_NAME,SEND_FLAG,SMS_REMIND,IMPORTANT,SIZE,FROM_WEBMAIL,TO_WEBMAIL,COMPRESS_CONTENT) values ('".$_SESSION["LOGIN_USER_ID"]."','$TO_ID2','$COPY_TO_ID','$SECRET_TO_ID','$SUBJECT','$CONTENT_STRIP','$SEND_TIME','$ATTACHMENT_ID','$ATTACHMENT_NAME','$SEND_FLAG','$SMS_REMIND','$IMPORTANT','$SIZE','$FROM_WEBMAIL','$TO_WEBMAIL',$COMPRESS_CONTENT)";
    else
        $query="update EMAIL_BODY set FROM_ID='".$_SESSION["LOGIN_USER_ID"]."',TO_ID2='$TO_ID',COPY_TO_ID='$COPY_TO_ID',SECRET_TO_ID='$SECRET_TO_ID',SUBJECT='$SUBJECT',CONTENT='$CONTENT_STRIP',SEND_TIME='$SEND_TIME',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME',SEND_FLAG='$SEND_FLAG',SMS_REMIND='$SMS_REMIND',IMPORTANT='$IMPORTANT',SIZE='$SIZE',FROM_WEBMAIL='$FROM_WEBMAIL',TO_WEBMAIL='$TO_WEBMAIL',COMPRESS_CONTENT='$COMPRESS_CONTENT' where BODY_ID='$BODY_ID'";
    exequery(TD::conn(),$query);

    if($BODY_ID==""||$REPLAY!=""||$FW!="")
        $BODY_ID=mysql_insert_id();

//ECHO $TO_ID." HHH<br>";
    $TO_ID_STR = $TO_ID.$COPY_TO_ID.$SECRET_TO_ID;
//echo $SEND_FLAG." SSS<br>";
//echo $TO_ID_STR." SSS<br>";
    $TOK = strtok($TO_ID_STR, ",");
    while($TOK!="")
    {
        if($TOK=="")
        {
            $TOK=strtok(",");
            continue;
        }

        $query="insert into EMAIL(TO_ID,READ_FLAG,DELETE_FLAG,BODY_ID,RECEIPT) values ('$TOK','0','0','$BODY_ID','$RECEIPT')";
        exequery(TD::conn(),$query);
        $ROW_ID=mysql_insert_id();

        if($SMS_REMIND=="1")
        {
            $REMIND_URL="email/?MAIN_URL=".urlencode("inbox/read_email/?BOX_ID=0&EMAIL_ID=".$ROW_ID."&BODY_ID=".$BODY_ID);
            $SMS_CONTENT=_("请查收我的邮件！")."\n"._("主题：").csubstr($SUBJECT1,0,100);

            send_sms("",$_SESSION["LOGIN_USER_ID"],$TOK,2,$SMS_CONTENT,$REMIND_URL,$ROW_ID);
        }
        include_once("inc/itask/itask.php");
        mobile_push_notification(UserId2Uid($TOK), $_SESSION["LOGIN_USER_NAME"]._("：")._("请查收我的邮件！")._("主题：").csubstr($SUBJECT1,0,20), "email");

        $TOK=strtok(",");
    }//while

    if($SMS2_REMIND=="on")
    {
        $SMS_CONTENT=sprintf(_("OA邮件,来自%s:%s"),$_SESSION["LOGIN_USER_NAME"],$SUBJECT1);
        send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$TO_ID_STR,$SMS_CONTENT,2);

    }
    /*
       if($EMAIL_ID!=""&&$REPLAY==""&&$FW=="")
       {
           $query="delete from EMAIL,EMAIL_BODY where EMAIL.BODY_ID=EMAIL_BODY.BODY_ID and EMAIL_ID='$EMAIL_ID'";
           exequery(TD::conn(),$query);
       }*/
    //发送Internet邮件
    if($TO_WEBMAIL!="" && $FROM_WEBMAIL!="")
    {
        $query = "SELECT * from WEBMAIL where EMAIL='$FROM_WEBMAIL'";
        $cursor= exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
        {
            $EMAIL=$ROW["EMAIL"];
            $SMTP_SERVER=$ROW["SMTP_SERVER"];
            $LOGIN_TYPE=$ROW["LOGIN_TYPE"];
            $SMTP_PASS=$ROW["SMTP_PASS"];
            $SMTP_PORT=$ROW["SMTP_PORT"];
            $SMTP_SSL=$ROW["SMTP_SSL"]=="1" ? "ssl":"";
            $EMAIL_PASS=$ROW["EMAIL_PASS"];
            $EMAIL_PASS=td_authcode($EMAIL_PASS,"DECODE");

            if($LOGIN_TYPE=="1")
                $SMTP_USER = substr($EMAIL,0,strpos($EMAIL,"@")); // SMTP username
            else
                $SMTP_USER =$EMAIL;
            if($SMTP_PASS=="yes")
                $SMTP_PASS = $EMAIL_PASS; // SMTP password
            else
                $SMTP_PASS = "";

            $ATTACHMENT="";
            $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
            $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);
            for($I=0;$I<count($ATTACHMENT_ID_ARRAY);$I++)
            {
                if($ATTACHMENT_ID_ARRAY[$I]=="")
                    continue;
                $FILENAME=attach_real_path($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]);
                if(file_exists($FILENAME))
                    $ATTACHMENT.=$FILENAME."*";
            }

            $result=send_mail($EMAIL,$TO_WEBMAIL,$SUBJECT,$CONTENT,$SMTP_SERVER,$SMTP_USER,$SMTP_PASS,true,$_SESSION["LOGIN_USER_NAME"],$REPLY_TO,$CC,$BCC,$ATTACHMENT,true,$SMTP_PORT,$SMTP_SSL);
            if($result===true)
            {
                Message(_("提示"),_("外部邮件发送成功"));
            }
            else
            {
                $BODY_ID=intval($BODY_ID);
                $query="update EMAIL_BODY set SEND_FLAG='0' where BODY_ID='$BODY_ID'";
                exequery(TD::conn(),$query);
                Message(_("外部邮件发送失败"),$result);
            }
        }
    }
}
else
{
    if($BODY_ID==""||$REPLAY!=""||$FW!="")
        $query="insert into EMAIL_BODY(FROM_ID,TO_ID2,COPY_TO_ID,SECRET_TO_ID,SUBJECT,CONTENT,SEND_TIME,ATTACHMENT_ID,ATTACHMENT_NAME,SEND_FLAG,SMS_REMIND,IMPORTANT,SIZE,FROM_WEBMAIL,TO_WEBMAIL,COMPRESS_CONTENT) values ('".$_SESSION["LOGIN_USER_ID"]."','$TO_ID','$COPY_TO_ID','$SECRET_TO_ID','$SUBJECT','$CONTENT_STRIP','$SEND_TIME','$ATTACHMENT_ID','$ATTACHMENT_NAME','$SEND_FLAG','$SMS_REMIND','$IMPORTANT','$SIZE','$FROM_WEBMAIL','$TO_WEBMAIL','$COMPRESS_CONTENT')";
    else
        $query="update EMAIL_BODY set FROM_ID='".$_SESSION["LOGIN_USER_ID"]."',TO_ID2='$TO_ID',COPY_TO_ID='$COPY_TO_ID',SECRET_TO_ID='$SECRET_TO_ID',SUBJECT='$SUBJECT',CONTENT='$CONTENT_STRIP',SEND_TIME='$SEND_TIME',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME',SEND_FLAG='$SEND_FLAG',SMS_REMIND='$SMS_REMIND',IMPORTANT='$IMPORTANT',SIZE='$SIZE',FROM_WEBMAIL='$FROM_WEBMAIL',TO_WEBMAIL='$TO_WEBMAIL',COMPRESS_CONTENT='$COMPRESS_CONTENT' where BODY_ID='$BODY_ID'";
    exequery(TD::conn(),$query);

    //需要审核
    if($BODY_ID==""||$REPLAY!=""||$FW!="")
        $BODY_ID=mysql_insert_id();
    if($CENSOR_FLAG==1)
    {
        $CENSOR_DATA["BODY_ID"]=$BODY_ID;
        $CENSOR_DATA["FROM_ID"]=$_SESSION["LOGIN_USER_ID"];
        $CENSOR_DATA["TO_ID"]=$TO_ID;
        $CENSOR_DATA["SUBJECT"]=$SUBJECT;
        $CENSOR_DATA["CONTENT"]=strip_tags($CONTENT);
        $CENSOR_DATA["SEND_TIME"]=$SEND_TIME;
        $CENSOR_DATA["RECEIPT"]=$RECEIPT;
        censor_data("0", $CENSOR_DATA);
        Button_Back();
        exit;
    }

    if($OP=="1")
    {
        ?>
        <script>
        if(parent.mail_menu)
            parent.mail_menu.location.reload();
        location="index.php?BODY_ID=<?=$BODY_ID?>";
        </script>
        <?
        exit;
    }
}

if($SEND_FLAG==1 && $TO_ID_STR!="")
    Message(_("提示"),_("内部邮件已发送"));
?>

<br>
<div align=center>
<?
if($SEND_FLAG==1 && $BODY_ID!="")
{
    ?>
    <script>
    if(parent.mail_menu)
        parent.mail_menu.location.reload();
    </script>
<input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='../outbox/?BOX_ID=0&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>'">
<?
}
else
{
?>
    <script>
    if(parent.mail_menu)
        parent.mail_menu.location.reload();
    window.location="../outbox/?BOX_ID=0&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>";
    </script>
    <?
}
?>
</div>
</body>
</html>