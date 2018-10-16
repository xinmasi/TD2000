<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$query="select PLAN_ID from WORK_PLAN";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor)) 			
{ 
	Message(_("警告"),_("有类型已使用，不能全部删除！"));
	Button_Back();
	exit;
}
else
{
	$query="delete from PLAN_TYPE";
    exequery(TD::conn(),$query);
	header("location: index.php");
}


?>