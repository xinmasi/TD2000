<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
function level_desc($level)
{
    switch($level)
    {
        case "1": return _("非常高");
        case "2": return _("高");
        case "3": return _("普通");
        case "4": return _("低");
    }
}

//修改事务提醒状态--yc
update_sms_status('42',$PROJ_ID);

$HTML_PAGE_TITLE = _("项目问题");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script language="javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script>
jQuery.noConflict();
function showDetail(bug_id)
{
    jQuery.get("detail.php?BUG_ID="+bug_id,function(data){jQuery("#detail_body").html(data);ShowDialog('detail');});
}
function update(bug_id)
{
    msg='<?=_("确认要提交此问题吗？提交后将不能再修改。")?>';
    if(window.confirm(msg))
    {
        URL="update.php?BUG_ID=" + bug_id+"&PROJ_ID=<?=$PROJ_ID?>&TASK_ID=<?=$TASK_ID?>";
        window.location=URL;
    }
}
//-----zfc----
function update_by_change(bug_id)
{
    msg='<?=_("确认要收回此问题吗？")?>';
    if(window.confirm(msg))
    {
        URL="update.php?BUG_ID=" + bug_id+"&PROJ_ID=<?=$PROJ_ID?>&TASK_ID=<?=$TASK_ID?>" + "&TYPE=0";
        window.location=URL;
    }
}

function delete_bug(bug_id)
{
    var msg='<?=_("确认要删除此问题吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?BUG_ID=" + bug_id+"&PROJ_ID=<?=$PROJ_ID?>&TASK_ID=<?=$TASK_ID?>";
        window.location=URL;
    }
}
function goback(bug_id)
{
    document.form1.BUG_ID.value=bug_id;
    ShowDialog('back');
}

function check_form()
{
    if(document.form1.RESULT.value=="")
    {
        alert("<?=_("请填写退回理由！")?>");
        return(false);
    }
    return(true);
}
</script>

<body class="bodycolor" style="padding:5px">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("创建问题")?></span><br>
        </td>
    </tr>
</table>

<div align="center">
    <input type="button" value="<?=_("创建问题")?>" class="BigButton" onclick="location='new.php?PROJ_ID=<?=$PROJ_ID?>&TASK_ID=<?=$TASK_ID?>';">
</div>
<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
    <tr>
        <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
    </tr>
</table>

<br>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr><td>
            <img src="<?=MYOA_STATIC_SERVER?>/static/images/project/bug.gif" align="absmiddle" width=16 height=16/>
            <span class="big3"><?=_("已创建问题")?></span>
        <td></tr>
</table>

