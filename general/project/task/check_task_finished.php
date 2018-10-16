<?
/**
 * ajax检查任务是否已完成100% by dq 090629
 */

include_once("inc/auth.inc.php");
ob_end_clean();

$query = "SELECT TASK_PERCENT_COMPLETE from PROJ_TASK where TASK_ID='$TASK_ID'";
$cursor = exequery(TD::conn(), $query);
if($ROW = mysql_fetch_array($cursor))
   $PERCENT_MAX = $ROW["TASK_PERCENT_COMPLETE"];

if($PERCENT_MAX < 100)
{
   echo "-ERR";
}
else
{
   echo "+OK";
}
?>


