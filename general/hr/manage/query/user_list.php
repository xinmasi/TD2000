<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");


$MENU_HEAD = array();
$MENU_LEFT=array();

$target="hrms";
$user_list=array(
"PARA_URL1" => "blank.php",
"PARA_URL2" => "show_staff_info.php",
"PARA_TARGET" => $target,
"PRIV_NO_FLAG" => "1",
"MANAGE_FLAG" => "1",
"MODULE_ID" => "10",
"xname" => "hrms_manage",
"showButton" => "0",
"include_file" => "inc/user_list/index.php");

$MENU_LEFT[] = array("text" => _("��ѯ/������ְ��Ա���µ���"), "href" => "query.php?is_leave=0", "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => "", "module_style" => "");
$MENU_LEFT[] = array("text" => _("��ְ��Ա"), "href" => "", "onclick" => "clickMenu", "target" => $target, "title" => _("��������б�"), "img" => "", "module" => $user_list, "module_style" => "");

include_once("inc/menu_left.php");
?>
