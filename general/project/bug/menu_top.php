<?
include_once("inc/auth.inc.php");


$MENU_TOP = array(
    array("text" => _("���������"), "href" => "bug_list.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/project/bug.gif"),
    array("text" => _("��ʷ��¼"), "href" => "bug_list.php?HISTORY=1", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/project/bug.gif")
);

include_once("inc/menu_top.php");
?>