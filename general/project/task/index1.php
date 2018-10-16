<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("项目任务");

$PROJ_ID = $_GET["PROJ_ID"];
$TASK_ID = $_GET["TASK_ID"];

//修改事务提醒状态--yc
update_sms_status('42',$PROJ_ID);

$MENU_TOP_CONFIGS['href'] ='detail.php?PROJ_ID='.$PROJ_ID.'&TASK_ID='.$TASK_ID;
$MENU_TOP_CONFIGS['framename']='menu_main2';

include_once("menu_top1.php");
?>