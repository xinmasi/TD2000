<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("��Ϸ");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if(!check_time_range($MYOA_GAME_TIME_RANGE))
{
   Message(_("��ֹ"), _("��ǰʱ���ֹ����Ϸ"));
   exit;
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/game.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("��Ϸ")?></span><br>
    </td>
    </tr>
</table>

<br>

<table width="450" class="TableBlock" align="center">
    <tr class="TableHeader">
      <td nowrap align="center"><?=_("��Ϸ�б�")?></td>
    </tr>
<?
$GAME_NAME_ARRAY=array(_("����˹����"),_("������"),_("������"),_("���Ƚ���"),_("ָ��"),_("�Ϻ�"),_("�����"),_("21��"),_("��ש��"),_("�߶���"),_("����"));

$COUNT=count($GAME_NAME_ARRAY);

for($I=0;$I<$COUNT;$I++)
{
?>
    <tr class="TableData">
      <td align="center"><a href="game.php?GAME_NAME=<?=$I?>&GAME_NAME_DESC=<?=$GAME_NAME_ARRAY[$I]?>"><?=$GAME_NAME_ARRAY[$I]?></a></td>
    </tr>
<?
}
?>

</table>

</body>
</html>
