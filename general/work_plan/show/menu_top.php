<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("���ռƻ�"), "href" => "work_plan.php?TPYE=1&WORK_TYPE=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/work_plan.gif"),
   array("text" => _("���ܼƻ�"), "href" => "work_plan.php?TPYE=2&WORK_TYPE=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/work_plan.gif"),
   array("text" => _("���¼ƻ�"), "href" => "work_plan.php?TPYE=3&WORK_TYPE=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/work_plan.gif"),
   array("text" => _("�ƻ���ѯ"), "href" => "query/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif")
);

include_once("inc/menu_top.php");
?>
