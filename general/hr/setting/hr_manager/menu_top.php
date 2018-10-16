<?
include_once("inc/auth.inc.php");
 

$MENU_TOP=array(
   array("text" => _("人力资源管理员设置"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("批量设置人力资源管理员"), "href" => "set_all.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("设置公开字段"), "href" => "open.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("人事档案查询列表字段设置"), "href" => "archives.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("人事合同提醒设置"), "href" => "prompt.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),   
   array("text" => _("其他设置"), "href" => "set_other.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif")

);

include_once("inc/menu_top.php");
?>
