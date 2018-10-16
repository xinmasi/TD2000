<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("文件管理"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/news.gif"),
   array("text" => _("新建文件"), "href" => "new.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("文件查询"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif")
);
include_once("inc/menu_top.php");
?>
