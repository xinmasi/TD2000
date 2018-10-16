<?
/**
 * 新建项目权限
 * PRIV_CODE='NEW'
 * 部门|角色|用户名
 * @name submit.php 
 * @author S2 zy
 * 
 */
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("项目申请权限");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
    //------------zfc-----------------
    $PRIV_USER = $DEPT_ID . "|" . $PRIV_ID . "|" . $USER_ID;
    $query = "UPDATE PROJ_PRIV SET PRIV_USER='$PRIV_USER' WHERE PRIV_CODE='NEW'";
    exequery(TD::conn(), $query);
    if(!mysql_affected_rows()){
        $query1 = "insert into proj_priv(PRIV_CODE,PRIV_USER) values('NEW','||')";
        exequery(TD::conn(), $query1);
        exequery(TD::conn(), $query);
    }   
    Message("", _("保存成功！"));
    button_back();
?>
</body>
</html>