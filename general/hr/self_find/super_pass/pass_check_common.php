<?
include_once("inc/auth.inc.php");
$HTML_PAGE_TITLE = _("修改密码");
include_once("inc/header.inc.php");
if (!isset($_SESSION["SALARY_PASS_FLAG"]))
{
    Message(_("错误"), _("您输入的超级密码不正确！"));
    exit;
}
?>