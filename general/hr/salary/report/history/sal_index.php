<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("工资上报");
$MENU_LEFT_CONFIGS = Array(
    'offset' => '200px',
    'framename' => 'hrms'
);
$MENU_LEFT_CONFIGS['href'] = 'blank.php?PAGE_START='.$PAGE_START;
$FLOW_ID = $_GET["FLOW_ID"];

include_once("user_list.php");
?>