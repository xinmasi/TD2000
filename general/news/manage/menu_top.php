<?
include_once("inc/auth.inc.php");

$MENU_TOP=array(
   array("text" => _("���Ź���"), "href" => "index1.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/menu/news.gif"),
   array("text" => _("�½�����"), "href" => "new.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/notify_new.gif"),
   array("text" => _("���Ų�ѯ"), "href" => "query.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/infofind.gif"),
   array("text" => _("����ͳ��"), "href" => "statistics.php", "target" => "", "title" => "", "img" => MYOA_STATIC_SERVER."/static/images/news.gif")
);

if($_SESSION['LOGIN_USER_PRIV']!='1' && !find_id($_SESSION['LOGIN_USER_PRIV_OTHER'], "1"))
{
    unset($MENU_TOP[3]);
}

include_once("inc/menu_top.php");
?>
