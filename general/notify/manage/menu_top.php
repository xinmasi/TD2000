<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");

$MENU_TOP=array(
   array("text" => _("�������"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/notify.gif"),
   array("text" => _("�½�����"), "href" => "new.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("�����ѯ"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif"),
   array("text" => _("����ͳ��"), "href" => "statistics.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif")
);

$is_notify_manager = is_module_manager(3);
if(!$is_notify_manager && $_SESSION['LOGIN_USER_PRIV']!='1' && !find_id($_SESSION['LOGIN_USER_PRIV_OTHER'], "1"))
{
    unset($MENU_TOP[3]);
}

include_once("inc/menu_top.php");
?>
