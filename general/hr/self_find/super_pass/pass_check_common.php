<?
include_once("inc/auth.inc.php");
$HTML_PAGE_TITLE = _("�޸�����");
include_once("inc/header.inc.php");
if (!isset($_SESSION["SALARY_PASS_FLAG"]))
{
    Message(_("����"), _("������ĳ������벻��ȷ��"));
    exit;
}
?>