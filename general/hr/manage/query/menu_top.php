<?
include_once("inc/auth.inc.php");


$PRIV_NO_FLAG="2";
$MANAGE_FLAG="1";
$MODULE_ID=10;
include_once("inc/my_priv.php");

$MENU_TOP=array();
$MENU_TOP[]=array("text" => _("在职人员"), "href" => "in_service.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/hrms.gif");

   $MENU_TOP[]=array("text" => _("离职人员"), "href" => "retired.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/hrms.gif");

include_once("inc/menu_top.php");
?>
