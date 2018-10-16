<?
include_once("inc/auth.inc.php");

$conn_mysql = mysql_connect("$DATABASE_IP:$DATABASE_PORT","$DATABASE_USER","$DATABASE_PASS");
if (!$conn_mysql)
{
	echo "+flase";
	exit;
}
$db_selected = mysql_select_db("$DATABASE_NAME",$conn_mysql);
if(!$db_selected){
	echo "-flase";
	exit;
}
echo "+OK";
?>