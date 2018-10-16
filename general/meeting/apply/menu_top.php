<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("申请会议"), "href" => "room_manage.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
   array("text" => _("待批会议"), "href" => "query.php?M_STATUS=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
   array("text" => _("已准会议"), "href" => "query.php?M_STATUS=1", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
   array("text" => _("进行中会议"), "href" => "query.php?M_STATUS=2", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif"),
   array("text" => _("未批准会议"), "href" => "query.php?M_STATUS=3", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/meeting.gif")
);

include_once("inc/menu_top.php");
?>
