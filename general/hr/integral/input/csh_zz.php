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
Message(_("��ʾ"),_("��ȷ��ɾ��֮ǰ�����OAʹ�û��֣���ջ����������еĸ����ֵ��"));
?>
<br>
<form name="form1" action="#" method="post">
	<center>
		<input type="button" class="BigButton" value="<?=_('ȷ��')?>" onclick="csh_submit();">
		<input type="button" class="BigButton" value="<?=_('����')?>" onclick="history.back();">
	</center>
</form>

