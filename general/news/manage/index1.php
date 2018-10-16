<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_cache.php");

//2013-04-11
if($IS_MAIN==1)
    $QUERY_MASTER="true";
else
    $QUERY_MASTER="";


if(!isset($TYPE))
    $TYPE="0";
if(!$PAGE_SIZE)
    $PAGE_SIZE = get_page_size("NEWS", 10);
if(!isset($start) || $start=="")
    $start=0;

$HTML_PAGE_TITLE = _("新闻管理");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script>
function open_news(NEWS_ID,FORMAT)
{
    URL="../show/read_news.php?NEWS_ID="+NEWS_ID;
    myleft=(screen.availWidth-780)/2;
    mytop=100
    mywidth=780;
    myheight=500;
    if(FORMAT=="1")
    {
        myleft=0;
        mytop=0
        mywidth=screen.availWidth-10;
        myheight=screen.availHeight-40;
    }
    window.open(URL,"read_news","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}


function delete_all()
{
    msg='<?=_("确认要删除所有新闻吗？")?>\n<?=_("删除后将不可恢复，确认删除请输入大写字母“OK”")?>';
    if(window.prompt(msg,"") == "OK")
    {
        URL="delete_all.php";
        window.location=URL;
    }
}

function re_news(NEWS_ID)
{
    URL="/general/news/show/re_news.php?NEWS_ID="+NEWS_ID+"&MANAGE=1&IS_MAIN=<?=$IS_MAIN?>";
    myleft=(screen.availWidth-500)/2;
    window.open(URL,"read_news","height=500,width=550,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left="+myleft+",resizable=yes");
}
function order_by(field,asc_desc)
{
    window.location="index1.php?start=<?=$start?>&TYPE=<?=$TYPE?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}

function change_type(type)
{
    window.location="index1.php?start=<?=$start?>&TYPE="+type+"&FIELD=<?=$FIELD?>&ASC_DESC=<?=$ASC_DESC?>";
}
jQuery(function(){
    jQuery("#allbox_for").click(function(){
        if(jQuery("#allbox_for").is(":checked"))
        {
            jQuery("[name='email_select']").attr("checked",'true');
        }
        else
        {
            jQuery("[name='email_select']").removeAttr("checked");
        }
    })

    jQuery("[name='email_select']").click(function(){
        jQuery("#allbox_for").removeAttr("checked");
    })
});

function get_checked()
{
    checked_str="";

    jQuery("input[name='email_select']:checkbox").each(function(){
        if(jQuery(this).attr("checked")){
            checked_str += jQuery(this).val()+","
        }
    })

    return checked_str;
}

function show_reader(NEWS_ID)
{
    select_str = get_checked();
    if(select_str=="")
    {
        alert("<?=_("请至少选择其中一条")?>");
        return;
    }
    else
    {
        var strs= new Array();
        strs=select_str.split(",");
        if(strs.length > 2)
        {
            alert("<?=_("只能选择一条。")?>");
            return;
        }
    }
    email_id = select_str.substring(0,select_str.length-1);

    URL="show_reader.php?NEWS_ID="+email_id;
    myleft=(screen.availWidth-500)/2;
    window.open(URL,"read_news","height=500,width=700,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

function delete_mail()
{
    delete_str=get_checked();

    if(delete_str=="")
    {
        alert("<?=_("要删除新闻，请至少选择其中一条。")?>");
        return;
    }

    msg='<?=_("确认要删除所选新闻吗？")?>';
    if(window.confirm(msg))
    {
        url="delete.php?DELETE_STR="+ delete_str +"&start=<?=$start?>";
        location=url;
    }
}
function delete_news(news_id)
{
    msg='<?=_("确认要删除该项新闻吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?DELETE_STR=" + news_id + "&start=<?=$start?>";
        window.location=URL;
    }
}
function cancel_top()
{
    delete_str=get_checked();

    if(delete_str=="")
    {
        alert("<?=_("要取消新闻置顶，请至少选择其中一条。")?>");
        return;
    }

    msg='<?=_("确认要取消所选新闻的置顶吗？")?>';
    if(window.confirm(msg))
    {
        url="notop.php?DELETE_STR="+ delete_str +"&start=<?=$start?>";
        location=url;
    }
}


function over_all()
{
    delete_str=get_checked();

    if(delete_str=="")
    {
        alert("<?=_("要终止新闻，请至少选择其中一条。")?>");
        return;
    }

    msg='<?=_("确认要终止所选新闻吗？")?>';
    if(window.confirm(msg))
    {
        url="over.php?DELETE_STR="+ delete_str +"&ISEND=1&start=<?=$start?>&TYPE=<?=$TYPE?>";
        location=url;
    }
}

function re_all()
{
    delete_str=get_checked();

    if(delete_str=="")
    {
        alert("<?=_("要生效新闻，请至少选择其中一条。")?>");
        return;
    }

    msg='<?=_("确认要生效所选新闻吗？")?>';
    if(window.confirm(msg))
    {
        url="over.php?DELETE_STR="+ delete_str +"&start=<?=$start?>&TYPE=<?=$TYPE?>";
        location=url;
    }
}
</script>


<body class="bodycolor">

<?
//$POST_PRIV=GetUserInfoByUID($_SESSION["LOGIN_UID"],"POST_PRIV");
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
if(!isset($TOTAL_ITEMS))
{
    if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION['LOGIN_USER_PRIV_TYPE']!='1'){
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
            $query = "SELECT count(*) from NEWS where find_in_set(PROVIDER,'".$user_id."')";
        }
        else
        {
            $query = "SELECT count(*) from NEWS where PROVIDER ='".$user_id."'";
        }
    }else{
        if($_SESSION["LOGIN_USER_PRIV"]=="1" || $POST_PRIV=="1")
        {
            $query = "SELECT count(*) from NEWS where 1=1";
        }else if ($POST_PRIV=="0" || $POST_PRIV=='6')
        {
            $query="SELECT count(*) FROM NEWS left join USER on NEWS.PROVIDER=USER.USER_ID where USER.DEPT_ID='$DEPT_ID2'";
        }
        else if ($POST_PRIV=="2")
        {
            $query="SELECT count(*) FROM NEWS  WHERE  find_in_set(PROVIDER,'$DEPT_MEMBERS') or PROVIDER='".$_SESSION["LOGIN_USER_ID"]."'";
        }
    }
    if($TYPE!="0")
    {
        $query .= " and TYPE_ID='$TYPE'";
    }
    $cursor = exequery(TD::conn(),$query,$QUERY_MASTER);
    $TOTAL_ITEMS = 0;
    if($ROW = mysql_fetch_array($cursor))
    {
        $TOTAL_ITEMS=$ROW[0];
    }
}
if($TOTAL_ITEMS==0)
{
    ?>
    <table border="0" width="95%" cellspacing="0" cellpadding="3" class="small" align="center">
        <tr>
            <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理新闻")?></span>&nbsp;
                <select name="TYPE" class="BigSelect" onChange="change_type(this.value);">
                    <option value="0"<?if($TYPE=="0") echo " selected";?>><?=_("所有类型")?></option>
                    <?=code_list("NEWS",$TYPE)?>
                    <option value=""<?if($TYPE=="") echo " selected";?>><?=_("无类型")?></option>
                </select>
            </td>
        </tr>
    </table>
    <br>

    <?
    Message("",_("无可管理的新闻"));
    exit;
}
?>

<table border="0" width="95%" cellspacing="0" cellpadding="3" class="small" align="center">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理新闻")?></span>&nbsp;
            <select name="TYPE" class="BigSelect" onChange="change_type(this.value);">
                <option value="0"<?if($TYPE=="0") echo " selected";?>><?=_("所有类型")?></option>
                <?=code_list("NEWS",$TYPE)?>
                <option value=""<?if($TYPE=="") echo " selected";?>><?=_("无类型")?></option>
            </select>
        </td>
        <td align="right" valign="bottom" class="small1"><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE)?></td>
    </tr>
</table>
<?
if($ASC_DESC=="")
    $ASC_DESC="1";
if($FIELD=="")
    $FIELD="NEWS_TIME";
/*************************查询置顶*******************************/
if($_SESSION["LOGIN_USER_PRIV"]=="1"||$POST_PRIV=="1"){
    $query = "SELECT TOP,TOP_DAYS,NEWS_ID from NEWS where 1=1";
}else{
    if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION['LOGIN_USER_PRIV_TYPE']!='1')
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
            $query = "SELECT TOP,TOP_DAYS,NEWS_ID from NEWS where find_in_set(PROVIDER,'".$user_id."')";
        }
        else
        {
            $query = "SELECT TOP,TOP_DAYS,NEWS_ID from NEWS where PROVIDER='".$_SESSION["LOGIN_USER_ID"]."'";
        }
    }
    else
    {
        if($_SESSION["LOGIN_USER_PRIV"]=="1" || $POST_PRIV=="1")
        {
            $query = "SELECT TOP,TOP_DAYS,NEWS_ID from NEWS where 1=1";
        }else if ($POST_PRIV=="0" || $POST_PRIV=='6')
        {
            $query="SELECT TOP,TOP_DAYS,NEWS_ID FROM NEWS left join USER on NEWS.PROVIDER=USER.USER_ID where USER.DEPT_ID='$DEPT_ID2'";
        }
        else if ($POST_PRIV=="2")
        {
            $query="SELECT TOP,TOP_DAYS,NEWS_ID FROM NEWS  WHERE  find_in_set(PROVIDER,'$DEPT_MEMBERS') or PROVIDER='".$_SESSION["LOGIN_USER_ID"]."'";
        }
    }
}
$cursor = exequery(TD::conn(),$query);
while($row = mysql_fetch_array($cursor)){
    $NEWS_ID = $row["NEWS_ID"];
    $TOP=$row["TOP"];
    $TOP_DAYS = $row["TOP_DAYS"];
    if($TOP_DAYS != "0" && strtotime($TOP_DAYS) < time()){
        $sql = "update NEWS set TOP='0' WHERE NEWS_ID='$NEWS_ID'";
        exequery(TD::conn(),$sql);
        $TOP = "0";
    }
}
//============================ 显示已发布新闻 =======================================
if($_SESSION["LOGIN_USER_PRIV"]=="1"||$POST_PRIV=="1"){
    $query = "SELECT * from NEWS where 1=1";
}else{
    if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION['LOGIN_USER_PRIV_TYPE']!='1')
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
            $query = "SELECT * from NEWS where find_in_set(PROVIDER,'".$user_id."')";
        }
        else
        {
            $query = "SELECT * from NEWS where PROVIDER='".$_SESSION["LOGIN_USER_ID"]."'";
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
            $query="SELECT * FROM NEWS  WHERE  find_in_set(PROVIDER,'$DEPT_MEMBERS') or PROVIDER='".$_SESSION["LOGIN_USER_ID"]."'";
        }
    }
}
if($TYPE!="0")
    $query.= " and TYPE_ID='$TYPE'";

