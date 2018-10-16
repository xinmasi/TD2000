<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$query="select POSITION,EMPLOYEE_MAJOR,EMPLOYEE_PHONE,PLAN_NO from HR_RECRUIT_POOL where EXPERT_ID='$EXPERT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  $POSITION=$ROW["POSITION"];
  $EMPLOYEE_MAJOR=$ROW["EMPLOYEE_MAJOR"];
  $EMPLOYEE_PHONE=$ROW["EMPLOYEE_PHONE"];
  $PLAN_NO=$ROW["PLAN_NO"];  
  
  $query1 = "SELECT PLAN_NAME from HR_RECRUIT_PLAN where PLAN_NO='$PLAN_NO'";
  $cursor1= exequery(TD::conn(),$query1);
  if($ROW1=mysql_fetch_array($cursor1))
     $PLAN_NAME=$ROW1["PLAN_NAME"];   
}
$POSITION=get_hrms_code_name($POSITION,"POOL_POSITION");
$EMPLOYEE_MAJOR=get_hrms_code_name($EMPLOYEE_MAJOR,"POOL_EMPLOYEE_MAJOR");
ob_end_clean();
echo $POSITION.";".$EMPLOYEE_MAJOR.";".$EMPLOYEE_PHONE.";".$PLAN_NAME.";".$PLAN_NO;
?>