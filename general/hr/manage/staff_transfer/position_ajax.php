<?
include_once("inc/auth.inc.php");

$USER_ID=$_GET["TRANSFER_PERSON"];

$query_position = "select JOB_POSITION HR_STAFF_INFO from  where USER_ID='$USER_ID'";
$cursor_position= exequery(TD::conn(),$query_position); 
if($ROW_POSITION=mysql_fetch_array($cursor_position)) 
{ 
  $JOB_POSITION=$ROW_POSITION['JOB_POSITION']; 
} 
ob_end_clean();
echo $JOB_POSITION;
?>