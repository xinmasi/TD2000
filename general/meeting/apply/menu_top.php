<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("�������"), "href" => "room_manage.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
   array("text" => _("��������"), "href" => "query.php?M_STATUS=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
   array("text" => _("��׼����"), "href" => "query.php?M_STATUS=1", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
   array("text" => _("�����л���"), "href" => "query.php?M_STATUS=2", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
   array("text" => _("δ��׼����"), "href" => "query.php?M_STATUS=3", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif")
);

include_once("inc/menu_top.php");
?>
