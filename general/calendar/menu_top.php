<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("�ҵ��ճ�"), "href" => "arrange/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/calendar.gif"),
   array("text" => _("����������"), "href" => "affair/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/calendar.gif"),
   array("text" => _("�ҵ�����"), "href" => "task/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/calendar.gif"),
   array("text" => _("����ʱ��"), "href" => "counttime/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/calendar.gif"),
   array("text" => _("����/����"), "href" => "in_out/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/inout.gif")
);

$MENU_RIGHT=help('007','skill/erp/calendar/');

include_once("inc/menu_top.php");
?>
