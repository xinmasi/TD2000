<?
include_once("inc/auth.inc.php");
include_once("inc/utility_cache.php");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?

$query="update USER_GROUP set USER_STR='$TO_ID' where GROUP_ID='$GROUP_ID' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
exequery(TD::conn(), $query);

updateUserCache($_SESSION["LOGIN_UID"]);

Message("",_("保存成功"), "", array(array('value' => _("返回"), 'href' => 'index.php?IS_MAIN=1')));

?>

</body>
</html>