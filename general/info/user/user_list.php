<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$MENU_LEFT=array();

$target="user_info";
$user_list=array(
"PARA_URL1" => "/general/ipanel/user/search.php",
"PARA_URL2" => "/general/ipanel/user/user_info.php",
"PARA_TARGET" => $target,
"PRIV_NO_FLAG" => "0",
"MANAGE_FLAG" => "1",
"xname" => "info_user",
"showButton" => "0",
"include_file" => "inc/user_list/index.php");

$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("��ְ��Ա"), "href" => "", "onclick" => "clickMenu", "target" => $target, "title" => _("��������б�"), "img" => "", "module" => $user_list, "module_style" => "");
$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("�����Ա"), "href" => "out.php", "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => "", "module_style" => "");
$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("ȫ����Ա"), "href" => "user_all.php", "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => "", "module_style" => "");
$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("��Ա��ѯ"), "href" => "/general/ipanel/user/query.php", "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => "", "module_style" => "");

include_once("inc/menu_left.php");
?>
