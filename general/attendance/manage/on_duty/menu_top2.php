<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("�����Ű�"), "href" => "new.php?ZBSJ_B=$ZBSJ_B", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/calendar2.gif"),
   array("text" => _("�����Ű�"), "href" => "more_new.php?ZBSJ_B=$ZBSJ_B", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/calendar2.gif"),
   array("text" => _("�Űർ��"), "href" => "import.php?ZBSJ_B=$ZBSJ_B", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/sys_config.gif")
);

include_once("inc/menu_top.php");
?>
