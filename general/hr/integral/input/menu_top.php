<?
include_once("inc/auth.inc.php");

include_once("inc/utility_all.php");
$MENU_TOP=array(
   array("text" => _("����¼��"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("����OAʹ�û���"), "href" => "auto.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("����¼�����"), "href" => "manage.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
);
include_once("inc/menu_top.php");