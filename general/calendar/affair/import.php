<?
include_once("inc/auth.inc.php");


$HTML_PAGE_TITLE = _("��������������");
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
        alert("<?=_("��ѡ��Χ������ͬʱΪ�գ�")?>");
        return (false);
    }

    if(document.form1.XML_FILE.value=="")
    {
        alert("<?=_("��ѡ��Ҫ������ļ���")?>");
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
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/diary.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("��������������")?></span>
        </td>
    </tr>
</table>
<br>

<form enctype="multipart/form-data" action="import_affair.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <table class="TableBlock" width="500" align="center">
        <tr>
            <td nowrap class="TableContent"><?=_("ѡ��Χ(����)��")?></td>
            <td class="TableData">
                <input type="hidden" name="TO_ID" value="">
                <textarea cols=35 name="TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("���")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("���")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableContent"><?=_("ѡ��Χ(��ɫ)��")?></td>
            <td class="TableData">
                <input type="hidden" name="PRIV_ID" value="">
                <textarea cols=35 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectPriv('','PRIV_ID', 'PRIV_NAME')"><?=_("���")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("���")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableContent"><?=_("ѡ��Χ(��Ա)��")?></td>
            <td class="TableData">
                <input type="hidden" name="TO_ID3" value="">
                <textarea cols=35 name="TO_NAME3" rows="2" class="BigStatic" wrap="yes" readonly></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('8','','TO_ID3', 'TO_NAME3')"><?=_("���")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID3', 'TO_NAME3')"><?=_("���")?></a>
            </td>
        </tr>
        <tr height="25">
            <td nowrap class="TableContent"><?=_("ѡ��Ҫ�����XML�ļ���")?></td>
            <td class="TableData">
                <input type="file" name="XML_FILE" class="BigInput" size="30">
                <input type="hidden" name="FILE_NAME">
            </td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan="2" nowrap>
                <input type="submit" value="<?=_("����")?>" class="BigButton">&nbsp;
                <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='index.php'">
            </td>
        </tr>
    </table>
</form>
</body>
</html>
