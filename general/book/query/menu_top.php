<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("ͼ���ѯ"), "href" => "search.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif"),
   array("text" => _("��������"), "href" => "query.php?STATUS=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/book.gif"),
   array("text" => _("��׼����"), "href" => "query.php?STATUS=1", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/book.gif"),
   array("text" => _("δ׼����"), "href" => "query.php?STATUS=2", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/book.gif")
);

include_once("inc/menu_top.php");
?>
