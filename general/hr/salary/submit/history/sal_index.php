<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�����ϱ�");

$FLOW_ID = $_GET["FLOW_ID"];

$MENU_LEFT_CONFIGS = Array(
    'href' => 'blank.php?PAGE_START='.$PAGE_START,
    'offset' => '200px',
    'framename' => 'hrms'
);
include_once("user_list.php");
?>