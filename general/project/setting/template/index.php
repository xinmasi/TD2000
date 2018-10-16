<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("项目模板管理");
include_once("inc/header.inc.php");
?>
<script Language="JavaScript">
    function check_all()
    {
        for (i = 0; i < document.all("email_select").length; i++)
        {
            if(document.all("allbox").checked)
                document.all("email_select").item(i).checked = true;
            else
                document.all("email_select").item(i).checked = false;
        }

        if(i == 0)
        {
            if(document.all("allbox").checked)
                document.all("email_select").checked = true;
            else
                document.all("email_select").checked = false;
        }
    }

    function check_one(el)
    {
        if(!el.checked)
            document.all("allbox").checked = false;
    }

    function get_checked()
    {
        checked_str = "";
        for (i = 0; i < document.all("email_select").length; i++)
        {

            el = document.all("email_select").item(i);
            if(el.checked)
            {
                val = el.value;
                checked_str += val + "|";
            }
        }

        if(i == 0)
        {
            el = document.all("email_select");
            if(el.checked)
            {
                val = el.value;
                checked_str += val + "|";
            }
        }
        return checked_str;
    }
    function delete_tpl()
    {
        delete_str = get_checked();
        if(delete_str == "")
        {
            alert("<?= _("请至少选择一个模板。") ?>");
            return;
        }

        msg = '<?= _("确认要删除所选模板吗？") ?>';
        if(window.confirm(msg))
        {
            url = "delete_tpl.php?DELETE_STR=" + delete_str;
            location = url;
        }
    }
</script>
<body class="bodycolor">  
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?= MYOA_STATIC_SERVER ?>/static/images/menu/project.gif" align="absmiddle"><span class="big3"> <?= _("项目模板管理") ?></span>
            </td>
        </tr>
    </table>
    <br>
    <?
    $PATH = MYOA_ATTACH_PATH . "proj_model/";
    $I = 0;
    if ($handle = opendir($PATH))
    {
        while (false !== ($file = readdir($handle)))
        {
            if (strtolower(substr($file, -4)) == ".xml")
            {
                $MODEL_ARRAY[$I++] = substr($file, 0, -4);
            }
        }
        closedir($handle);
    }

    if (sizeof($MODEL_ARRAY) > 0)
    {
        sort($MODEL_ARRAY);
        reset($MODEL_ARRAY);
    }

    for ($I = 0; $I < sizeof($MODEL_ARRAY); $I++)
    {
        if ($I == 0)
        {
            ?>
            <table class="TableList" width="600" align="center" border=0>
                <tr class="TableHeader">
                    <td width=80><?= _("选择") ?></td>
                    <td><?= _("模板名称") ?></td>
                </tr>
                <?
            }
            if ($I % 2 == 1)
                $TableLine = "TableLine1";
            else
                $TableLine = "TableLine2";
            ?>

            <tr class="<?= $TableLine ?>">
                <td><input type="checkbox" name="email_select" value="<?= urlencode($MODEL_ARRAY[$I]) ?>" onClick="check_one(self);">
                </td>
                <td style="cursor:hand">
                    <?= $MODEL_ARRAY[$I] ?>
                </td>
            </tr>
            <?
        }

        if ($I == 0)
        {
            Message("", _("没有定义项目模版"));
        }
        else
        {
            echo '
                <tr class="TableFooter">
                    <td colspan=2>
                     <div align="left">
                      <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();">
                      <label for="allbox_for">'._("全选").'</label>&nbsp;
                      <a href="javascript:delete_tpl();" title="'._("删除所选记录").'"><img src="'.MYOA_STATIC_SERVER.'/static/images/delete.gif" align="absMiddle">'._("删除").'</a>&nbsp;
                      </div>
                     </td>
                </tr></table>';
        }
?>