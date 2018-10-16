<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("维护记录管理"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("添加维护记录"), "href" => "new.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif")
);

include_once("inc/menu_top.php");
?>
