<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
    $EXCEL_OUT=array("Person_onduty","Start_time","End_time","Scheduling_type","Duty_type","Duty_requirement","Remarks","Duty_log");
else
    $EXCEL_OUT=array(_("ֵ����"),_("ֵ�࿪ʼʱ��"),_("ֵ�����ʱ��"),_("�Ű�����"),_("ֵ������"),_("ֵ��Ҫ��"),_("��ע"),_("ֵ����־"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("�Ű�ģ��"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>