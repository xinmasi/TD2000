<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�������/�ָ�");
include_once("inc/header.inc.php");
?>


<script>
function checkForm()
{
 msg='<?=_("ȷ��Ҫ������")?>';
 if(window.confirm(msg))
 {
  form1.submit();
 }
}
</script>


<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/system.gif" align="absmiddle"><span class="big3"> <?=_("�������")?></span><br>
    </td>
  </tr>
</table>
<div align="center" class="Big1">
<input type="button"  value="<?=_("�������/����")?>" class="BigButton" onClick="window.open('export.php')">
</div>
<br>
<br>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/system.gif" align="absmiddle"><span class="big3"> <?=_("������ָ�")?></span><br>
    </td>
  </tr>
</table>
<div align="center" class="Big1">
<b><?=_("��ָ�����ڻ�����ָ���SQL�ļ���")?></b>
<form name="form1" method="post" action="sql.php" enctype="multipart/form-data">
  <input type="file" name="sql_file" class="BigInput" size="30">
  <input type="button" value="<?=_("�ָ�")?>" class="BigButton" onclick="return checkForm();">
</form>
</div>

</body>
</html>
