<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("��������"), "href" => "query.php?DMER_STATUS=0", "target" => "", "title" => _("�ȴ���׼�ĳ�������"), "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("��׼����"), "href" => "query.php?DMER_STATUS=1", "target" => "", "title" => _("�Ѿ�����׼�ĳ�������"), "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("δ׼����"), "href" => "query.php?DMER_STATUS=3", "target" => "", "title" => _("δ����׼�ĳ�������"), "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif")
);

include_once("inc/menu_top.php");
?>
