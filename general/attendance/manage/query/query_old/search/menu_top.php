<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
    array("text" => _("上下班统计"), "href" => "index1.php?DATE1=$DATE1&DATE2=$DATE2&DUTY_TYPE1=$DUTY_TYPE1&DEPARTMENT1=$DEPARTMENT1", "target" => "menu_main", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("外出记录"), "href" => "out.php?DATE1=$DATE1&DATE2=$DATE2&DUTY_TYPE1=$DUTY_TYPE1&DEPARTMENT1=$DEPARTMENT1", "target" => "menu_main", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("请假记录"), "href" => "leave.php?DATE1=$DATE1&DATE2=$DATE2&DUTY_TYPE1=$DUTY_TYPE1&DEPARTMENT1=$DEPARTMENT1", "target" => "menu_main", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("出差记录"), "href" => "evection.php?DATE1=$DATE1&DATE2=$DATE2&DUTY_TYPE1=$DUTY_TYPE1&DEPARTMENT1=$DEPARTMENT1", "target" => "menu_main", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("加班记录"), "href" => "overtime.php?DATE1=$DATE1&DATE2=$DATE2&DUTY_TYPE1=$DUTY_TYPE1&DEPARTMENT1=$DEPARTMENT1", "target" => "menu_main", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/attendance.gif"),
   array("text" => _("返回"), "href" => "../index1.php", "target" =>"_parent", "title" => "", "img" => ""),
);

include_once("inc/menu_top.php");
?>
