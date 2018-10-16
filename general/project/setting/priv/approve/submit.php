<?
/**
 * 审批权限设置
 * 用户|部门
 * PRIV_CODE='APPROVE'
 * @author S2 zy <zy@tongda2000.com>
 */
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("设置审批规则");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">
    <?
    $PRIV_USER = $USER_ID . "|" . $DEPT_ID;
    if ($PRIV_ID)
    {
        $query = "UPDATE PROJ_PRIV SET PRIV_USER='$PRIV_USER' WHERE PRIV_ID='$PRIV_ID' AND PRIV_CODE='APPROVE'";
    }
    else
    {
        $query = "insert into PROJ_PRIV (PRIV_CODE,PRIV_USER) VALUES('APPROVE', '$PRIV_USER')";
    }
    exequery(TD::conn(), $query);
    Message("", _("保存成功！"));
    button_back();
    ?>
</body>
</html>
