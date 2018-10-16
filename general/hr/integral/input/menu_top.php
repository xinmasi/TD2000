<?
include_once("inc/auth.inc.php");

include_once("inc/utility_all.php");
$MENU_TOP=array(
   array("text" => _("积分录入"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("计算OA使用积分"), "href" => "auto.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("积分录入管理"), "href" => "manage.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
);
include_once("inc/menu_top.php");