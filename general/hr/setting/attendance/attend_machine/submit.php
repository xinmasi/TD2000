<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("考勤机数据库设置");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
/*
$ACCESS_PATH//文件路径
$DATABASE_IP//服务器IP
$DATABASE_PORT//考勤机数据库端口
$DATABASE_NAME//数据库名称
$DATABASE_USER//用户名
$DATABASE_PASS//密码
$DUTY_TABLE//数据存储表表名
$DUTY_USER//用户字段名
$DUTY_TIME//打卡时间字段名
*/

if($DATABASE_TYPE =="sqlserver")
{
	$DATABASE_IP   = $DATABASE_IP_SQL;
	$DATABASE_PORT = $DATABASE_PORT_SQL;
	$DATABASE_NAME = $DATABASE_NAME_SQL;//数据库名称
	$DATABASE_USER = $DATABASE_USER_SQL;
	$DATABASE_PASS = $DATABASE_PASS_SQL;//密码
	
	$DUTY_TABLE    = $DUTY_TABLE_SQL;//数据存储表表名
	$DUTY_USER     = $DUTY_USER_SQL;//用户字段名
	$DUTY_TIME     = $DUTY_TIME_SQL;//打卡时间字段名
}

$query="update ATTEND_MACHINE set MACHINE_BRAND='$MACHINE_BRAND',DATABASE_TYPE='$DATABASE_TYPE',ACCESS_PATH='$ACCESS_PATH',DATABASE_IP='$DATABASE_IP',DATABASE_PORT='$DATABASE_PORT',DATABASE_USER='$DATABASE_USER',DATABASE_PASS='$DATABASE_PASS',DUTY_TABLE='$DUTY_TABLE',DUTY_USER='$DUTY_USER',DUTY_TIME='$DUTY_TIME',DATABASE_NAME='$DATABASE_NAME' where MACHINEID=1";
exequery(TD::conn(),$query);

header("location: index.php");
?>
</body>
</html>
