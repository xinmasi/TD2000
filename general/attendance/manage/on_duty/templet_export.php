<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
    $EXCEL_OUT=array("Person_onduty","Start_time","End_time","Scheduling_type","Duty_type","Duty_requirement","Remarks","Duty_log");
else
    $EXCEL_OUT=array(_("值班人"),_("值班开始时间"),_("值班结束时间"),_("排班类型"),_("值班类型"),_("值班要求"),_("备注"),_("值班日志"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("排班模板"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>