<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("待批招聘计划"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/news.gif"),
   array("text" => _("已批准招聘计划"), "href" => "pass_app.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/hr_recruit.gif"),
   array("text" => _("未批准招聘计划"), "href" => "failed_app.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/hr_recruit.gif"),
   //array("text" => _("已通过审批计划"), "href" => "pass_app.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif"),
   array("text" => _("招聘计划查询"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif")
);

include_once("inc/menu_top.php");
?>