if($FIELD=="")
    $query.= " order by TOP desc,NEWS_TIME desc";
else
{
    $query.= " order by TOP desc, $FIELD";
    if($ASC_DESC=="1")
        $query .= " desc";
    else
        $query .= " asc";
}

$query .= " limit $start,$PAGE_SIZE";
if($ASC_DESC=="0")
    $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
else
    $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";
?>

<table class="TableList" width="95%" align="center">
    <tr class="TableHeader">
        <td nowrap align="center"><?=_("选择")?></td>
        <td nowrap align="center"><?=_("发布人")?></td>
        <td nowrap align="center"><?=_("类型")?></td>
        <td nowrap align="center"><?=_("发布范围")?></td>
        <td nowrap align="center" onClick="order_by('SUBJECT','<?if($FIELD=="SUBJECT") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("标题")?></u><?if($FIELD=="SUBJECT") echo $ORDER_IMG;?></td>
        <td nowrap align="center" onClick="order_by('NEWS_TIME','<?if($FIELD=="NEWS_TIME"||$FIELD=="") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("发布时间")?></u><?if($FIELD=="NEWS_TIME"||$FIELD=="") echo $ORDER_IMG;?></td>
        <td nowrap align="center" onClick="order_by('CLICK_COUNT','<?if($FIELD=="CLICK_COUNT") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("点击数")?></u><?if($FIELD=="CLICK_COUNT") echo $ORDER_IMG;?></td>
        <td nowrap align="center"><?=_("评论(条)")?></td>
        <td nowrap align="center" onClick="order_by('PUBLISH','<?if($FIELD=="PUBLISH") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("状态")?></u><?if($FIELD=="PUBLISH") echo $ORDER_IMG;?></td>
        <td nowrap align="center"><?=_("操作")?></td>
    </tr>

    <?
    $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);

    $POSTFIX = _("，");
    $NEWS_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $NEWS_COUNT++;

        $NEWS_ID=$ROW["NEWS_ID"];
        $SUBJECT=$ROW["SUBJECT"];
        $PROVIDER=$ROW["PROVIDER"];
        $NEWS_TIME=$ROW["NEWS_TIME"];
        $CLICK_COUNT=$ROW["CLICK_COUNT"];
        $FORMAT=$ROW["FORMAT"];
        $TYPE_ID=$ROW["TYPE_ID"];
        $PUBLISH=$ROW["PUBLISH"];
        $ANONYMITY_YN=$ROW["ANONYMITY_YN"];
        $TO_ID=$ROW["TO_ID"];
        $PRIV_ID=$ROW["PRIV_ID"];
        $USER_ID=$ROW["USER_ID"];
        $ISEND=$ROW["ISEND"];
        $TOP=$ROW["TOP"];
        $SUBJECT_COLOR=$ROW["SUBJECT_COLOR"];
        $SUBJECT_TITLE="";
        if(strlen($SUBJECT) > 50)
        {
            $SUBJECT_TITLE=$SUBJECT;
            $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
        }
        $SUBJECT=td_htmlspecialchars($SUBJECT);
        $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT_TITLE);

        $ROW            = GetUserInfoByUID(UserId2Uid($PROVIDER),"USER_NAME,DEPT_ID");
        $PROVIDER_NAME  = isset($ROW["USER_NAME"])?$ROW["USER_NAME"]:"";
        $DEPT_ID        = isset($ROW["DEPT_ID"])?$ROW["DEPT_ID"]:"";


        $TO_NAME="";
        if($TO_ID=="ALL_DEPT")
            $TO_NAME=_("全体部门");
        else
            $TO_NAME=GetDeptNameById($TO_ID);

        $PRIV_NAME="";
        $PRIV_NAME=GetPrivNameById($PRIV_ID);

        $USER_NAME="";
        $query1 = "SELECT USER_NAME from USER where find_in_set(USER_ID,'$USER_ID')";
        $cursor1= exequery(TD::conn(),$query1);
        while($ROW=mysql_fetch_array($cursor1))
            $USER_NAME.=$ROW["USER_NAME"].$POSTFIX;

        $TO_NAME_TITLE="";
        $TO_NAME_STR="";
        if($TO_NAME!="")
        {
            if(substr($TO_NAME,-strlen($POSTFIX))==$POSTFIX)
                $TO_NAME=substr($TO_NAME,0,-strlen($POSTFIX));
            $TO_NAME_TITLE.=_("部门：").$TO_NAME;
            //中文字符串截取函数csubstr
            $TO_NAME_STR.="<font color=#0000FF><b>"._("部门：")."</b></font>".csubstr(strip_tags($TO_NAME),0,20).(strlen($TO_NAME)>20?"...":"")."<br>";
        }

        if($PRIV_NAME!="")
        {
            if(substr($PRIV_NAME,-strlen($POSTFIX))==$POSTFIX)
                $PRIV_NAME=substr($PRIV_NAME,0,-strlen($POSTFIX));
            if($TO_NAME_TITLE!="")
                $TO_NAME_TITLE.="\n\n";
            $TO_NAME_TITLE.=_("角色：").$PRIV_NAME;
            $TO_NAME_STR.="<font color=#0000FF><b>"._("角色：")."</b></font>".csubstr(strip_tags($PRIV_NAME),0,20).(strlen($PRIV_NAME)>20?"...":"")."<br>";
        }

        if($USER_NAME!="")
        {
            if(substr($USER_NAME,-strlen($POSTFIX))==$POSTFIX)
                $USER_NAME=substr($USER_NAME,0,-strlen($POSTFIX));
            if($TO_NAME_TITLE!="")
                $TO_NAME_TITLE.="\n\n";
            $TO_NAME_TITLE.=_("人员：").$USER_NAME;
            $TO_NAME_STR.="<font color=#0000FF><b>"._("人员：")."</b></font>".csubstr(strip_tags($USER_NAME),0,20).(strlen($USER_NAME)>20?"...":"")."<br>";
        }

        $TYPE_NAME=get_code_name($TYPE_ID,"NEWS");
        $DEPT_NAME=dept_long_name($DEPT_ID);

        if($PUBLISH=="0")
        {
            $PUBLISH_DESC="<font color=red>"._("未发布")."</font>";

        }
        if($PUBLISH=="2")
        {
            $PUBLISH_DESC="<font color=blue>"._("已终止")."</font>";
            $strTemp="<a href=\"manage.php?NEWS_ID=".$NEWS_ID."&start=".$start."&IS_MAIN=".$IS_MAIN."\">"._("生效")."</a>";
        }
        if($PUBLISH=="1")
        {
            $PUBLISH_DESC="<font color=green>"._("生效")."</font>";
            $strTemp="<a href=\"manage.php?NEWS_ID=".$NEWS_ID."&ISEND=1&start=".$start."&IS_MAIN=".$IS_MAIN."\">"._("终止")."</a>";
        }
        $query1 = "SELECT count(*) from NEWS_COMMENT where NEWS_ID='$NEWS_ID'";
        $cursor1= exequery(TD::conn(),$query1);
        $COMMENT_COUNT=0;
        if($ROW1=mysql_fetch_array($cursor1))
            $COMMENT_COUNT=$ROW1[0];

        if($TOP=="1")
            $SUBJECT="<font color=red><b>".$SUBJECT."</b></font>";
        else
            $SUBJECT="<font color='".$SUBJECT_COLOR."'>".$SUBJECT."</font>";

        if($NEWS_COUNT%2==1)
            $TableLine="TableLine1";
        else
            $TableLine="TableLine2";
        ?>
        <tr class="<?=$TableLine?>">
            <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$NEWS_ID?>" >
            <td nowrap align="center"><u title="<?=_("部门：")?><?=$DEPT_NAME?>" style="cursor:hand"><?=$PROVIDER_NAME?></u></td>
            <td nowrap align="center"><?=$TYPE_NAME?></td>
            <td style="cursor:hand" title="<?=$TO_NAME_TITLE?>"><?=$TO_NAME_STR?></td>
            <td><a href=javascript:open_news('<?=$NEWS_ID?>','<?=$FORMAT?>'); title="<?=$SUBJECT_TITLE?>"><?=$SUBJECT?></a></td>
            <td align="center"><?=$NEWS_TIME?></td>
            <td nowrap align="center"><?=$CLICK_COUNT?></td>
            <td nowrap align="center"><?=$COMMENT_COUNT?></td>
            <td nowrap align="center"><?=$PUBLISH_DESC?></td>
            <td align="center">
                <a href="modify.php?NEWS_ID=<?=$NEWS_ID?>&start=<?=$start?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("修改")?></a>
                <?
                if($ANONYMITY_YN!="2" && $PUBLISH!="0")
                {
                    ?>
                    <a href="javascript:re_news('<?=$NEWS_ID?>');"><? if($PUBLISH==1){?> <?=_("管理评论")?><?}else{?> <?=_("查看评论")?><?}?></a>
                    <?
                }
                echo $strTemp;
                if( $_SESSION["LOGIN_USER_PRIV_TYPE"] == "1" || $PROVIDER == $_SESSION["LOGIN_USER_ID"]){
                    ?>
                    <a href="javascript:delete_news('<?=$NEWS_ID.","?>')"><?=_("删除")?></a>
                <?}?>
            </td>
        </tr>
        <?
    }
    ?>

    <tr class="TableControl">
        <td colspan="19">
            <input type="checkbox" name="allbox" id="allbox_for" ><label for="allbox_for"><?=_("全选")?></label> &nbsp;
            <?if($_SESSION["LOGIN_USER_PRIV_TYPE"] == 1 ){?>
                <a href="javascript:delete_mail();" title="<?=_("删除所选新闻")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除所选新闻")?></a>
                &nbsp;<?}?>
            <a href="javascript:show_reader();" title="<?=_("查阅情况")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/user_group.gif" align="absMiddle"><?=_("查阅情况")?></a>&nbsp;
            <a href="javascript:cancel_top();" title="<?=_("取消所选新闻置顶")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/cancel_top.gif" align="absMiddle"><?=_("取消置顶")?></a>&nbsp;
            <?if($_SESSION["LOGIN_USER_PRIV_TYPE"] == 1){?>
                <a href="javascript:delete_all();" title="<?=_("删除所有已发布的新闻")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除全部新闻")?></a>&nbsp;
            <?}?>
            <a href="javascript:over_all();" title="<?=_("终止所选的新闻")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/email_delete.gif" align="absMiddle"><?=_("终止所选新闻")?></a>&nbsp;
            <a href="javascript:re_all();" title="<?=_("生效所选的新闻")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/correct.gif" align="absMiddle"><?=_("生效所选新闻")?></a>&nbsp;
        </td>
    </tr>

</table>

</body>
</html>
