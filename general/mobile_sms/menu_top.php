<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("�����ֻ�����"), "href" => "new/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("���ŷ��͹���"), "href" => "send_manage/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/msg_fwd.gif"),
   array("text" => _("���ն��Ų�ѯ"), "href" => "receive_manage/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/msg_back.gif")
);

include_once("inc/menu_top.php");
?>
