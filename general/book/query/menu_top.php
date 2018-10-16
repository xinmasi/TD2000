<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("Í¼Êé²éÑ¯"), "href" => "search.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif"),
   array("text" => _("´ýÅú½èÔÄ"), "href" => "query.php?STATUS=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/book.gif"),
   array("text" => _("ÒÑ×¼½èÔÄ"), "href" => "query.php?STATUS=1", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/book.gif"),
   array("text" => _("Î´×¼½èÔÄ"), "href" => "query.php?STATUS=2", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/book.gif")
);

include_once("inc/menu_top.php");
?>
