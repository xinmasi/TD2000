<?
include_once("inc/auth.inc.php");
include_once("inc/utility_cache.php");

$HTML_PAGE_TITLE = ($concern_id!="" ? _("�༭��ע��Ա") : _("�½���ע��Ա"));
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
//------------------- ���� -----------------------
if($group_id == "")
{
    Message(_("����"),_("��ѡ����Ա��ע�Ĺ�ע��"));
    Button_Back();
    exit;
}

if($concern_user == "")
{
    Message(_("����"),_("��ѡ���ע����Ա"));
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
    Message(_("����"), $HTML_PAGE_TITLE._("ʧ��"));
    Button_Back();
    exit;
}
else
{
    Message("", $HTML_PAGE_TITLE._("�ɹ�"));
    Button_Back();
}

updateUserCache($_SESSION["LOGIN_UID"]);

//header("location: index.php?IS_MAIN=1");
?>
</body>
</html>
