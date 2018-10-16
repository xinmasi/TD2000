<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("待批计划"), "href" => "index1.php?ASSESSING_STATUS=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/hr_training.gif"),
   array("text" => _("已准记录"), "href" => "index1.php?ASSESSING_STATUS=1", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/hr_training.gif"),
   array("text" => _("未准记录"), "href" => "index1.php?ASSESSING_STATUS=2", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/hr_training.gif"),
   array("text" => _("计划（审批）查询"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/hr_training.gif")
);

include_once("inc/menu_top.php");
?>
