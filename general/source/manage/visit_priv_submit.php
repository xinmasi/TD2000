<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("ָ����Դ�����ɫȨ��");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$SOURCEID=intval($SOURCEID);
$query="update OA_SOURCE set VISIT_PRIV='$FLD_STR' where SOURCEID='$SOURCEID'";
exequery(TD::conn(), $query);

Message(_("��ʾ"),_("���óɹ���"));
?>

<div align=center>
<input type="button" class="BigButton" value="<?=_("�ر�")?>" onclick="window.close()">
</div>

</body>
</html>