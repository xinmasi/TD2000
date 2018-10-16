<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("工资上报");
$FLOW_ID = $_GET['FLOW_ID'];
$DEPT_ID = $_SESSION["LOGIN_DEPT_ID"];
$MENU_LEFT_CONFIGS['href'] = 'table.php?DEPT_ID='.$DEPT_ID.'&FLOW_ID='.$FLOW_ID;
$MENU_LEFT_CONFIGS['framename'] = 'hrms';
$MENU_LEFT_CONFIGS['offset'] = '200px';
include_once("user_list.php");
?>
