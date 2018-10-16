<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("未读公告"), "href" => "new.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/notify.gif"),
   array("text" => _("公告通知"), "href" => "notify.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/notify.gif"),
   array("text" => _("公告查询"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif")
);

include_once("inc/menu_top.php");
?>
