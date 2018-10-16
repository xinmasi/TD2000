<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("员工关怀管理"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/news.gif"),
   array("text" => _("新建员工关怀"), "href" => "new.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("员工关怀信息导入"), "href" => "pre_import.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("员工关怀查询"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif"),
   array("text" => _("关怀提醒"), "href" => "care_remind.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/notify.gif"),
   array("text" => _("生日贺卡模版"), "href" => "card_module_list.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/card_module.gif")
);

include_once("inc/menu_top.php");
?>
