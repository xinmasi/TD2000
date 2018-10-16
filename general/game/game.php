<?
while(list($GET_KEY, $GET_VALUE) = each($_GET))
{
   $GET_VALUE = preg_replace("/<(script|iframe)(.|\n)*$/i", "", $GET_VALUE);
   $_GET[$GET_KEY] = $GET_VALUE;
   $$GET_KEY = $GET_VALUE;
}

include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("游戏");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if(!check_time_range($MYOA_GAME_TIME_RANGE))
{
   Message(_("禁止"),_("当前时间禁止玩游戏"));
   exit;
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/game.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("游戏")?> - <?=$GAME_NAME_DESC?></span><br>
    </td>
    </tr>
</table>

<div align="center">
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="<?=MYOA_JS_SERVER?>/static/js/swflash.cab#version=6,0,0,0" width="500" height="400">
<param name=movie value="<?=$GAME_NAME?>.swf">
<param name=quality value=high>
</object>

<br>
<br>
<input type="button" value="<?=_("全屏")?>" language="JavaScript" onClick="window.open('<?=urlencode($GAME_NAME)?>.swf','game_full','status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes')" class="BigButton">&nbsp;&nbsp;
<input type="button" value="<?=_("重新开始")?>" language="JavaScript" onClick="location.reload()" class="BigButton">&nbsp;&nbsp;
<input type="button" value="<?=_("返回")?>" language="JavaScript" onClick="location='index.php'" class="BigButton">
</div>
</body>
</html>
