<?
/**
 * 审批例外（免审批）权限设置
 * 部门|角色|用户
 * PRIV_CODE='NOAPP'
 * @author S2 zy <zy@tongda2000.com>
 */
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("项目免审批权限");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">
    <?
//--------------------zfc--------------------------------------
    $PRIV_USER = $DEPT_ID . "|" . $PRIV_ID . "|" . $USER_ID;
    $query = "UPDATE PROJ_PRIV SET PRIV_USER='$PRIV_USER' WHERE PRIV_CODE='NOAPP'";
    exequery(TD::conn(), $query);
    
    if(!mysql_affected_rows()){
        $query1 = "insert into proj_priv(PRIV_CODE,PRIV_USER) values('NOAPP','')";
        exequery(TD::conn(), $query1);
        exequery(TD::conn(), $query);
    }       
    
    Message("", _("保存成功！"));
    ?>
    
    <div><span onclick="location.href = 'index.php'">
    <?
    button_back();
    ?></span>
        </div>
</body>
</html>
