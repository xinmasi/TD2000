<?
include_once("inc/auth.inc.php");


$MENU_TOP=array(
   array("text" => _("�����ƻ�"), "href" => "index1.php?ASSESSING_STATUS=0", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/hr_training.gif"),
   array("text" => _("��׼��¼"), "href" => "index1.php?ASSESSING_STATUS=1", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/hr_training.gif"),
   array("text" => _("δ׼��¼"), "href" => "index1.php?ASSESSING_STATUS=2", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/hr_training.gif"),
   array("text" => _("�ƻ�����������ѯ"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/hr_training.gif")
);

include_once("inc/menu_top.php");
?>
