<?
include_once("inc/auth.inc.php");
include_once("inc/utility_cache.php");

$HTML_PAGE_TITLE = _("编辑个人资料");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
//------------------- 保存 -----------------------

$CONCERN_USER = str_replace($_SESSION["LOGIN_USER_ID"] . "," , "", $CONCERN_USER);

$query ="update USER_EXT set CONCERN_USER='$CONCERN_USER' ";
$query.="where UID='".$_SESSION["LOGIN_UID"]."'";

exequery(TD::conn(),$query);

updateUserCache($_SESSION["LOGIN_UID"]);

Message(_("提示"),_("已保存修改"));
?>

<div align="center">
 <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index.php?IS_MAIN=1'">
</div>

</body>
</html>
