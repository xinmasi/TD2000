<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
//2013-04-11 主服务器查询
if($IS_MAIN==1)
    $QUERY_MASTER=true;
else
    $QUERY_MASTER="";
if(!$PAGE_SIZE)
    $PAGE_SIZE = get_page_size("VOTE", 15);
if(!isset($start))
    $start=0;
if ($start=="")
    $start=0;

$HTML_PAGE_TITLE = _("投票管理");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script>
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
function show_reader(VOTE_ID)
{
    URL="show_reader.php?VOTE_ID="+VOTE_ID+"&IS_MAIN=<?=$IS_MAIN?>";
    myleft=(screen.availWidth-780)/2;
    window.open(URL,"read_vote","height=500,width=780,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

function delete_vote(VOTE_ID,start)
{
    msg='<?=_("确认要删除该投票吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?VOTE_ID=" + VOTE_ID + "&start=" + start;
        window.location=URL;
    }
}


function delete_all()
{
    msg='<?=_("确认要删除所有投票吗？")?>\n<?=_("删除后将不可恢复，确认删除请输入大写字母“OK”")?>';
    if(window.prompt(msg,"") == "OK")
    {
        URL="delete_all.php";
        window.location=URL;
    }
}
function get_checked()
{
    checked_str="";
    jQuery("input[name='email_select']:checkbox").each(function(){
        if(jQuery(this).attr("checked"))
        {
            checked_str +=jQuery(this).val()+',';
        }
    })
    return checked_str;
}
function delete_mail()
{
    delete_str=get_checked();

    if(delete_str=="")
    {
        alert("<?=_("要删除投票，请至少选择其中一条。")?>");
        return;
    }

    msg='<?=_("确认要删除所选投票吗？")?>';
    if(window.confirm(msg))
    {
        url="delete.php?DELETE_STR="+ delete_str +"&start=<?=$start?>";
        location=url;
    }
}
function clear_vote()
{
    delete_str=get_checked();

    if(delete_str=="")
    {
        alert("<?=_("要清空数据，请至少选择其中一条。")?>");
        return;
    }

    msg='<?=_("确认要清空所选投票数据吗？")?>';
    if(window.confirm(msg))
    {
        location="clear.php?DELETE_STR="+ delete_str +"&start=<?=$start?>";
    }
}
function clone()
{
    delete_str=get_checked();

    if(delete_str=="")
    {
        alert("<?=_("要复制投票，请至少选择其中一条。")?>");
        return;
    }

    msg='<?=_("确认要复制所选投票吗？")?>';
    if(window.confirm(msg))
    {
        location="clone.php?DELETE_STR="+ delete_str +"&start=<?=$start?>";
    }
}

function release(VOTE_ID,start)
{
    msg='<?=_("确认要发布该投票吗？")?>';
    if(window.confirm(msg))
    {
        url="manage.php?VOTE_ID="+VOTE_ID+"&OPERATION=9&start="+start+"&IS_MAIN=<?=$IS_MAIN?>";
        location=url;
    }
}

function cancel_top()
{
    delete_str="";
    for(i=0;i<document.getElementsByName("email_select").length;i++)
    {

        el=document.getElementsByName("email_select").item(i);
        if(el.checked)
        {  val=el.value;
            delete_str+=val + ",";
        }
    }

    if(i==0)
    {
        el=document.getElementsByName("email_select");
        if(el.checked)
        {  val=el.value;
            delete_str+=val + ",";
        }
    }

    if(delete_str=="")
    {
        alert("<?=_("要取消投票置顶，请至少选择其中一条。")?>");
        return;
    }

    msg='<?=_("确认要取消所选投票的置顶吗？")?>';
    if(window.confirm(msg))
    {
        url="notop.php?DELETE_STR="+ delete_str +"&start=<?=$start?>";
        location=url;
    }
}
</script>


<body class="bodycolor">
<?
if($_SESSION["LOGIN_USER_PRIV"]=="1") {
    $query = "SELECT * from VOTE_TITLE where PARENT_ID=0 order by TOP desc,BEGIN_DATE desc";
}
else if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]!="1")
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
        $query = "SELECT * from VOTE_TITLE where PARENT_ID=0 and FIND_IN_SET(FROM_ID,'".$user_id."') order by TOP desc,BEGIN_DATE,SEND_TIME desc";
    }
    else
    {
        $query = "SELECT * from VOTE_TITLE where PARENT_ID=0 and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."' order by TOP desc,BEGIN_DATE,SEND_TIME desc";
    }
}else{
    $query = "SELECT * from VOTE_TITLE where PARENT_ID=0 and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."' order by TOP desc,BEGIN_DATE,SEND_TIME desc";
}
$query .= " limit $start,$PAGE_SIZE";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if(!isset($TOTAL_ITEMS))
{
    if($_SESSION["LOGIN_USER_PRIV"]=="1") {
        $query1 = "SELECT count(*) from VOTE_TITLE where PARENT_ID=0 order by SEND_TIME desc";
    }
    else if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]!="1")
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
            $query1 = "SELECT count(*) from VOTE_TITLE where PARENT_ID=0 and FIND_IN_SET(FROM_ID,'".$user_id."') order by BEGIN_DATE,SEND_TIME desc";
        }
        else
        {
            $query1 = "SELECT count(*) from VOTE_TITLE where PARENT_ID=0 and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."' order by BEGIN_DATE,SEND_TIME desc";
        }
    }else{
        $query1 = "SELECT count(*) from VOTE_TITLE where PARENT_ID=0 and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."' order by BEGIN_DATE,SEND_TIME desc";
    }
    $cursor1= exequery(TD::conn(),$query1,$QUERY_MASTER);
    $TOTAL_ITEMS=0;
    if($ROW1=mysql_fetch_array($cursor1))
        $TOTAL_ITEMS=$ROW1[0];
}

