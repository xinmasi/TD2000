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

$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("在职人员"), "href" => "", "onclick" => "clickMenu", "target" => $target, "title" => _("点击伸缩列表"), "img" => "", "module" => $user_list, "module_style" => "");
$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("外出人员"), "href" => "out.php", "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => "", "module_style" => "");
$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("全部人员"), "href" => "user_all.php", "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => "", "module_style" => "");
$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("人员查询"), "href" => "/general/ipanel/user/query.php", "onclick" => "", "target" => $target, "title" => "", "img" => "", "module" => "", "module_style" => "");

include_once("inc/menu_left.php");
?>
