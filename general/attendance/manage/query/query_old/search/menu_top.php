<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
    array("text" => _("���°�ͳ��"), "href" => "index1.php?DATE1=$DATE1&DATE2=$DATE2&DUTY_TYPE1=$DUTY_TYPE1&DEPARTMENT1=$DEPARTMENT1", "target" => "menu_main", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("�����¼"), "href" => "out.php?DATE1=$DATE1&DATE2=$DATE2&DUTY_TYPE1=$DUTY_TYPE1&DEPARTMENT1=$DEPARTMENT1", "target" => "menu_main", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("��ټ�¼"), "href" => "leave.php?DATE1=$DATE1&DATE2=$DATE2&DUTY_TYPE1=$DUTY_TYPE1&DEPARTMENT1=$DEPARTMENT1", "target" => "menu_main", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("�����¼"), "href" => "evection.php?DATE1=$DATE1&DATE2=$DATE2&DUTY_TYPE1=$DUTY_TYPE1&DEPARTMENT1=$DEPARTMENT1", "target" => "menu_main", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("�Ӱ��¼"), "href" => "overtime.php?DATE1=$DATE1&DATE2=$DATE2&DUTY_TYPE1=$DUTY_TYPE1&DEPARTMENT1=$DEPARTMENT1", "target" => "menu_main", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("����"), "href" => "../index1.php", "target" =>"_parent", "title" => "", "img" => ""),
);

include_once("inc/menu_top.php");
?>
