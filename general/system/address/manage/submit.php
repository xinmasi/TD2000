<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("添加维护部门");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?

$query="update ADDRESS_GROUP set SUPPORT_DEPT='$TO_ID',SUPPORT_USER='$COPY_TO_ID' where GROUP_ID='$GROUP_ID'";
exequery(TD::conn(),$query);

Message(_("提示"),_("操作成功"));
?>
<br>
<center>
	<input type="button" class="BigButton" value="<?=_("关闭")?>" onClick="window.close();" title="<?=_("关闭窗口")?>">
</center>
</body>
</html>