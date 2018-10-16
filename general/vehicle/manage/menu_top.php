<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("车辆信息管理"), "href" => "manage.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("添加车辆"), "href" => "new.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("油卡管理"), "href" => "oilcard.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("添加油卡"), "href" => "newoilcard.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("批量导入车辆信息"), "href" => "import.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif")
);

include_once("inc/menu_top.php");
?>
