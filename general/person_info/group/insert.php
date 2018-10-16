<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$sql = "SELECT * FROM user_group WHERE GROUP_NAME = '$GROUP_NAME' AND USER_ID = '".$_SESSION["LOGIN_USER_ID"]."'";
$arr = exequery(TD::conn(),$sql);
if(mysql_affected_rows()>0)
{
	Message(_("错误"),_("自定义用户组名称重复"));
	Button_Back();
	exit;
}

$query="insert into USER_GROUP (GROUP_NAME, USER_ID, ORDER_NO) values ('$GROUP_NAME', '".$_SESSION["LOGIN_USER_ID"]."', '$ORDER_NO')";
exequery(TD::conn(),$query);

header("location: index.php?IS_MAIN=1");
?>

</body>
</html>
