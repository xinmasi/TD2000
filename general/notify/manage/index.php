<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

//如果默认显示的frame与$MENU_TOP[0]['href']不一致，请设置$MENU_TOP_CONFIGS['href']
$MENU_TOP_CONFIGS = Array(
    'href' => 'index1.php'
);

//修改事务提醒状态--yc
update_sms_status('1',0);

$HTML_PAGE_TITLE = _("公告通知列表");
include_once("./menu_top.php");
?>