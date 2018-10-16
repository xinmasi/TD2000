<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("项目任务查看");
$PROJ_ID = $_GET["$PROJ_ID"];
$TASK_ID = $_GET["$TASK_ID"];

$MENU_TOP_CONFIGS['href'] ='detail_view.php?PROJ_ID='.$PROJ_ID.'&TASK_ID='.$TASK_ID;
$MENU_TOP_CONFIGS['framename']='menu_main2';


include_once("menu_top_view.php");
?>