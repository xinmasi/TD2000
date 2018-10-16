<?
include_once("inc/auth.inc.php");

$PRIV_NO_FLAG="2";
$MANAGE_FLAG="1";
$MODULE_ID=9;
include_once("inc/my_priv.php");

$MENU_TOP=array();
$MENU_TOP[]=array("text" => _("人事档案管理(在职)"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/hr_manage.gif");

$MENU_TOP[]=array("text" => _("人事档案管理(离职)"), "href" => "index2.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/hr_manage.gif");

$MENU_TOP[]=array("text" => _("人事档案查询"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif");
$MENU_TOP[]=array("text" => _("未建人事档案人员查询"), "href" => "search1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif");

include_once("inc/menu_top.php");
?>
