<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("未自评"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/score.gif"),
   array("text" => _("已自评"), "href" => "index2.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/score.gif"),
   array("text" => _("全部"), "href" => "main.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/score.gif")
);

include_once("inc/menu_top.php");
?>
