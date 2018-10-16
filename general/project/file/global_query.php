<?
include_once("inc/auth.inc.php");
$SORT_ID=intval($SORT_ID);

$HTML_PAGE_TITLE = _("全局搜索");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function CheckForm()
{
    if(document.form1.SUBJECT.value==""  && document.form1.CREATER.value==""
        && document.form1.SEND_TIME_MIN.value=="" && document.form1.SEND_TIME_MAX.value=="")
    { alert("<?=_("请指定至少一个查询条件！")?>");
        return (false);
    }

    return true;
}
</script>


<body class="bodycolor" onLoad="document.form1.SUBJECT.focus();">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/folder_search.gif" align="absmiddle"><b><span class="Big1"> <?=_("全局搜索")?></span></b><br>
        </td>
    </tr>
</table>

<br>

<table class="TableBlock" width="600" align="center">
    <form action="global_search.php" method="POST" name="form1" onsubmit="return CheckForm();">
        <tr class="TableData">
            <td nowrap align="right"><?=_("标题包含文字：")?></td>
            <td nowrap><input type="text" name="SUBJECT" class="BigInput" size="20"></td>
        </tr>
        <tr class="TableData">
            <td nowrap align="right"><?=_("创建人：")?></td>
            <td nowrap><input type="text" name="CREATER" class="BigInput" size="10" value="">
                <input type="hidden" name="UPLOAD_USER">
                <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('65','','UPLOAD_USER','CREATER')"><?=_("选择")?></a>
            </td>
        </tr>

        <tr class="TableData">
            <td nowrap align="right"><?=_("日期：")?></td>
            <td nowrap>
                <input type="text" name="SEND_TIME_MIN" size="20" maxlength="19" class="BigInput" value="" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
                <?=_("至")?>&nbsp;
                <input type="text" name="SEND_TIME_MAX" size="20" maxlength="19" class="BigInput" value="<?=date("Y-m-d H:i:s",time())?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">

            </td>
        </tr>
        <tr>
            <td nowrap class="TableControl" colspan="2" align="center">
                <input type="hidden" name="PROJ_ID" value="<?=$PROJ_ID?>">
                <input type="submit" value="<?=_("搜索")?>" class="BigButton" title="<?=_("进行文件搜索")?>">&nbsp;&nbsp;
            </td>
        </tr>
</table>
</form>
</body>
</html>