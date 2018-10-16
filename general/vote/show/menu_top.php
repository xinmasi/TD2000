<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("已发布的投票"), "href" => "current.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/vote.gif"),
   array("text" => _("已终止投票"), "href" => "history.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/vote.gif")
);

include_once("inc/menu_top.php");
?>
