<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("申请车辆"), "href" => "new.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/notify.gif"),
   array("text" => _("待批申请"), "href" => "query.php?VU_STATUS=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("已准申请"), "href" => "query.php?VU_STATUS=1", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("使用中车辆"), "href" => "query.php?VU_STATUS=2", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("未准申请"), "href" => "query.php?VU_STATUS=3&DMER_STATUS=3", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif")
);

include_once("inc/menu_top.php");
?>
