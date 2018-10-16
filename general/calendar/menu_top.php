<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("我的日程"), "href" => "arrange/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/calendar.gif"),
   array("text" => _("周期性事务"), "href" => "affair/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/calendar.gif"),
   array("text" => _("我的任务"), "href" => "task/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/calendar.gif"),
   array("text" => _("倒计时器"), "href" => "counttime/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/calendar.gif"),
   array("text" => _("导入/导出"), "href" => "in_out/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/inout.gif")
);

$MENU_RIGHT=help('007','skill/erp/calendar/');

include_once("inc/menu_top.php");
?>
