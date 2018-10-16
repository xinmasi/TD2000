<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_cache.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
// $PARA_ARRAY=get_sys_para("NOTIFY_EDIT_PRIV");

if($_SESSION["LOGIN_USER_PRIV_TYPE"] == "1")
{
    $query="select ATTACHMENT_ID,ATTACHMENT_NAME from NOTIFY where ATTACHMENT_NAME!=''";
    $cursor=exequery(TD::conn(),$query);
    while($ROW=mysql_fetch_array($cursor))
    {
        $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
        $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

        delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
    }

    $query="delete from NOTIFY";
    exequery(TD::conn(),$query);

    delete_remind_sms(1);
}

// delete_remind_sms(1, $_SESSION["LOGIN_USER_ID"]);

add_log(15, _("删除所有公告通知"), $_SESSION["LOGIN_USER_ID"]);
header("location: index1.php?IS_MAIN=1");
?>

</body>
</html>