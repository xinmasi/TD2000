<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("����������"), "href" => "unaudited.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/notify.gif"),
   array("text" => _("����������"), "href" => "audited.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif")
  // array("text" => _("������ѯ"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif")
);

include_once("inc/menu_top.php");
?>
