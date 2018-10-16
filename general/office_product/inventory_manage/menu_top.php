<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("库存维护"), "href" => "query_list.php?action=one", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER." "),
   array("text" => _("代登记"), "href" => "query_list.php?action=two", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER." "),
   array("text" => _("调拨"), "href" => "change_index.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER." "),
   array("text" => _("办公用品查询"), "href" => "office_query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER." ")
);

include_once("inc/menu_top.php");
?>
