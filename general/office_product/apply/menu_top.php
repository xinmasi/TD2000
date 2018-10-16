<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("办公用品申领"), "href" => "apply.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/office_product.gif"),
   array("text" => _("批量申领"), "href" => "apply_more.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/office_product.gif"),
   array("text" => _("我的申领记录"), "href" => "apply_list.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif")
);

include_once("inc/menu_top.php");
?>
