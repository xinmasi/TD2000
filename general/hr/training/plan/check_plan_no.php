<?
include_once("inc/auth.inc.php");
ob_end_clean();

$query = "SELECT * from HR_TRAINING_PLAN where T_PLAN_NO='$T_PLAN_NO'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   echo "-ERR";
else
   echo "+OK";
?>