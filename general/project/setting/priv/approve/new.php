<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("设置审批规则");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?= MYOA_JS_SERVER ?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

<script Language="JavaScript">
    function CheckForm()
    {
        if(document.form1.USER_ID.value == "")
        {
            alert("<?= _("审批人不能为空！") ?>");
            return (false);
        }

        if(document.form1.DEPT_ID.value == "")
        {
            alert("<?= _("所管部门不能为空！") ?>");
            return (false);
        }
    }
</script>

<body class="bodycolor">
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?= MYOA_STATIC_SERVER ?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?= _("设置审批人员") ?></span>
            </td>
        </tr>
    </table>
    <?
    if ($PRIV_ID != "")
    {
        $query = "SELECT * from PROJ_PRIV WHERE PRIV_CODE='APPROVE' and PRIV_ID='$PRIV_ID'";
        $cursor = exequery(TD::conn(), $query);
        $MANAGER_COUNT = 0;
        if ($ROW = mysql_fetch_array($cursor))
        {
            $PRIV_USER = $ROW["PRIV_USER"];

            $PRIV_USER = explode("|", $PRIV_USER);
            $USER_ID_STR = $PRIV_USER[0];
            $DEPT_ID_STR = $PRIV_USER[1];

            $DEPT_NAME = "";
            if ($DEPT_ID_STR  == "ALL_DEPT")
            {
                $DEPT_NAME = _("全体部门");
            }
            else
            {
                $DEPT_NAME = GetDeptNameById($DEPT_ID_STR);
            }
            $MANAGER_NAME = GetUserNameById($USER_ID_STR);
        }
    }
    ?>
    <form action="submit.php"  method="post" name="form1" onsubmit="return CheckForm();">
        <table class="TableBlock"  width="500" align="center" >
            <tr>
                <td nowrap class="TableData"><?= _("审批人员：") ?></td>
                <td class="TableData">
                    <input type="hidden" name="USER_ID" value="<?= $PRIV_USER[0] ?>">
                    <textarea cols=30 name="USER_NAME" rows=3 class="BigStatic" wrap="yes" readonly><?= $MANAGER_NAME ?></textarea>
                    <a href="javascript:;" class="orgAdd" onClick="SelectUser('192','', 'USER_ID', 'USER_NAME')"><?= _("选择") ?></a>
                    <a href="javascript:;" class="orgClear" onClick="ClearUser('USER_ID', 'USER_NAME')"><?= _("清空") ?></a>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData"><?= _("所管部门：") ?></td>
                <td class="TableData">
                    <input type="hidden" name="DEPT_ID" value="<?= $PRIV_USER[1] ?>">
                    <textarea cols=30 name=DEPT_NAME rows=4 class="BigStatic" wrap="yes" readonly><?= $DEPT_NAME ?></textarea>
                    <a href="javascript:;" class="orgAdd" onClick="SelectDept('', 'DEPT_ID', 'DEPT_NAME')"><?= _("添加") ?></a>
                    <a href="javascript:;" class="orgClear" onClick="ClearUser('DEPT_ID', 'DEPT_NAME')"><?= _("清空") ?></a>
                </td>
            </tr>
            <tr>
                <td nowrap  class="TableControl" colspan="2" align="center">
                    <input type="hidden" name="PRIV_ID" value="<?= $PRIV_ID ?>">
                    <input type="submit" value="<?= _("确定") ?>" class="BigButton" name="button">&nbsp;
                    <input type="button" value="<?= _("返回") ?>" class="BigButton" onclick="location = 'index.php'">
                </td>
            </tr>

        </table>
    </form>
</body>
</html>
