<?
include_once("inc/auth.inc.php");
include_once("inc/utility_cache.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$sql = "SELECT * FROM user_group WHERE GROUP_NAME = '$GROUP_NAME' AND USER_ID = '".$_SESSION["LOGIN_USER_ID"]."' AND GROUP_ID!='$GROUP_ID'";
$arr = exequery(TD::conn(),$sql);
if(mysql_affected_rows()>0)
{
	Message(_("错误"),_("自定义用户组名称重复"));
	Button_Back();
	exit;
}

$query="update USER_GROUP set GROUP_NAME='$GROUP_NAME',ORDER_NO='$ORDER_NO' where GROUP_ID='$GROUP_ID' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
exequery(TD::conn(),$query);

updateUserCache($_SESSION["LOGIN_UID"]);

header("location: index.php?IS_MAIN=1");
?>
</body>
</html>
