<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("������Ƹ�ƻ�"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/news.gif"),
   array("text" => _("����׼��Ƹ�ƻ�"), "href" => "pass_app.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/hr_recruit.gif"),
   array("text" => _("δ��׼��Ƹ�ƻ�"), "href" => "failed_app.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/hr_recruit.gif"),
   //array("text" => _("��ͨ�������ƻ�"), "href" => "pass_app.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif"),
   array("text" => _("��Ƹ�ƻ���ѯ"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif")
);

include_once("inc/menu_top.php");
?>
