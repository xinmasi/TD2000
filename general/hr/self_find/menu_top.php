<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
    array("text" => _("员工自助查询"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif"),
    array("text" => _("员工积分查询"), "href" => "integral/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif"),
    array("text" => _("专家信息"), "href" => "experts/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif")
);

include_once("inc/menu_top.php");
?>
