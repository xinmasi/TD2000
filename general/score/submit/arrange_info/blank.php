<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/diary.gif" WIDTH="18" HEIGHT="18"><span class="big3"> <?=_("工作日志查询")?></span>
    </td>
  </tr>
</table>
<br>

<?
Message(_("提示"),_("请选择用户"));
?>

</body>
</html>
