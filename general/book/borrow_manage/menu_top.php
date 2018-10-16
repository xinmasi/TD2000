<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("借书管理"), "href" => "borrow/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/book.gif"),
   array("text" => _("还书管理"), "href" => "return/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/book.gif"),
   array("text" => _("历史记录查询"), "href" => "query/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif")
);

include_once("inc/menu_top.php");
?>
