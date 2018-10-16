<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("招聘录用管理"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/news.gif"),
   array("text" => _("新增招聘录用信息"), "href" => "new.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("招聘录用查询"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif")
);

include_once("inc/menu_top.php");
?>
