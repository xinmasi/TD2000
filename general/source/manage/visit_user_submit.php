<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("指定资源申请用户权限");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$SOURCEID=intval($SOURCEID);
$query="update OA_SOURCE set VISIT_USER='$FLD_STR' where SOURCEID='$SOURCEID'";
exequery(TD::conn(), $query);

Message(_("提示"),_("设置成功！"));
?>

<div align=center>
<input type="button" class="BigButton" value="<?=_("关闭")?>" onclick="window.close()">
</div>

</body>
</html>