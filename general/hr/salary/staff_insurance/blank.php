<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/salary.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("н���������")?></span>
    </td>
  </tr>
</table>
<br>

<?
Message(_("��ʾ"),_("��ѡ��Ա��"));
?>

<br>
<div align="center">
  <input type="button" value="<?=_("����")?>" class="BigButton" onclick="parent.location='index.php'">&nbsp;
</div>

</body>
</html>
