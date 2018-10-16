<?
include_once("inc/auth.inc.php");

$MENU_TOP = array(
    array("text" => _("新建权限"), "href" => "new/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/user_group.gif"),
    array("text" => _("审批权限"), "href" => "approve/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/user_group.gif"),
);

include_once("inc/menu_top.php");
?>