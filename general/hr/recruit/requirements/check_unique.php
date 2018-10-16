<?
include_once("inc/auth.inc.php");
ob_end_clean();
$query="select count(*)  from HR_RECRUIT_REQUIREMENTS where REQU_NO='$REQU_NO'";
$cursor=exequery(TD::conn(),$query);
$COUNT=0;
if($ROW=mysql_fetch_array($cursor))
	$COUNT=$ROW[0];
if($COUNT==0)
	echo "SUCCESS";
else
	echo "FAILED";
?>