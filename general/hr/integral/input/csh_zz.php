<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("calIntegral.class.php");
?>
<script>
function csh_submit()
{
	document.form1.action='csh.php';
	document.form1.submit();
}
</script>
<body class="bodycolor">
<?
Message(_("提示"),_("您确定删除之前计算的OA使用积分，清空积分项设置中的各项分值吗？"));
?>
<br>
<form name="form1" action="#" method="post">
	<center>
		<input type="button" class="BigButton" value="<?=_('确定')?>" onclick="csh_submit();">
		<input type="button" class="BigButton" value="<?=_('返回')?>" onclick="history.back();">
	</center>
</form>

