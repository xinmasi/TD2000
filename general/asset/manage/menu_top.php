<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("固定资产管理"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/asset.gif"),
   array("text" => _("增加固定资产"), "href" => "new/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("导入固定资产"), "href" => "import.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("固定资产折旧"), "href" => "dpct.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/asset.gif")
);

include_once("inc/menu_top.php");
?>
