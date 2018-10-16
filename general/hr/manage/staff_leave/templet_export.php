<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="USER_ID,LEAVE_PERSON,POSITION,QUIT_TYPE,APPLICATION_DATE,QUIT_TIME_PLAN,QUIT_TIME_FACT,LAST_SALARY_TIME,LEAVE_DEPT,TRACE,MATERIALS_CONDITION,REMARK,TO_NAME,QUIT_REASON,SALARY";
else
   $EXCEL_OUT=array(_("用户名"),_("离职人员"),_("担任职务"),_("离职类型"),_("申请日期"),_("拟离职日期"),_("实际离职日期"),_("工资截止日期"),_("离职部门"),_("去向"),_("离职手续办理"),_("备注"),_("提醒人员"),_("离职原因"),_("离职当月薪资"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("员工离职"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>