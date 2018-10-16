<?
include_once("inc/auth.inc.php");
 

$MENU_TOP=array(
   array("text" => _("工资流程管理"), "href" => "sal_flow/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/salary.gif"),
   array("text" => _("新建工资流程"), "href" => "sal_flow/new.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/salary.gif")
);

include_once("inc/menu_top.php");
?>
