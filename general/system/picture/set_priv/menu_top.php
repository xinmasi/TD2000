<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("�ϴ�Ȩ��"), "href" => "set_upuser.php?PIC_ID=$PIC_ID&IS_MAIN=$IS_MAIN", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/sys_config.gif"),
   array("text" => _("����Ȩ��"), "href" => "set_deluser.php?PIC_ID=$PIC_ID&IS_MAIN=$IS_MAIN", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/sys_config.gif"),
);

include_once("inc/menu_top.php");
?>
