<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$query="select PLAN_ID from WORK_PLAN where TYPE ='$TYPE_ID'";

$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor)) 			
{ 
	Message(_("����"),_("�������ѱ�ʹ�ã�������ɾ����"));
    Button_Back();
	exit;
}
else
{
	$query="delete from PLAN_TYPE where TYPE_ID='$TYPE_ID'";
    exequery(TD::conn(),$query); 
	header("location: index.php"); 
}

?>
