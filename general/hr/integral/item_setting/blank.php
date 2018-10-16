<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/system.gif" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("积分项设置")?></span>
    </td>
  </tr>
</table>

<?
Message(_("积分项定义说明"),_("通过此模块，用户可对积分项以及其默认积分进行定制"));
?>

</body>
</html>
