<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("代码编辑");
include_once("inc/header.inc.php");
?>
<script>
    function check_submit()
    {
        var type_no = form1.type_no.value;
        var type_name = form1.type_name.value;
        if(type_no == "" || !isInteger(type_no))
        {
            alert("<?= _('科目编号不能是字母或是空！') ?>");
            return false;
        }
        if(type_no.length != 3)
        {
            alert("<?= _('科目编号为三位数字！') ?>");
            return false;
        }
        if(type_name == "")
        {
            alert("<?= _('科目名称不能为空！') ?>");
            return false;
        }
    }
//判断是否为int类型
    function isInteger(str)
    {
        var regu = /^[-]{0,1}[0-9]{1,}$/;
        return regu.test(str);
    }
</script>
<?
$i_id = intval($_GET['id']);
$s_type_query = "SELECT type_name, type_no, id FROM proj_budget_type WHERE id='$i_id'";
$a_type_cursor = exequery(TD::conn(), $s_type_query);
while ($a_type_row = mysql_fetch_array($a_type_cursor))
{
    $type_name = $a_type_row['type_name'];
    $id = $a_type_row['id'];
    if (strlen($a_type_row['type_no']) == 3)
    {
        $type_no = $a_type_row['type_no'];
        $parent_budget = -1;
    }
    else
    {
        $type_no = substr($a_type_row['type_no'], 3, strlen($a_type_row['type_no']));
        $parent_budget = substr($a_type_row['type_no'], 0, 3);
    }
}
?>
<body class="bodycolor" onload="document.form1.type_no.focus();">
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?= MYOA_STATIC_SERVER ?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?= _("编辑科目项") ?></span>
            </td>
        </tr>
    </table>
    <table class="TableBlock" width="450" align="center">
        <form action="budget_update.php"  method="post" name="form1" onsubmit="return check_submit();">
            <tr>
            <input type="hidden" name="id" class="BigInput" size="20" maxlength="100" value="<?= $id ?>">
            <td nowrap class="TableData" width="120"><?= _("科目编号：") ?></td>
            <td nowrap class="TableData">
                <input type="text" name="type_no" class="BigInput" size="20" maxlength="100" value="<?= $type_no ?>">&nbsp;
            </td>
            </tr>
            <tr>
                <td nowrap class="TableData" width="120"><?= _("科目名称：") ?></td>
                <td nowrap class="TableData">
                    <input type="text" name="type_name" class="BigInput" size="20" maxlength="100" value="<?= $type_name ?>">
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData" width="120"><?= _("父级科目：") ?></td>
                <td nowrap class="TableData">
                    <select name="parent_budget" id="parent_budget" style="width:158px; height:24px;">
                        <option value="" <?php if ($parent_budget == -1) echo "selected"; ?>><?= _("无") ?></option>
                        <?
                        $s_query = "SELECT * FROM proj_budget_type where LENGTH(type_no)=3 and type_no!='$type_no'"; //不能自己当自己的一级科目
                        $s_cursor = exequery(TD::conn(), $s_query);
                        while ($t_row = mysql_fetch_array($s_cursor))
                        {
                            ?>
                            <option value="<?= $t_row['type_no'] ?>" <? if ($parent_budget == $t_row['type_no'])
                        {
                            echo "selected";
                        } ?>><?= td_htmlspecialchars($t_row['type_name']) ?></option>
    <?
}
?>			
                    </select>
                </td>
            </tr>
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