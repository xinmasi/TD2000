<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("������Ϣ����"), "href" => "manage.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("��ӳ���"), "href" => "new.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("�Ϳ�����"), "href" => "oilcard.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("����Ϳ�"), "href" => "newoilcard.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("�������복����Ϣ"), "href" => "import.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif")
);

include_once("inc/menu_top.php");
?>
