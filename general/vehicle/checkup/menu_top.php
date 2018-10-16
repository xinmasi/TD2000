<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("待批申请"), "href" => "query.php?VU_STATUS=0", "target" => "", "title" => _("等待批准的车辆申请"), "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("已准申请"), "href" => "query.php?VU_STATUS=1", "target" => "", "title" => _("已经被批准的车辆申请"), "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("使用中车辆"), "href" => "query.php?VU_STATUS=2", "target" => "", "title" => _("正在使用的车辆"), "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("未准申请"), "href" => "query.php?VU_STATUS=3", "target" => "", "title" => _("未被批准的车辆申请"), "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("使用结束车辆"), "href" => "query.php?VU_STATUS=4", "target" => "", "title" => _("使用结束车辆"), "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("全部记录查询"), "href" => "query_all.php", "target" => "", "title" => _("全部车辆使用记录"), "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif"),
   array("text" => _("车辆收回设置"), "href" => "vehicle_take_back.php", "target" => "", "title" => _("车辆收回设置"), "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif"),
   array("text" => _("其他设置"), "href" => "vehicle_other_setting.php", "target" => "", "title" => _("其他设置"), "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif")
);

include_once("inc/menu_top.php");
?>
