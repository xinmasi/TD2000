<?
/**
 * ����Ȩ������
 * �û�|����
 * PRIV_CODE='APPROVE'
 * @author S2 zy <zy@tongda2000.com>
 */
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("������������");
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
    Message("", _("����ɹ���"));
    button_back();
    ?>
</body>
</html>
