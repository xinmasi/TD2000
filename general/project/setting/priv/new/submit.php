<?
/**
 * �½���ĿȨ��
 * PRIV_CODE='NEW'
 * ����|��ɫ|�û���
 * @name submit.php 
 * @author S2 zy
 * 
 */
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("��Ŀ����Ȩ��");
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
    Message("", _("����ɹ���"));
    button_back();
?>
</body>
</html>