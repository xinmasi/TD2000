<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("项目问题");

//修改事务提醒状态--yc
update_sms_status('42',0);

include_once("menu_top.php");
?>