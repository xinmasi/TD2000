<?
include_once("inc/auth.inc.php");
 
//include_once("./salary_pass/pass_check_common.php");

$MENU_TOP=array(
   array("text" => _("���ʴ�������"), "href" => "run/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/salary.gif"),
   array("text" => _("������ʷ����"), "href" => "history/", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/salary.gif"),
   array("text" => _("��������"), "href" => "salary_pass/pass_check_common.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/salary.gif")
);

include_once("inc/menu_top.php");
?>
