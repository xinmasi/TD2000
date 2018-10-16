<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("设置免审批范围");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>


<body class="bodycolor">
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?= MYOA_STATIC_SERVER ?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?= _("设置免审批范围") ?></span>
            </td>
        </tr>
    </table>
    <?
    $query = "SELECT PRIV_USER from PROJ_PRIV WHERE PRIV_CODE='NOAPP'";
    $cursor = exequery(TD::conn(), $query);
    if ($ROW = mysql_fetch_array($cursor))
    {
        $PRIV_USER = $ROW["PRIV_USER"];
        $PRIV_USER = explode("|", $PRIV_USER);
        $DEPT_ID_STR = $PRIV_USER[0];
        $PRIV_ID_STR = $PRIV_USER[1];
        $USER_ID_STR = $PRIV_USER[2];

        $DEPT_NAME_STR = "";
        if ($DEPT_ID_STR  == "ALL_DEPT")
        {
            $DEPT_NAME_STR = _("全体部门");
        }
        else
        {
            $DEPT_NAME_STR = GetDeptNameById($DEPT_ID_STR);
        }
        $USER_PRIV_STR = GetPrivNameById($PRIV_ID_STR);
        $USER_NAME_STR = GetUserNameById($USER_ID_STR);
    }
    ?>
    <table class="TableBlock"  width="550" align="center" >
        <form action="exempt_submit.php"  method="post" name="form1">
            <tr >
                <td nowrap class="TableContent"  align="center"><?= _("免审批范围：") ?><br><?= _("（部门）") ?></td>
                <td class="TableData">
                    <input type="hidden" name="DEPT_ID" value="<?= $DEPT_ID_STR ?>">
                    <textarea cols=45 name=DEPT_NAME rows=7 class="BigStatic" wrap="yes" readonly><?= $DEPT_NAME_STR ?></textarea>
                    <a href="javascript:;" class="orgAdd" onClick="SelectDept('', 'DEPT_ID', 'DEPT_NAME')"><?= _("添加") ?></a>
                    <a href="javascript:;" class="orgClear" onClick="ClearUser('DEPT_ID', 'DEPT_NAME')"><?= _("清空") ?></a>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableContent" align="center"><?= _("免审批范围：") ?><br><?= _("（角色）") ?></td>
                <td class="TableData">
                    <input type="hidden" name="PRIV_ID" value="<?= $PRIV_ID_STR ?>">
                    <textarea cols=45 name="PRIV_NAME" rows=7 class="BigStatic" wrap="yes" readonly><?= $USER_PRIV_STR ?></textarea>
                    <a href="javascript:;" class="orgAdd" onClick="SelectPriv('','PRIV_ID', 'PRIV_NAME')"><?= _("添加") ?></a>
                    <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?= _("清空") ?></a>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableContent" align="center"><?= _("免审批范围：") ?><br><?= _("（人员）") ?></td>
                <td class="TableData">
                    <input type="hidden" name="USER_ID" value="<?= $USER_ID_STR ?>">
                    <textarea cols=45 name="USER_NAME" rows=7 class="BigStatic" wrap="yes" readonly><?= $USER_NAME_STR ?></textarea>
                    <a href="javascript:;" class="orgAdd" onClick="SelectUser('192','', 'USER_ID', 'USER_NAME')"><?= _("选择") ?></a>
                    <a href="javascript:;" class="orgClear" onClick="ClearUser('USER_ID', 'USER_NAME')"><?= _("清空") ?></a>
                </td>
            </tr>
            <tr>
                <td nowrap  class="TableControl" colspan="2" align="center">
                    <input type="submit" value="<?= _("确定") ?>" class="BigButton" name="button">&nbsp;

                    <input type="button" value="<?= _("返回") ?>" class="BigButton" onclick="location = 'index.php'">
                </td>
            </tr>
        </form>
    </table>

</body>
</html>
