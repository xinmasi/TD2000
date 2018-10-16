<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_cache.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("新闻查询结果");
include_once("inc/header.inc.php");
?>


<script>
function open_news(NEWS_ID)
{
    URL="/general/news/show/read_news.php?NEWS_ID="+NEWS_ID;
    myleft=(screen.availWidth-780)/2;
    window.open(URL,"read_news","height=500,width=780,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left="+myleft+",resizable=yes");
}

function open_urladdress(NEWS_ID)
{
    URL="../show/url_address.php?NEWS_ID="+NEWS_ID;
    myleft=(screen.availWidth-780)/2;
    window.open(URL,"read_notify","height=500,width=780,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left="+myleft+",resizable=yes");
}

function delete_news(NEWS_ID,start)
{
    msg='<?=_("确认要删除该项新闻吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete_query.php?NEWS_ID=" + NEWS_ID + "&start=" + start + "&FROM_FLAG=1";
        window.location=URL;
    }
}


function delete_all()
{
    msg='<?=_("确认要删除所有新闻吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete_all.php";
        window.location=URL;
    }
}

function re_news(NEWS_ID)
{
    URL="/general/news/show/re_news.php?NEWS_ID="+NEWS_ID+"&MANAGE=1";
    myleft=(screen.availWidth-500)/2;
    window.open(URL,"read_news","height=500,width=550,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left="+myleft+",resizable=yes");
}
</script>

<body class="bodycolor">

<?
$ROW        = GetUserInfoByUID($_SESSION["LOGIN_UID"],"POST_PRIV,POST_DEPT,DEPT_ID");
$POST_PRIV  = $ROW["POST_PRIV"];
$DEPT_ID1   = td_trim($ROW["POST_DEPT"]);
$DEPT_ID2   = $ROW["DEPT_ID"];
//只查询主部门的成员
function GetMembersFromDept($DEPT_ID1,$QUERY_MASTER=""){
    $query  = "SELECT * from USER where find_in_set(DEPT_ID,'".$DEPT_ID1."')";
    $cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
    while($ROW = mysql_fetch_array($cursor))
    {
        $USER_ID_STR .= $ROW["USER_ID"].",";
    }
    return $USER_ID_STR;
}
//$DEPT_MEMBERS ---> $DEPT_ID1 部门下所有成员
$DEPT_MEMBERS = GetMembersFromDept($DEPT_ID1,$QUERY_MASTER="");
$CUR_DATE=date("Y-m-d",time());
//----------- 合法性校验 ---------
if($SEND_TIME_MIN!="")
{
    $TIME_OK=is_date($SEND_TIME_MIN);

    if(!$TIME_OK)
    { Message(_("错误"),sprintf(_("发布日期的格式不对，应形如 %s"),$CUR_DATE));
        Button_Back();
        exit;
    }
    $SEND_TIME_MIN=$SEND_TIME_MIN." 00:00:00";
}
if($SEND_TIME_MAX!="")
{
    $TIME_OK=is_date($SEND_TIME_MAX);

    if(!$TIME_OK)
    { Message(_("错误"),sprintf(_("发布日期的格式不对，应形如 %s"),$CUR_DATE));
        Button_Back();
        exit;
    }
    $SEND_TIME_MAX=$SEND_TIME_MAX." 23:59:59";
}

//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($SUBJECT!="")
    $CONDITION_STR.=" and SUBJECT like '%".$SUBJECT."%'";
if($CONTENT!="")
    $CONDITION_STR.=" and CONTENT like '%".$CONTENT."%'";
if($SEND_TIME_MIN!="")
    $CONDITION_STR.=" and NEWS_TIME>='$SEND_TIME_MIN'";
if($TOP!="")
    $CONDITION_STR.=" and TOP='$TOP'";
if($SEND_TIME_MAX!=""){
    $CONDITION_STR.=" and NEWS_TIME<='$SEND_TIME_MAX'";
}
if($CLICK_COUNT_MIN!="")
    $CONDITION_STR.=" and CLICK_COUNT>=$CLICK_COUNT_MIN";
