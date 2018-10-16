<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("奖惩信息管理"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/news.gif"),
   array("text" => _("新建奖惩信息"), "href" => "new.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("奖惩信息导入"), "href" => "pre_import.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("奖惩信息查询"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif"),
   
);

include_once("inc/menu_top.php");
?>
