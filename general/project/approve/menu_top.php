<?
include_once("inc/auth.inc.php");


$MENU_TOP = array(
    array("text" => _("待批项目"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/menu/project.gif"),
    array("text" => _("审批记录"), "href" => "history.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/menu/project.gif"),
);

include_once("inc/menu_top.php");
?>