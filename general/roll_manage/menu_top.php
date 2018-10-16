<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("案卷管理"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/news.gif"),
   array("text" => _("新建案卷"), "href" => "new.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("案卷查询"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif")
);

include_once("inc/menu_top.php");
?>
