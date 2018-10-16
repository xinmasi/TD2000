<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="USER_ID,REINSTATEMENT_PERSON,REAPPOINTMENT_TYPE,APPLICATION_DATE,NOW_POSITION,REAPPOINTMENT_TIME_PLAN,REAPPOINTMENT_TIME_FACT,FIRST_SALARY_TIME,REAPPOINTMENT_DEPT,MATERIALS_CONDITION,REMARK,REAPPOINTMENT_STATE";
else
   $EXCEL_OUT=array(_("用户名"),_("复职人员"),_("复职类型"),_("申请日期"),_("担任职务"),_("拟复职日期"),_("实际复职日期"),_("工资恢复日期"),_("复职部门"),_("复职手续办理"),_("备注"),_("复职说明"));
require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("员工复职"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>