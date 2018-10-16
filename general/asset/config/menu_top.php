<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   
   array("text" => _("基本参数"), "href" => "param/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/sys_config.gif"),
   array("text" => _("资产类别"), "href" => "type/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/sys_config.gif"),
   array("text" => _("导入设置"), "href" => "import/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/sys_config.gif"),
  
);

include_once("inc/menu_top.php");
?>
