<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$MENU_TOP=array(
   array("text" => _("任务详情"), "href" => "detail_view.php?PROJ_ID=$PROJ_ID&TASK_ID=$TASK_ID", "target" => "menu_main2", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/project.gif"),
   array("text" => _("项目问题"), "href" => "problem_view/?PROJ_ID=$PROJ_ID&TASK_ID=$TASK_ID", "target" => "menu_main2", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/project/bug.gif"),
   array("text" => _("项目流程"), "href" => "workflow_view.php?PROJ_ID=$PROJ_ID&TASK_ID=$TASK_ID", "target" => "menu_main2", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/workflow.gif")
);

include_once("inc/menu_top.php");
?>
