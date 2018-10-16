<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
   
$MENU_LEFT=array();

$target="hrms";

$user_list=array(
"PARA_URL1" => "",
"PARA_URL2" => "/general/attendance/manage/user_manage/user.php",
"PARA_TARGET" => $target,
"PRIV_NO_FLAG" => "1",
"MANAGE_FLAG" => "1",
"MODULE_ID" => "9",
"xname" => "attend_manage",
"showButton" => "0",
"include_file" => "inc/user_list/index.php"
);

$MENU_LEFT[count($MENU_LEFT)] = array("text" => _("在职人员"), "href" => "", "onclick" => "clickMenu", "target" => $target, "title" => _("点击伸缩列表"), "img" => "", "module" => $user_list, "module_style" => "");

include_once("inc/menu_left.php");
?>