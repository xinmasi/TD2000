<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("���ά��"), "href" => "query_list.php?action=one", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER." "),
   array("text" => _("���Ǽ�"), "href" => "query_list.php?action=two", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER." "),
   array("text" => _("����"), "href" => "change_index.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER." "),
   array("text" => _("�칫��Ʒ��ѯ"), "href" => "office_query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER." ")
);

include_once("inc/menu_top.php");
?>
