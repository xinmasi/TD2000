<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("工作计划管理"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/work_plan.gif"),
   array("text" => _("新建工作计划"), "href" => "new/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("工作计划查询"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif")
);

include_once("inc/menu_top.php");
?>