if($CLICK_COUNT_MAX!="")
    $CONDITION_STR.=" and CLICK_COUNT<=$CLICK_COUNT_MAX";
if($FORMAT!="")
    $CONDITION_STR.=" and FORMAT='$FORMAT'";
if($TYPE_ID!="")
    $CONDITION_STR.=" and TYPE_ID='$TYPE_ID'";
$time = date("Y-m-d H:i:s",time());
if($PUBLISH!=""){
    $CONDITION_STR.=" and ( PUBLISH='$PUBLISH'";
    if($PUBLISH == 0)
    {
        $CONDITION_STR.=" or NEWS_TIME>'$time' )";
    }elseif($PUBLISH == 1)
    {
        $CONDITION_STR.=" and NEWS_TIME<'$time' )";
    }
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("新闻查询结果")?></span><br>
        </td>
    </tr>
</table>

<?
if($_SESSION["LOGIN_USER_PRIV_TYPE"]=="1" || $POST_PRIV=="1")
{
    $query = "SELECT NEWS_ID,SUBJECT,PROVIDER,NEWS_TIME,CLICK_COUNT,TYPE_ID,ANONYMITY_YN,FORMAT,TOP,SUBJECT_COLOR from NEWS where 1=1";
}
else if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION['LOGIN_USER_PRIV_TYPE']!='1')
{
    $dept_id = get_manage_dept_ids($_SESSION['LOGIN_UID']);
    if($dept_id)
    {
        $dept_str = $dept_id;
    }
    else
    {
        $dept_str = $_SESSION["LOGIN_DEPT_ID"];
    }
    $UID = rtrim(GetUidByOther('','',$dept_str),",");
    $user_id = rtrim(GetUserIDByUid($UID),",");
    if($user_id!='')
    {
        $query = "SELECT NEWS_ID,SUBJECT,PROVIDER,NEWS_TIME,CLICK_COUNT,TYPE_ID,ANONYMITY_YN,FORMAT,TOP,SUBJECT_COLOR from NEWS where find_in_set(PROVIDER,'".$user_id."')";
    }
    else
    {
        $query = "SELECT NEWS_ID,SUBJECT,PROVIDER,NEWS_TIME,CLICK_COUNT,TYPE_ID,ANONYMITY_YN,FORMAT,TOP,SUBJECT_COLOR from NEWS where PROVIDER ='".$_SESSION["LOGIN_USER_ID"]."'";
    }
}
else
{
    if($_SESSION["LOGIN_USER_PRIV"]=="1" || $POST_PRIV=="1")
    {
        $query = "SELECT * from NEWS where 1=1";
    }else if ($POST_PRIV=="0" || $POST_PRIV=='6')
    {
        $query="SELECT * FROM NEWS left join USER on NEWS.PROVIDER=USER.USER_ID where USER.DEPT_ID='$DEPT_ID2'";
    }
    else if ($POST_PRIV=="2")
    {
        $query="SELECT * FROM NEWS  WHERE (find_in_set(PROVIDER,'$DEPT_MEMBERS') or PROVIDER='".$_SESSION["LOGIN_USER_ID"]."') ";
    }
}
$query.=$CONDITION_STR." order by TOP desc,NEWS_TIME desc";
$cursor=exequery(TD::conn(),$query);
$NEWS_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $NEWS_COUNT++;
    $NEWS_ID=$ROW["NEWS_ID"];
    $SUBJECT=$ROW["SUBJECT"];
    $PROVIDER=$ROW["PROVIDER"];
    $NEWS_TIME=$ROW["NEWS_TIME"];
    $CLICK_COUNT=$ROW["CLICK_COUNT"];
    $TYPE_ID=$ROW["TYPE_ID"];
    $ANONYMITY_YN=$ROW["ANONYMITY_YN"];
    $FORMAT=$ROW["FORMAT"];
    $TOP=$ROW["TOP"];
    $SUBJECT_COLOR=$ROW["SUBJECT_COLOR"];

    $SUBJECT_TITLE="";
    if(strlen($SUBJECT) > 50)
    {
        $SUBJECT_TITLE=$SUBJECT;
        $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
    }
    $SUBJECT=td_htmlspecialchars($SUBJECT);
    if($TOP=="1")
        $SUBJECT="<font color=red><b>".$SUBJECT."</b></font>";
    else
        $SUBJECT="<font color='".$SUBJECT_COLOR."'>".$SUBJECT."</font>";
    $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT_TITLE);

    $query1="select USER_NAME,DEPT_ID from USER where USER_ID='$PROVIDER'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
    {
        $PROVIDER_NAME=$ROW["USER_NAME"];
        $DEPT_ID=$ROW["DEPT_ID"];
    }

    $TYPE_NAME=get_code_name($TYPE_ID,"NEWS");
    $DEPT_NAME=dept_long_name($DEPT_ID);

    $query1 = "SELECT count(*) from NEWS_COMMENT where NEWS_ID='$NEWS_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    $COMMENT_COUNT=0;
    if($ROW1=mysql_fetch_array($cursor1))
        $COMMENT_COUNT=$ROW1[0];

    if($NEWS_COUNT%2==1)
        $TableLine="TableLine1";
    else
        $TableLine="TableLine2";
    
    if($NEWS_COUNT==1)
    {
?>
<table class="TableList" width="95%" align="center">
    <tr class="TableHeader">
        <td nowrap align="center"><?=_("标题")?></td>
        <td nowrap align="center"><?=_("类型")?></td>
        <td nowrap align="center"><?=_("发布人")?></td>
        <td nowrap align="center"><?=_("发布时间")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></td>
        <td nowrap align="center"><?=_("点击次数")?></td>
        <td nowrap align="center"><?=_("评论(条)")?></td>
        <td nowrap align="center"><?=_("操作")?></td>
    </tr>
    <?
    }
    if($NEWS_COUNT%2==1)
        $TableLine="TableLine1";
    else
        $TableLine="TableLine2";
    ?>
    <tr class="<?=$TableLine?>">
        <td ><a href=javascript:open_news('<?=$NEWS_ID?>'); title="<?=$SUBJECT_TITLE?>"><?=$SUBJECT?></a></td>
        <td align="center"><?=$TYPE_NAME?></td>
        <td nowrap align="center"><u title="<?=_("部门：")?><?=$DEPT_NAME?>" style="cursor:hand"><?=$PROVIDER_NAME?></u></td>
        <td nowrap align="center"><?=$NEWS_TIME?></td>
        <td nowrap align="center"><?=$CLICK_COUNT?></td>
        <td nowrap align="center"><?=$COMMENT_COUNT?></td>
        <td nowrap align="center">
            <a href="modify_query.php?NEWS_ID=<?=$NEWS_ID?>&start=<?=$start?>"> <?=_("修改")?></a>
            <a href="javascript:delete_news('<?=$NEWS_ID?>','<?=$start?>');"> <?=_("删除")?></a>
            <?
            if($ANONYMITY_YN!="2")
            {
                ?>
                <a href="javascript:re_news('<?=$NEWS_ID?>');"> <?=_("管理评论")?></a>
                <?
            }
            ?>
        </td>
    </tr>
    <?
    }
    if($NEWS_COUNT==0)
    {
        Message("",_("无符合条件的新闻"));
        //Button_Back();
        ?>
        <center><input type="button" class="BigButtonA" value="返回" onclick="location.href='query.php'"></center>
        <?
        exit;
    }
    else
    {
    ?>
</table>
    <center><input type="button" class="BigButtonA" value="返回" onclick="location.href='query.php'"></center>
<?
//Button_Back();
}

?>
</body>
</html>
