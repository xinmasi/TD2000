<?
include_once("inc/auth.inc.php");
include_once("pass_ask_duty.php");

$MENU_TOP=array(
   array("text" => _("���°�Ǽ�"), "href" => "duty", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
    array("text" => _("�ֻ�����"), "href" => "mobile", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("����Ǽ�"), "href" => "out", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("��ٵǼ�"), "href" => "leave", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("����Ǽ�"), "href" => "evection", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("�Ӱ�Ǽ�"), "href" => "overtime", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("�ҵ�ֵ��"), "href" => "on_duty", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),  
   array("text" => _("��ڼ�¼"), "href" => "ask_duty", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),  
   array("text" => _("���°��¼"), "href" => "mobile/list.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif")
);

include_once("inc/menu_top.php");
?>
