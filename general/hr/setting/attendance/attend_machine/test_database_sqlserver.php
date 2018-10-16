<?
include_once("inc/auth.inc.php");

$dbserver=stripslashes($DATABASE_IP);
//$connectionInfo = array( "Database"=>$DATABASE_NAME,"UID"=>$DATABASE_USER,"PWD"=>$DATABASE_PASS);
//$conn = sqlsrv_connect($dbserver,$connectionInfo);

$conn = odbc_connect("Driver={SQL Server};Server=".$DATABASE_IP.";", $DATABASE_USER, $DATABASE_PASS);
if(!$conn)
{
    echo "+false";
    exit;
}else
{
    echo "+OK";
}
?>