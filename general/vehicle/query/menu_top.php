<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("��������"), "href" => "query.php?VU_STATUS=0", "target" => "", "title" => _("�ȴ���׼�ĳ�������"), "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("��׼����"), "href" => "query.php?VU_STATUS=1", "target" => "", "title" => _("�Ѿ�����׼�ĳ�������"), "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("ʹ���г���"), "href" => "query.php?VU_STATUS=2", "target" => "", "title" => _("����ʹ��״̬�ĳ���"), "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("δ׼����"), "href" => "query.php?VU_STATUS=3", "target" => "", "title" => _("δ����׼�ĳ�������"), "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("δʹ�ó���"), "href" => "not_using.php", "target" => "", "title" => _("δʹ�õĳ���"), "img" => MYOA_STATIC_SERVER."/static/images/menu/vehicle.gif"),
   array("text" => _("ȫ����¼��ѯ"), "href" => "query_all.php", "target" => "", "title" => _("ȫ������ʹ�ü�¼"), "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif")
);

include_once("inc/menu_top.php");
?>
