<?
include_once("inc/auth.inc.php");

$TITLE_ARRAY = array("PROJ_TYPE" => _("项目类型"), "PROJ_ROLE" => _("项目角色类型"), "PROJ_DOC_TYPE" => _("项目文档类型"), "PROJ_COST_TYPE" => _("项目费用类型"));

$HTML_PAGE_TITLE = _("项目代码设置—");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script Language="JavaScript">
    function delete_code(CODE_ID, FUNC_NAME)
    {
        var msg = sprintf("<?= _("确认要删除代码项 '%s' 吗？") ?>", FUNC_NAME);
        if(window.confirm(msg))
        {
            URL = "delete.php?CODE_ID=" + CODE_ID + "&PARENT_NO=<?= $PARENT_NO ?>";
            location = URL;
        }
    }
</script>


<body class="bodycolor">
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?= MYOA_STATIC_SERVER ?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?= _("增加项目代码—") ?><?= $TITLE_ARRAY[$PARENT_NO] ?></span>
            </td>
        </tr>
    </table>

    <div align="center">
        <input type="button"  value="<?= _("增加代码项") ?>" class="BigButton" onClick="location = 'new.php?PARENT_NO=<?= $PARENT_NO ?>';" >
    </div>

    <br>

    <table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
        <tr>
            <td background="<?= MYOA_STATIC_SERVER ?>/static/images/dian1.gif" width="100%"></td>
        </tr>
    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?= MYOA_STATIC_SERVER ?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?= _("项目代码管理") ?></span>
            </td>
        </tr>
    </table>

    <table class="TableList" align="center">
        <tr class="TableHeader" align="center">
            <td nowrap align="center"><b><?= $TITLE_ARRAY[$PARENT_NO] ?></b>
            </td>
            <td nowrap align="center"><?= _("操作") ?></td>
        </tr>
        <?
        $query1 = "SELECT * from SYS_CODE where PARENT_NO='$PARENT_NO' order by CODE_ORDER";
        $cursor1 = exequery(TD::conn(), $query1);

        while ($ROW = mysql_fetch_array($cursor1))
        {
            $CODE_ID = $ROW["CODE_ID"];
            $CODE_NO = $ROW["CODE_NO"];
            $CODE_NAME = $ROW["CODE_NAME"];
            $CODE_EXT = unserialize($ROW["CODE_EXT"]);
            if (is_array($CODE_EXT) && $CODE_EXT[MYOA_LANG_COOKIE] != "")
                $CODE_NAME = $CODE_EXT[MYOA_LANG_COOKIE];

            $CODE_FLAG = $ROW["CODE_FLAG"];
            ?>
            <tr class="TableData">
                <td nowrap title="<?= $CODE_NAME ?>" >
                    &nbsp;<b><?= $CODE_NO ?>&nbsp;&nbsp;<?= $CODE_NAME ?></b>&nbsp;
                </td>
                <td nowrap>&nbsp;
                    <a href="edit.php?CODE_ID=<?= $CODE_ID ?>"> <?= _("编辑") ?></a>&nbsp;&nbsp;
                    <?
                    if ($CODE_FLAG != "0")
                    {
                        ?>
                        <a href="javascript:delete_code(<?= $CODE_ID ?>,'<?= $CODE_NAME ?>');"> <?= _("删除") ?></a>
                        <?
                    }
                    ?>
                </td>
            </tr>

            <?
        }//while
        ?>
    </table>
</body>
</html>