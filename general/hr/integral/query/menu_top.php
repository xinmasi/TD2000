<?
include_once("inc/auth.inc.php");

include_once("inc/utility_all.php");
$MENU_TOP=array(
   array("text" => _("积分查询"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("排行榜"), "href" => "rank.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
);
include_once("inc/menu_top.php");