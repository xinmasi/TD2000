<?
include_once("inc/auth.inc.php");

if($CYCLE==1)
{
	$MENU_TOP=array(
	   array("text" => _("���������Ի���"), "href" => "manage1.php?M_STATUS=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("��������"), "href" => "manage.php?M_STATUS=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("��׼����"), "href" => "manage.php?M_STATUS=1", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("�����л���"), "href" => "manage.php?M_STATUS=2", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("δ��׼����"), "href" => "manage.php?M_STATUS=3", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("�ѽ�������"), "href" => "manage.php?M_STATUS=4", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("�����Ҫ����"), "href" => "summary.php?M_STATUS=5", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif")
	);
}
else
{
	$MENU_TOP=array(
	   array("text" => _("��������"), "href" => "manage.php?M_STATUS=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("���������Ի���"), "href" => "manage1.php?M_STATUS=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("��׼����"), "href" => "manage.php?M_STATUS=1", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("�����л���"), "href" => "manage.php?M_STATUS=2", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("δ��׼����"), "href" => "manage.php?M_STATUS=3", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("�ѽ�������"), "href" => "manage.php?M_STATUS=4", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
	   array("text" => _("�����Ҫ����"), "href" => "summary.php?M_STATUS=5", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif")
	);
}

include_once("inc/menu_top.php");
?>
