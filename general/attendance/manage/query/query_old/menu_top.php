<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("考勤统计"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("考勤导入"), "href" => "import.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/sys_config.gif"),
   array("text" => _("轮班考勤统计"), "href" => "index2.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif")
);

include_once("inc/menu_top.php");
?>
