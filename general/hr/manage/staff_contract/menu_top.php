<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("��ͬ����"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/news.gif"),
   array("text" => _("�½���ͬ��Ϣ"), "href" => "new.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("��ͬ��Ϣ��ѯ"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif"),
   array("text" => _("�������뵼��"), "href" => "in_out_index.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/inout.gif"),
   array("text" => _("���õ��ڲ�ѯ"), "href" => "due_query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif"),
   array("text" => _("��ͬ���ڲ�ѯ"), "href" => "due_query1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif"),
   array("text" => _("δǩ�Ͷ���ͬ"), "href" => "no_contract.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif")
);

include_once("inc/menu_top.php");
?>
