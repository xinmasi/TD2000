<?
include_once("inc/auth.inc.php");
include_once("inc/utility_cache.php");

$HTML_PAGE_TITLE = ($concern_id!="" ? _("编辑关注人员") : _("新建关注人员"));
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
//------------------- 保存 -----------------------
if($group_id == "")
{
    Message(_("错误"),_("请选择人员关注的关注组"));
    Button_Back();
    exit;
}

if($concern_user == "")
{
    Message(_("错误"),_("请选择关注的人员"));
    Button_Back();
    exit;
}

$concern_user = str_replace($_SESSION["LOGIN_USER_ID"] . "," , "", $concern_user);

if($concern_id == "")
{
    $sql = "INSERT INTO concern_user (user_id,group_id,concern_user) VALUES ('".$_SESSION["LOGIN_USER_ID"]."','$group_id','$concern_user')";
}
else
{
    $sql = "UPDATE concern_user SET concern_user='$concern_user' WHERE concern_id = '$concern_id'";
}
$cursor = exequery(TD::conn(), $sql);
if(!$cursor)
{
    Message(_("错误"), $HTML_PAGE_TITLE._("失败"));
    Button_Back();
    exit;
}
else
{
    Message("", $HTML_PAGE_TITLE._("成功"));
    Button_Back();
}

updateUserCache($_SESSION["LOGIN_UID"]);

//header("location: index.php?IS_MAIN=1");
?>
</body>
</html>
