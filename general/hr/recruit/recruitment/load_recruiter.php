<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$query="select POSITION,EMPLOYEE_MAJOR,EMPLOYEE_PHONE from HR_RECRUIT_POOL where EXPERT_ID='$EXPERT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  $POSITION=$ROW["POSITION"];
  $EMPLOYEE_MAJOR=$ROW["EMPLOYEE_MAJOR"];
  $EMPLOYEE_PHONE=$ROW["EMPLOYEE_PHONE"];
  
  $POSITION=get_hrms_code_name($POSITION,"POOL_POSITION");
}
ob_end_clean();
echo $POSITION.";".$EMPLOYEE_MAJOR.";".$EMPLOYEE_PHONE;
?>