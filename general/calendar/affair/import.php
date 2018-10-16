<?
include_once("inc/auth.inc.php");


$HTML_PAGE_TITLE = _("导入周期性事务");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script Language="JavaScript">
function CheckForm()
{
    if(document.form1.TO_ID.value==""&&document.form1.TO_ID3.value==""&&document.form1.PRIV_ID.value=="")
    {
        alert("<?=_("请选择范围，不能同时为空！")?>");
        return (false);
    }

    if(document.form1.XML_FILE.value=="")
    {
        alert("<?=_("请选择要导入的文件！")?>");
        return (false);
    }

    if (document.form1.XML_FILE.value!="")
    {
        var file_temp=document.form1.XML_FILE.value,file_name;
        var Pos;
        Pos=file_temp.lastIndexOf("\\");
        file_name=file_temp.substring(Pos+1,file_temp.length);
        document.form1.FILE_NAME.value=file_name;
    }
    return (true);
}
</script>

<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/diary.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("导入周期性事务")?></span>
        </td>
    </tr>
</table>
<br>

<form enctype="multipart/form-data" action="import_affair.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <table class="TableBlock" width="500" align="center">
        <tr>
            <td nowrap class="TableContent"><?=_("选择范围(部门)：")?></td>
            <td class="TableData">
                <input type="hidden" name="TO_ID" value="">
                <textarea cols=35 name="TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableContent"><?=_("选择范围(角色)：")?></td>
            <td class="TableData">
                <input type="hidden" name="PRIV_ID" value="">
                <textarea cols=35 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectPriv('','PRIV_ID', 'PRIV_NAME')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("清空")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableContent"><?=_("选择范围(人员)：")?></td>
            <td class="TableData">
                <input type="hidden" name="TO_ID3" value="">
                <textarea cols=35 name="TO_NAME3" rows="2" class="BigStatic" wrap="yes" readonly></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('8','','TO_ID3', 'TO_NAME3')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID3', 'TO_NAME3')"><?=_("清空")?></a>
            </td>
        </tr>
        <tr height="25">
            <td nowrap class="TableContent"><?=_("选择要导入的XML文件：")?></td>
            <td class="TableData">
                <input type="file" name="XML_FILE" class="BigInput" size="30">
                <input type="hidden" name="FILE_NAME">
            </td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan="2" nowrap>
                <input type="submit" value="<?=_("导入")?>" class="BigButton">&nbsp;
                <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php'">
            </td>
        </tr>
    </table>
</form>
</body>
</html>
