<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("���ά������");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?

$query="update ADDRESS_GROUP set SUPPORT_DEPT='$TO_ID',SUPPORT_USER='$COPY_TO_ID' where GROUP_ID='$GROUP_ID'";
exequery(TD::conn(),$query);

Message(_("��ʾ"),_("�����ɹ�"));
?>
<br>
<center>
	<input type="button" class="BigButton" value="<?=_("�ر�")?>" onClick="window.close();" title="<?=_("�رմ���")?>">
</center>
</body>
</html>