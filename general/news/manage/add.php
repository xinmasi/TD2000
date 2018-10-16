<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("发布新闻");
include_once("inc/header.inc.php");
mysql_set_charset(MYOA_DB_CHARSET, TD::conn());
?>

<body class="bodycolor">

<?
if($SEND_TIME!="" && !is_date_time($SEND_TIME))
{
    Message(_("错误"),sprintf(_("发布时间格式不对，应形如%s"),date("Y-m-d H:i:s",time())));
    Button_Back();
    exit;
}

$CUR_TIME = $SEND_TIME ? $SEND_TIME : date("Y-m-d H:i:s",time());

//--------- 上传附件 ----------
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

if($FORMAT=="1")
    $CONTENT="";
$CONTENT=str_replace("http://".$HTTP_HOST."/inc/attach.php?","/inc/attach.php?",$CONTENT);
$CONTENT=str_replace("http://".$HTTP_HOST."/module/editor/plugins/smiley/images/","/module/editor/plugins/smiley/images/",$CONTENT);
$C = preg_match('/<img.*?\ssrc=\\\"\/inc\/attach.php\?(.*)MODULE=upload_temp/i',$CONTENT);
if($C==1)
{
    $CONTENT = replace_attach_url($CONTENT);
    $ATTACHMENT_ID = move_attach($ATTACHMENT_ID,$ATTACHMENT_NAME,"","").",";
}
if($TOP=="on")
{
    $TOP='1';
    if($TOP_DAYS != "0" && $TOP_DAYS != null)
    {
        $TOP_DAYS = date("Y-m-d H:i:s",(strtotime($CUR_TIME)+(86400*$TOP_DAYS)));
    }

    if($TOP_DAYS == "0" || $TOP_DAYS == null)
    {
        $TOP_DAYS = "2038-01-01 00:00:00";
    }
}
else
{
    $TOP='0';
    $TOP_DAYS = "0";
}
$CONTENT=strip_unsafe_tags($CONTENT);
//------------------- 发布新闻 -----------------------
if ($FORMAT!=2)
{
    $CONTENT = stripslashes($CONTENT);
    $COMPRESS_CONTENT = bin2hex(gzcompress($CONTENT));
    $CONTENT = addslashes(strip_tags($CONTENT));

    $query="insert into NEWS(SUBJECT,SUMMARY,CONTENT,PROVIDER,NEWS_TIME,ATTACHMENT_ID,ATTACHMENT_NAME,ANONYMITY_YN,FORMAT,TYPE_ID,PUBLISH,TO_ID,PRIV_ID,USER_ID,COMPRESS_CONTENT,TOP,TOP_DAYS,SUBJECT_COLOR,KEYWORD) values ('$SUBJECT','$SUMMARY','$CONTENT','".$_SESSION["LOGIN_USER_ID"]."','$CUR_TIME','$ATTACHMENT_ID','$ATTACHMENT_NAME','$ANONYMITY_YN','$FORMAT','$TYPE_ID','$PUBLISH','$TO_ID','$PRIV_ID','$COPY_TO_ID',0x$COMPRESS_CONTENT,'$TOP','$TOP_DAYS','$SUBJECT_COLOR','$KEYWORD')";
}
else
{
    $query="insert into NEWS(SUBJECT,SUMMARY,CONTENT,PROVIDER,NEWS_TIME,ATTACHMENT_ID,ATTACHMENT_NAME,ANONYMITY_YN,FORMAT,TYPE_ID,PUBLISH,TO_ID,PRIV_ID,USER_ID,TOP,TOP_DAYS,SUBJECT_COLOR,KEYWORD) values ('$SUBJECT','$SUMMARY','$URL_ADD','".$_SESSION["LOGIN_USER_ID"]."','$CUR_TIME','$ATTACHMENT_ID','$ATTACHMENT_NAME','$ANONYMITY_YN','$FORMAT','$TYPE_ID','$PUBLISH','$TO_ID','$PRIV_ID','$COPY_TO_ID','$TOP','$TOP_DAYS','$SUBJECT_COLOR','$KEYWORD')";
}

