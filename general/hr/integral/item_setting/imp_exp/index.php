<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("积分项备份/恢复");
include_once("inc/header.inc.php");
?>


<script>
function checkForm()
{
 msg='<?=_("确认要继续吗？")?>';
 if(window.confirm(msg))
 {
  form1.submit();
 }
}
</script>


<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/system.gif" align="absmiddle"><span class="big3"> <?=_("积分项备份")?></span><br>
    </td>
  </tr>
</table>
<div align="center" class="Big1">
<input type="button"  value="<?=_("积分项导出/备份")?>" class="BigButton" onClick="window.open('export.php')">
</div>
<br>
<br>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/system.gif" align="absmiddle"><span class="big3"> <?=_("积分项恢复")?></span><br>
    </td>
  </tr>
</table>
<div align="center" class="Big1">
<b><?=_("请指定用于积分项恢复的SQL文件：")?></b>
<form name="form1" method="post" action="sql.php" enctype="multipart/form-data">
  <input type="file" name="sql_file" class="BigInput" size="30">
  <input type="button" value="<?=_("恢复")?>" class="BigButton" onclick="return checkForm();">
</form>
</div>

</body>
</html>
