<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="BY_EVALU_STAFFS,ID,APPROVE_PERSON,POST_NAME,GET_METHOD,REPORT_TIME,RECEIVE_TIME,APPROVE_NEXT,APPROVE_NEXT_TIME,EMPLOY_POST,EMPLOY_COMPANY,START_DATE,END_DATE,EVALUATION_DETAILS";
else
   $EXCEL_OUT=array(_("评定对象"),_("评定对象用户名"),_("批准人"),_("获取职称"),_("获取方式"),_("申报时间"),_("获取时间"),_("下次申报职称"),_("下次申报时间"),_("聘用职务"),_("聘用单位"),_("聘用开始时间"),_("聘用结束时间"),_("评定详情"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("员工职称评定"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>