if($TOTAL_ITEMS==0)
{
    ?>
    <table border="0" width="95%" cellspacing="0" cellpadding="3" class="small" align="center">
        <tr>
            <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vote.gif" align="absmiddle"><span class="big3"> <?=_("管理已发布的投票")?></span> <?=help('005','skill/erp/vote_manage');?><br>
            </td>
        </tr>
    </table>
    <br>

    <?
    Message("",_("无已发布的投票"));
    exit;
}
?>

<table border="0" width="95%" cellspacing="0" cellpadding="3" class="small" align="center">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vote.gif" align="absmiddle"><span class="big3"> <?=_("管理已发布的投票")?></span> <?=help('005','skill/erp/vote_manage');?><br>
        </td>
        <td align="right" valign="bottom" class="small1"><?=sprintf(_("共%s条"),"<span class='big4'>&nbsp;".$TOTAL_ITEMS."</span>&nbsp;");?>
        </td>
        <td align="right" valign="bottom" class="small1"><?=page_bar($start,$TOTAL_ITEMS,$PAGE_SIZE)?></td>
    </tr>
</table>

<table class="TableList" width="95%" align="center">
    <tr class="TableHeader">
        <td nowrap align="center"><?=_("选择")?></td>
        <td nowrap align="center"><?=_("发布人")?></td>
        <td nowrap align="center"><?=_("发布范围")?></td>
        <td nowrap align="center"><?=_("标题")?></td>
        <td nowrap align="center"><?=_("类型")?></td>
        <td nowrap align="center"><?=_("匿名")?></td>
        <td nowrap align="center"><?=_("生效日期")?> </td>
        <td nowrap align="center"><?=_("终止日期")?></td>
        <td nowrap align="center"><?=_("状态")?></td>
        <td nowrap align="center"><?=_("已投票/未投票")?></td>
        <td nowrap align="center"><?=_("操作")?></td>
    </tr>

    <?
    //============================ 显示投票信息 =======================================
    $CUR_DATE=date("Y-m-d H:i:s",time());
    $POSTFIX = _("，");
    $VOTE_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $VOTE_COUNT++;

        $VOTE_ID=$ROW["VOTE_ID"];
        $FROM_ID=$ROW["FROM_ID"];
        $TO_ID=$ROW["TO_ID"];
        $PRIV_ID=$ROW["PRIV_ID"];
        $USER_ID=$ROW["USER_ID"];
        $SUBJECT=$ROW["SUBJECT"];
        $TYPE=$ROW["TYPE"];
        $ANONYMITY=$ROW["ANONYMITY"];
        $SEND_TIME=$ROW["SEND_TIME"];
        $BEGIN_DATE=$ROW["BEGIN_DATE"];
        $END_DATE=$ROW["END_DATE"];
        $READERS=$ROW["READERS"];
        $PUBLISH=$ROW["PUBLISH"];
        $TOP=$ROW["TOP"];
        $SUBJECT_TITLE="";
        if(strlen($SUBJECT) > 50)
        {
            $SUBJECT_TITLE=$SUBJECT;
            $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
        }
        $SUBJECT=td_htmlspecialchars($SUBJECT);
        $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT_TITLE);

        if($TOP=="1")
            $SUBJECT="<font color=red><b>".$SUBJECT."</b></font>";

        if($TYPE=="0")
            $TYPE_DESC=_("单选");
        else if($TYPE=="1")
            $TYPE_DESC=_("多选");
        else
            $TYPE_DESC=_("文本输入");

        if($PUBLISH=="0")
            $PUBLISH_DESC= "<font color='#FF0000'><b>"._("未发布")."</b></font>";
        else
            $PUBLISH_DESC= "<font color='#00AA00'><b>"._("已发布")."</b></font>";

        if($ANONYMITY=="0")
            $ANONYMITY_DESC=_("不允许");
        else
            $ANONYMITY_DESC=_("允许");

        if($END_DATE=="0000-00-00 00:00:00")
            $END_DATE="";

        $query1="select * from USER where USER_ID='$FROM_ID'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
        {
            $FROM_NAME=$ROW["USER_NAME"];
            $DEPT_ID=$ROW["DEPT_ID"];
        }

        $DEPT_NAME=dept_long_name($DEPT_ID);

        if($TO_ID=="ALL_DEPT")
            $TO_NAME=_("全体部门");
        else
            $TO_NAME=GetDeptNameById($TO_ID);

        $PRIV_NAME=GetPrivNameById($PRIV_ID);

        $USER_NAME=GetUserNameById($USER_ID);


        $READ_COUNT = 0;
        $READER_ARRAY = explode(',',td_trim($READERS));
        $READER_ARRAY = array_unique($READER_ARRAY);
        foreach($READER_ARRAY as $READER)
        {
            if($READER != "")
                $READ_COUNT++;
        }

        if($TO_ID!="ALL_DEPT")
        {
            $DEPT_OTHER_SQL = "";
            $TO_ID_ARRAY = explode(",", $TO_ID);
            foreach($TO_ID_ARRAY as $ID)
            {
                if($ID != "")
                    $DEPT_OTHER_SQL .= " OR FIND_IN_SET('$ID',DEPT_ID_OTHER)";
            }
            $sql ="SELECT COUNT(*) FROM USER WHERE (FIND_IN_SET(USER_PRIV,'$PRIV_ID') OR FIND_IN_SET(DEPT_ID,'$TO_ID')".$DEPT_OTHER_SQL." OR FIND_IN_SET(USER_ID,'$USER_ID')) AND NOT_LOGIN=0 AND DEPT_ID<>0";
        }
        else
        {
            $sql ="SELECT COUNT(*) FROM USER WHERE NOT_LOGIN=0 AND DEPT_ID<>0";
        }
        $cursor_sql= exequery(TD::conn(),$sql);
        if($ROW_SQL=mysql_fetch_array($cursor_sql))
            $ALL_COUNT=$ROW_SQL[0];

        $UN_READ_COUNT = $ALL_COUNT-$READ_COUNT;

        $MSG=$READ_COUNT."/".$UN_READ_COUNT;

        $TO_NAME_STR="";
        $TO_NAME_TITLE="";
        if($TO_NAME!="")
        {
            if(substr($TO_NAME,-strlen($POSTFIX))==$POSTFIX)
                $TO_NAME=substr($TO_NAME,0,-strlen($POSTFIX));
            $TO_NAME_TITLE.=_("部门：").$TO_NAME;
            $TO_NAME_STR.= "<font color=#0000FF><b>"._("部门：")."</b></font>".csubstr(strip_tags($TO_NAME),0,20).(strlen($TO_NAME)>20?"...":"")."<br>";
        }
        if($PRIV_NAME!="")
        {
            if(substr($PRIV_NAME,-strlen($POSTFIX))==$POSTFIX)
                $PRIV_NAME=substr($PRIV_NAME,0,-strlen($POSTFIX));
            if($TO_NAME_TITLE!="")
                $TO_NAME_TITLE.="\n\n";
            $TO_NAME_TITLE.=_("角色：").$PRIV_NAME;
            $TO_NAME_STR.= "<font color=#0000FF><b>"._("角色：")."</b></font>".csubstr(strip_tags($PRIV_NAME),0,20).(strlen($PRIV_NAME)>20?"...":"")."<br>";
        }
        if($USER_NAME!="")
        {
            if(substr($USER_NAME,-strlen($POSTFIX))==$POSTFIX)
                $USER_NAME=substr($USER_NAME,0,-strlen($POSTFIX));
            if($TO_NAME_TITLE!="")
                $TO_NAME_TITLE.="\n\n";
            $TO_NAME_TITLE.=_("人员：").$USER_NAME;
            $TO_NAME_STR.= "<font color=#0000FF><b>"._("人员：")."</b></font>".csubstr(strip_tags($USER_NAME),0,20).(strlen($USER_NAME)>20?"...":"")."<br>";
        }

        if(compare_date($CUR_DATE,$BEGIN_DATE)<0)
        {
            $VOTE_STATUS=1;
            $VOTE_STATUS_STR=_("待生效");
        }
        else
        {
            $VOTE_STATUS=2;
            $VOTE_STATUS_STR= "<font color='#00AA00'><b>"._("生效")."</b></font>";
        }


        if($END_DATE!="")
        {
            if(compare_date($CUR_DATE,$END_DATE)>=0)
            {
                $VOTE_STATUS=3;
                $VOTE_STATUS_STR = "<font color='#FF0000'><b>"._("终止")."</b></font>";
            }
        }

        if($PUBLISH=="0")
            $VOTE_STATUS_STR="";

        if($VOTE_COUNT%2==1)
            $TableLine="TableLine1";
        else
            $TableLine="TableLine2";
        ?>
        <tr class="<?=$TableLine?>">
            <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$VOTE_ID?>" >
            <td nowrap align="center"><u title="<?=_("部门：")?><?=$DEPT_NAME?>" style="cursor:hand"><?=$FROM_NAME?></u></td>
            <td title="<?=$TO_NAME_TITLE?>"><?=$TO_NAME_STR?></td>
            <td>
                <a href="javascript:show_reader('<?=$VOTE_ID?>');" title="<?=$SUBJECT_TITLE?>"><?=$SUBJECT?></a>
            </td>
            <td nowrap align="center"><?=$TYPE_DESC?></td>
            <td nowrap align="center"><?=$ANONYMITY_DESC?></td>
            <td nowrap align="center"><?=substr($BEGIN_DATE,0,10)?></td>
            <td nowrap align="center"><?=substr($END_DATE,0,10)?></td>
            <td nowrap align="center"><?=$VOTE_STATUS_STR==""? $PUBLISH_DESC:$VOTE_STATUS_STR?></td>
            <td nowrap align="center"><?if (($VOTE_STATUS==2&&$PUBLISH!="0")|| ($VOTE_STATUS==3&&$PUBLISH!="0"))echo $MSG;else echo "-/-";?></td>
            <td align="center" nowrap>
                <a href="vote.php?PARENT_ID=<?=$VOTE_ID?>&start=<?=$start?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("子投票")?></a>
                <?
                if($TYPE!="2")
                {
                    ?>
                    <a href="item/?VOTE_ID=<?=$VOTE_ID?>&start=<?=$start?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("投票项目")?></a>
                    <?
                }
                ?>
                <a href="new.php?VOTE_ID=<?=$VOTE_ID?>&start=<?=$start?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("修改")?></a>

                <a href="export/export.php?VOTE_ID=<?=$VOTE_ID?>"><?=_("导出")?></a>


                <?
                if($VOTE_STATUS==1)
                {
                    ?>
                    <a href="manage.php?VOTE_ID=<?=$VOTE_ID?>&OPERATION=1&start=<?=$start?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("立即生效")?></a>
                    <?
                }
                else if(($VOTE_STATUS==2)&&($PUBLISH!="0"))
                {
                    ?>
                    <a href="manage.php?VOTE_ID=<?=$VOTE_ID?>&OPERATION=2&start=<?=$start?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("立即终止")?></a>
                    <?
                }
                else if($VOTE_STATUS==3)
                {
                    ?>
                    <a href="manage.php?VOTE_ID=<?=$VOTE_ID?>&OPERATION=3&start=<?=$start?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("恢复生效")?></a>
                    <?
                }
                ?>
                <?
                if($PUBLISH=="0")
                {
                    ?>
                    <a href="#" onClick="javascript:release('<?=$VOTE_ID?>','<?=$start?>');"> <?=_("立即发布")?></a>
                    <?
                }
                ?>
            </td>
        </tr>
        <?
    }
    ?>

    <tr class="TableControl">
        <td colspan="19">
            <input type="checkbox" name="allbox" id="allbox_for" ><label for="allbox_for"><?=_("全选")?></label> &nbsp;
            <a href="javascript:delete_mail();" title="<?=_("删除所选投票")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除")?></a>&nbsp;
            <?
            if($_SESSION["LOGIN_USER_PRIV"]=="1")
            {
                ?>
                <a href="javascript:delete_all();" title="<?=_("删除所有自己发布的投票")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("全部删除")?></a>&nbsp;
                <?
            }
            ?>
            <a href="javascript:clear_vote();" title="<?=_("清空投票数据")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("清空数据")?></a>&nbsp;
            <a href="javascript:clone();" title="<?=_("复制投票，但不复制数据")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/copy.gif" align="absMiddle"><?=_("克隆")?></a>&nbsp;
            <a href="javascript:cancel_top();" title="<?=_("取消所选投票置顶")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/user_group.gif" align="absMiddle"><?=_("取消置顶")?></a>&nbsp;
        </td>
    </tr>
</table>
</body>
</html>
