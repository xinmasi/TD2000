<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("今日计划"), "href" => "work_plan.php?TPYE=1&WORK_TYPE=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/work_plan.gif"),
   array("text" => _("本周计划"), "href" => "work_plan.php?TPYE=2&WORK_TYPE=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/work_plan.gif"),
   array("text" => _("本月计划"), "href" => "work_plan.php?TPYE=3&WORK_TYPE=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/work_plan.gif"),
   array("text" => _("计划查询"), "href" => "query/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif")
);

include_once("inc/menu_top.php");
?>
