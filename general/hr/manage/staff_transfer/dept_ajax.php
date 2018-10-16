<?
include_once("inc/auth.inc.php");

$TRANSFER_PERSON=$_GET["TRANSFER_PERSON"];

$query_position = "select JOB_POSITION from HR_STAFF_INFO where USER_ID='$TRANSFER_PERSON'";
$cursor_position= exequery(TD::conn(),$query_position); 
if($ROW_POSITION=mysql_fetch_array($cursor_position)) 
{ 
  $JOB_POSITION=$ROW_POSITION['JOB_POSITION']; 
} 
ob_end_clean();
echo $JOB_POSITION;
?>