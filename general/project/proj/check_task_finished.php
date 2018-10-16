<?
/**
 * ajax检查一个项目的所有下属任务是否都已结束 by dq 090629
 * TASK_STATUS='0'，表示任务处于_("进行中")状态
 */

include_once("inc/auth.inc.php");
ob_end_clean();

$COUNTER = 0;
$query = "select COUNT(*) from PROJ_TASK where PROJ_ID='$PROJ_ID' and TASK_STATUS='0'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $COUNTER = $ROW[0];

if($COUNTER > 0)
{
   echo "-ERR";
}
else
{
   echo "+OK";
}
?>