exequery(TD::conn(),$query);
$NEWS_ID=mysql_insert_id();
if($PUBLISH=="1" && ($SMS_REMIND=="on" || $SMS2_REMIND=="on"))
{
    $query="select USER_ID from USER where (NOT_LOGIN = 0 or NOT_MOBILE_LOGIN = 0)";
    if($TO_ID!="ALL_DEPT")
        $query.=" and (find_in_set(DEPT_ID,'$TO_ID') or find_in_set(USER_PRIV,'$PRIV_ID') or find_in_set(USER_ID,'$COPY_TO_ID'))";

    $cursor=exequery(TD::conn(),$query);

    while($ROW=mysql_fetch_array($cursor))
        $USER_ID_STR.=$ROW["USER_ID"].",";
//辅助角色
    $MY_ARRAY=explode(",",$PRIV_ID);
    $ARRAY_COUNT=sizeof($MY_ARRAY);
    for($I=0;$I<$ARRAY_COUNT;$I++)
    {
        if($MY_ARRAY[$I]=="")
            continue;
        $query="select USER_ID from USER where (NOT_LOGIN = 0 or NOT_MOBILE_LOGIN = 0) and find_in_set('$MY_ARRAY[$I]',USER_PRIV_OTHER)";
        $cursor=exequery(TD::conn(),$query);
        while($ROW=mysql_fetch_array($cursor))
        {
            if(!find_id($USER_ID_STR,$ROW["USER_ID"]))
                $USER_ID_STR.=$ROW["USER_ID"].",";
        }
    }
    //辅助部门
    $MY_ARRAY_DEPT=explode(",",$TO_ID);
    $ARRAY_COUNT_DEPT=sizeof($MY_ARRAY_DEPT);
    for($I=0;$I<$ARRAY_COUNT_DEPT;$I++)
    {
        if($MY_ARRAY_DEPT[$I]=="")
            continue;
        $query_d="select USER_ID from USER where (NOT_LOGIN = 0 or NOT_MOBILE_LOGIN = 0) and find_in_set('$MY_ARRAY_DEPT[$I]',DEPT_ID_OTHER)";
        $cursor_d=exequery(TD::conn(),$query_d);
        while($ROWD=mysql_fetch_array($cursor_d))
        {
            if(!find_id($USER_ID_STR,$ROWD["USER_ID"]))
                $USER_ID_STR.=$ROWD["USER_ID"].",";
        }
    }
}
//排除没有新闻菜单权限的人
$USER_ID_STR_ARRAY=explode(",",$USER_ID_STR);
$USER_ID_STR_ARRAY_COUNT=sizeof($USER_ID_STR_ARRAY);
for($I=0;$I<$USER_ID_STR_ARRAY_COUNT;$I++)
{
    if($USER_ID_STR_ARRAY[$I]=="")
        continue;

    $FUNC_ID_STR=GetfunmenuByuserID($USER_ID_STR_ARRAY[$I]);
    if(!find_id($FUNC_ID_STR,147))
        $USER_ID_STR=str_replace($USER_ID_STR_ARRAY[$I],'',$USER_ID_STR);

}
//.............
if($PUBLISH=="1" && $SMS_REMIND=="on")
{
    $REMIND_URL="1:news/show/read_news.php?NEWS_ID=".$NEWS_ID;
    $SMS_CONTENT=_("请查看新闻！")."\n"._("标题：").csubstr($SUBJECT,0,80);
    if(mb_detect_encoding($SMS_CONTENT,array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')) == "CP936"){
        $SMS_CONTENT = stripslashes($SMS_CONTENT);
        $SUMMARY     = stripslashes($SUMMARY);
    }
    if($SUMMARY)
        $SMS_CONTENT .= "\n"._("内容简介：") . $SUMMARY;

    $newsid = array("news_id" => $NEWS_ID);
    if($USER_ID_STR!=""){
        send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,14,$SMS_CONTENT,$REMIND_URL,$NEWS_ID);
    }
    //应该放在事务提醒里面，开启之后才能够发送提醒
    include_once("inc/itask/itask.php");
    mobile_push_notification(UserId2Uid($USER_ID_STR), $_SESSION["LOGIN_USER_NAME"]._("：")._("请查看新闻！")._("标题：").csubstr($SUBJECT,0,20), "news");
    //企业微信的事务提醒小秘书 lp 2014/10/31 5:22:05
    /*$WX_OPTIONS = array(
       "module" => "news",
       "module_action" => "news.read",
       "user" => $USER_ID_STR,
       "content" => $_SESSION["LOGIN_USER_NAME"]._("：")._("请查看新闻！")._("标题：").csubstr($SUBJECT,0,20),
       "params" => array(
            "news_id" => $NEWS_ID
        )
    );
    WXQY_NEWS($WX_OPTIONS);*/
}


if($PUBLISH=="1" && $SMS2_REMIND=="on")
{
    $SMS_CONTENT=sprintf(_("OA新闻,来自%s"),$_SESSION["LOGIN_USER_NAME"].":".$SUBJECT);
    if($USER_ID_STR!=""){
        send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,$SMS_CONTENT,14);
    }
}

if($PUBLISH=="1" && $SNS_REMIND=="on")
{
    if(mb_detect_encoding($SUBJECT,array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')) == "CP936"){
        $SUBJECT = stripslashes($SUBJECT);
    }
    $push_arr = array(
        'module' => 'news',
        'mid' => $NEWS_ID,
        'user_id' => $COPY_TO_ID,
        'priv_id' => $PRIV_ID,
        'dept_id' => $TO_ID,
        'subject' => $SUBJECT
    );
    push_to_wxshare($push_arr);
}

if($PUBLISH=="1"){
    include_once("inc/itask/itask.php");
    mobile_push_notification(UserId2Uid($USER_ID_STR), $_SESSION["LOGIN_USER_NAME"]._("：")._("请查看新闻！")._("标题：").csubstr($SUBJECT,0,20), "news");
}

if($OP==0)
    header("location:modify.php?NEWS_ID=$NEWS_ID&IS_MAIN=1");
else
{
    if($PUBLISH==1)
    {
        Message("",_("新闻发布成功！"));
        ?>
        <br><center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location.href='index1.php?start=<?=$start?>&IS_MAIN=1'"></center>
        <?
    }
    else
    {
        Message("",_("新闻保存成功！"));
        ?>
        <br><center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location.href='modify.php?NEWS_ID=<?=$NEWS_ID?>&IS_MAIN=1'"></center>
        <?
    }
    ?>
    <!--<br><center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location.href='index1.php?start=<?=$start?>&IS_MAIN=1'"></center>-->
    <?
}
?>

</body>
</html>
