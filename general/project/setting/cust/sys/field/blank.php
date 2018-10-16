<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/system.gif" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("自定义字段设置")?></span>
    </td>
  </tr>
</table>

<?
   Message(_("自定义字段说明"),_("通过自定义字段，用户可对项目基本信息定制"));
?>

</body>
</html>
