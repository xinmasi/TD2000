<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

//���Ĭ����ʾ��frame��$MENU_TOP[0]['href']��һ�£�������$MENU_TOP_CONFIGS['href']
$MENU_TOP_CONFIGS = Array(
    'href' => 'index1.php'
);

//�޸���������״̬--yc
update_sms_status('1',0);

$HTML_PAGE_TITLE = _("����֪ͨ�б�");
include_once("./menu_top.php");
?>