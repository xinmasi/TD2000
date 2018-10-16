<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
    $EXCEL_OUT="USER_ID,STAFF_NAME,STAFF_CONTRACT_NO,CONTRACT_TYPE,STATUS,CONTRACT_SPECIALIZATION,CONTRACT_ENTERPRIES,MAKE_CONTRACT,PROBATION_EFFECTIVE_DATE,CONTRACT_END_TIME,IS_TRIAL,TRAIL_OVER_TIME,PASS_OR_NOT,REMOVE_OR_NOT,CONTRACT_REMOVE_TIME,IS_RENEW,RENEW_TIME,REMARK";
else
    $EXCEL_OUT=array(_("用户名"),_("姓名"),_("合同编号"),_("合同类型"),_("合同状态"),_("合同期限属性"),_("合同签约公司"),_("合同签订日期"),_("合同生效日期"),_("合同终止日期"),_("是否含试用期"),_("试用截止日期"),_("雇员是否转正"),_("合同是否已解除"),_("合同解除日期"),_("合同是否续签"),_("续签到期日期"),_("备注"));
//CREATE_USER_ID，CREATE_DEPT_ID
require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("合同数据模板"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>