<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("Ñ¡Ôñ¼Æ»®±àºÅ");

$MENU_TOP_CONFIGS['href'] = 'plan_no_info.php';
$MENU_TOP_CONFIGS['framename'] = 'plan_no_info';

?>
<frameset cols="*"  rows="40,*" frameborder="YES" border="1" framespacing="0" id="bottom">
  <frame name="query" src="query.php" scrolling="NO" frameborder="YES">
  <frame name="plan_no_info" src="plan_no_info.php" frameborder="NO">
 </frameset>
</frameset>