<table class="TableList" width="95%">
    <?
    $query = "select a.*,b.USER_NAME FROM PROJ_BUG AS a LEFT JOIN USER AS b ON (a.BEGIN_USER=b.USER_ID) where TASK_ID='$TASK_ID' AND BEGIN_USER='".$_SESSION["LOGIN_USER_ID"]."' ORDER BY CREAT_TIME DESC";
    $cursor = exequery(TD::conn(),$query);
    while($ROW=mysql_Fetch_array($cursor))
    {
        $COUNT++;
        if($COUNT==1)
            echo '
    <tr class="TableHeader">
        <td nowrap align="center">'._("问题名称").'</td>
        <td nowrap align="center">'._("提交人").'</td>
        <td nowrap align="center" width="120px">'._("处理底线").'</td>
        <td nowrap align="center" width="120px">'._("优先级").'</td>
        <td nowrap align="center" width="120px">'._("状态").'</td>
        <td nowrap align="center">'._("操作").'</td>
    </tr>';

        if($COUNT%2==1)
            $TableLine='TableLine1';
        else
            $TableLine='TableLine2';

        switch ($ROW["STATUS"])
        {
            case 0:
                $STATUS_DESC=_("未提交");
                break;
            case 1:
                $STATUS_DESC=_("未接收");
                break;
            case 2:
                $STATUS_DESC=_("处理中");
                break;
            case 3:
                $STATUS_DESC=_("已反馈");
                break;
            default:
                break;
        }

        echo '<tr class="'.$TableLine.'">
    <td nowrap align="center"><a href="#" onclick=showDetail("'.$ROW["BUG_ID"].'")>'.$ROW["BUG_NAME"].'</a></td>
    <td nowrap align="center">'.$ROW["USER_NAME"].'</td>
    <td nowrap align="center">'.$ROW["DEAD_LINE"].'</td>
    <td nowrap align="center"><span class="CalLevel'.$ROW["LEVEL"].'" title="'.level_desc($ROW["LEVEL"]).'">'.level_desc($ROW["LEVEL"]).'</span></td>
    <td nowrap align="center">'.$STATUS_DESC.'</td>
    <td nowrap align="center">';
        if($ROW["STATUS"]==0)
            echo '<a href="#" onclick=showDetail("'.$ROW["BUG_ID"].'")>'._("详情&nbsp;&nbsp;").'</a><a href="#" onclick=update("'.$ROW["BUG_ID"].'")>'._("提交").'</a>&nbsp;<a href="new.php?BUG_ID='.$ROW["BUG_ID"].'&PROJ_ID='.$PROJ_ID.'&TASK_ID='.$TASK_ID.'">'._("修改").'</a>&nbsp;<a href="#" onclick=delete_bug("'.$ROW["BUG_ID"].'") >'._("删除").'</a>&nbsp;';
        elseif($ROW["STATUS"]==3)
            echo '<a href="#" onclick=showDetail("'.$ROW["BUG_ID"].'")>'._("详情&nbsp;&nbsp;").'</a><a href="#" onclick=goback("'.$ROW["BUG_ID"].'")>'._("退回").'</a>';
        else
            echo '<a href="#" onclick=showDetail("'.$ROW["BUG_ID"].'")>'._("详情&nbsp;&nbsp;").'</a><font color=green>'._("已提交").'</font> &nbsp;<a href="#" onclick=update_by_change("'.$ROW["BUG_ID"].'")>'._("收回").'</a>';
        echo '</td></tr>';
    }
    ?>
</table>
<?
if($COUNT==0)
    Message('',_("没有已提交问题！"));

?>
<div id="overlay"></div>
<div id="detail" class="ModalDialog" style="width:550px;">
    <div class="header"><span id="title" class="title"><?=_("项目问题详情")?></span><a style="padding-top:2px" class="operation" href="javascript:HideDialog('detail');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/close.png"/></a></div>
    <div id="detail_body" class="body">
    </div>
    <div id="footer" class="footer">
        <input class="BigButton" onclick="HideDialog('detail')" type="button" value="<?=_("关闭")?>"/>
    </div>
</div>

<div id="back" class="ModalDialog" style="width:550px;">
    <div class="header"><span id="title" class="title"><?=_("项目问题退回")?></span><a style="padding-top:2px" class="operation" href="javascript:HideDialog('back');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/close.png"/></a></div>
    <form action="update.php" method="post" name="form1" onsubmit="return check_form();">
        <div id="back_body" class="body">
            <table class="TableList" border=0 align="center">
                <tr>
                    <td class="TableContent"><?=_("退回意见：")?></td>
                    <td class="TableData"><textarea class="BigInput" rows=5 cols=50 name="RESULT"></textarea></td>
                </tr>
            </table>
        </div>
        <div id="footer" class="footer">
            <input type="hidden" name="PROJ_ID" value="<?=$PROJ_ID?>"/>
            <input type="hidden" name="TASK_ID" value="<?=$TASK_ID?>"/>
            <input type="hidden" name="BUG_ID"/>
            <input class="BigButton" type="submit" value="<?=_("确定")?>"/>
            <input class="BigButton" onclick="HideDialog('back')" type="button" value="<?=_("关闭")?>"/>
        </div>
    </form>
</div>
</body>
</html>