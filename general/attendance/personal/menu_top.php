<?
include_once("inc/auth.inc.php");
include_once("pass_ask_duty.php");

$MENU_TOP=array(
   array("text" => _("上下班登记"), "href" => "duty", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
    array("text" => _("手机考勤"), "href" => "mobile", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("外出登记"), "href" => "out", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("请假登记"), "href" => "leave", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("出差登记"), "href" => "evection", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("加班登记"), "href" => "overtime", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("我的值班"), "href" => "on_duty", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),  
   array("text" => _("查岗记录"), "href" => "ask_duty", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),  
   array("text" => _("上下班记录"), "href" => "mobile/list.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif")
);

include_once("inc/menu_top.php");
?>
