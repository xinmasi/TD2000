<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("用户信息");
$MENU_LEFT_CONFIGS = Array(
    'href' => '/general/ipanel/user/query.php',
    'offset' => '200px',
    'framename' => 'user_info'
);
include_once("user_list.php");
?>