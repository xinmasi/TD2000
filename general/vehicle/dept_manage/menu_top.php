<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("待批申请"), "href" => "query.php?DMER_STATUS=0", "target" => "", "title" => _("等待批准的车辆申请"), "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("已准申请"), "href" => "query.php?DMER_STATUS=1", "target" => "", "title" => _("已经被批准的车辆申请"), "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("未准申请"), "href" => "query.php?DMER_STATUS=3", "target" => "", "title" => _("未被批准的车辆申请"), "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif")
);

include_once("inc/menu_top.php");
?>
