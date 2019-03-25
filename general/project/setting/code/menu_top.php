<?
include_once("inc/auth.inc.php");

$MENU_TOP = array(
    array("text" => _("项目类型"), "href" => "code_list.php?PARENT_NO=PROJ_TYPE", "target" => "code_list", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/edit.gif"),
    array("text" => _("项目角色类型"), "href" => "code_list.php?PARENT_NO=PROJ_ROLE", "target" => "code_list", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/edit.gif"),
    //array("text" => _("项目文档类型 "), "href" => "code_list.php?PARENT_NO=PROJ_DOC_TYPE", "target" => "code_list", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/edit.gif"),
    //array("text" => _("项目费用类型"), "href" => "code_list.php?PARENT_NO=PROJ_COST_TYPE", "target" => "code_list", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/edit.gif"),
    array("text" => _("项目费用科目"), "href" => "budget_list.php?PARENT_NO=PROJ_COST_TYPE", "target" => "code_list", "title" => "", "img" => MYOA_STATIC_SERVER . "/static/images/edit.gif")
);

include_once("inc/menu_top.php");
?>
