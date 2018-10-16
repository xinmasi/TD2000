<?
include_once("inc/auth.inc.php");
ob_end_clean();
$PROJ_NUM=td_htmlspecialchars($PROJ_NUM);
$query="select PROJ_NUM from `PROJ_PROJECT` where PROJ_NUM='$PROJ_NUM' and PROJ_ID<>'$PROJ_ID'";
//echo $query;
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	echo _("项目编号不能与其他项目重复!");
}
else
{
	echo "OK";
}
?>