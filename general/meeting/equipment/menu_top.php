<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("设备管理"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
   array("text" => _("新建设备"), "href" => "new.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
   array("text" => _("设备查询"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif")
);

include_once("inc/menu_top.php");
?>
