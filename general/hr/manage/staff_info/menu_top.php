<?
include_once("inc/auth.inc.php");

$PRIV_NO_FLAG="2";
$MANAGE_FLAG="1";
$MODULE_ID=9;
include_once("inc/my_priv.php");

$MENU_TOP=array();
$MENU_TOP[]=array("text" => _("���µ�������(��ְ)"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/hr_manage.gif");

$MENU_TOP[]=array("text" => _("���µ�������(��ְ)"), "href" => "index2.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/hr_manage.gif");

$MENU_TOP[]=array("text" => _("���µ�����ѯ"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif");
$MENU_TOP[]=array("text" => _("δ�����µ�����Ա��ѯ"), "href" => "search1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif");

include_once("inc/menu_top.php");
?>
