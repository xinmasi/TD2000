<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("�̶��ʲ�����"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/asset.gif"),
   array("text" => _("���ӹ̶��ʲ�"), "href" => "new/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("����̶��ʲ�"), "href" => "import.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("�̶��ʲ��۾�"), "href" => "dpct.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/asset.gif")
);

include_once("inc/menu_top.php");
?>
