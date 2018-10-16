<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("游戏");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if(!check_time_range($MYOA_GAME_TIME_RANGE))
{
   Message(_("禁止"), _("当前时间禁止玩游戏"));
   exit;
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/game.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("游戏")?></span><br>
    </td>
    </tr>
</table>

<br>

<table width="450" class="TableBlock" align="center">
    <tr class="TableHeader">
      <td nowrap align="center"><?=_("游戏列表")?></td>
    </tr>
<?
$GAME_NAME_ARRAY=array(_("俄罗斯方块"),_("五联珠"),_("三连棋"),_("拯救金鱼"),_("指球"),_("上海"),_("跳舞机"),_("21点"),_("打砖块"),_("高尔夫"),_("拉霸"));

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
