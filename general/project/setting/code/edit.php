<?
include_once("inc/auth.inc.php");
$TITLE_ARRAY = array("PROJ_TYPE" => _("项目类型"), "PROJ_ROLE" => _("项目角色类型"), "PROJ_DOC_TYPE" => _("项目文档类型"), "PROJ_COST_TYPE" => _("项目费用类型"));

$HTML_PAGE_TITLE = _("代码编辑");
include_once("inc/header.inc.php");
?>
<script Language="JavaScript">
    function CheckForm()
    {
        if(document.form1.CODE_NO.value == "")
        {
            alert("<?= _("代码编号不能为空！") ?>");
            return (false);
        }

        if(document.form1.CODE_ORDER.value == "")
        {
            alert("<?= _("排序号不能为空！") ?>");
            return (false);
        }

        if(document.form1.CODE_NAME.value == "")
        {
            alert("<?= _("代码名称不能为空！") ?>");
            return (false);
        }
    }
</script>
<?
$query = "SELECT * from SYS_CODE where CODE_ID='$CODE_ID'";
$cursor = exequery(TD::conn(), $query);
if ($ROW = mysql_fetch_array($cursor))
{
    $CODE_NO = $ROW["CODE_NO"];
    $CODE_NAME = $ROW["CODE_NAME"];
    $CODE_EXT = unserialize($ROW["CODE_EXT"]);
    if (is_array($CODE_EXT) && $CODE_EXT[MYOA_LANG_COOKIE] != "")
        $CODE_NAME = $CODE_EXT[MYOA_LANG_COOKIE];

    $CODE_ORDER = $ROW["CODE_ORDER"];
    $PARENT_NO = $ROW["PARENT_NO"];
    $CODE_FLAG = $ROW["CODE_FLAG"];
}
?>

<body class="bodycolor" onload="document.form1.CODE_ORDER.focus();">
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?= MYOA_STATIC_SERVER ?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?= _("编辑代码项") ?></span>
            </td>
        </tr>
    </table>

    <br>
    <table class="TableBlock" width="450" align="center">
        <form action="update.php"  method="post" name="form1" onsubmit="return CheckForm();">
            <tr height="30">
                <td nowrap class="TableData" width="120"><?= _("代码类别：") ?></td>
                <td nowrap class="TableData">
                    <?= $TITLE_ARRAY[$PARENT_NO] ?>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData" width="120"><?= _("代码编号：") ?></td>
                <td nowrap class="TableData">
                    <input type="text" name="CODE_NO" class="<? if ($CODE_FLAG == "0") echo "BigStatic";
                    else echo "BigInput"; ?>" size="20" maxlength="100" value="<?= $CODE_NO ?>"<? if ($CODE_FLAG == "0") echo " readonly"; ?>>&nbsp;
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData" width="120"><?= _("排序号：") ?></td>
                <td nowrap class="TableData">
                    <input type="text" name="CODE_ORDER" class="BigInput" size="20" maxlength="100" value="<?= $CODE_ORDER ?>">
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData"><?= _("代码名称：") ?></td>
                <td nowrap class="TableData">
                    <input type="text" name="CODE_NAME" class="BigInput" size="20" maxlength="100" value="<?= $CODE_NAME ?>">&nbsp;
                </td>
            </tr>
            <tr>
                <td nowrap  class="TableControl" colspan="2" align="center">
                    <input type="hidden" value="<?= $PARENT_NO ?>" name="PARENT_NO">
                    <input type="hidden" value="<?= $CODE_ID ?>" name="CODE_ID">
                    <input type="submit" value="<?= _("确定") ?>" class="BigButton">&nbsp;&nbsp;
                    <input type="button" value="<?= _("返回") ?>" class="BigButton" onclick="history.back();">
                </td>
        </form>
    </table>

</body>
</html>