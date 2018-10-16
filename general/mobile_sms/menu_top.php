<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("发送手机短信"), "href" => "new/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("短信发送管理"), "href" => "send_manage/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/msg_fwd.gif"),
   array("text" => _("接收短信查询"), "href" => "receive_manage/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/msg_back.gif")
);

include_once("inc/menu_top.php");
?>
