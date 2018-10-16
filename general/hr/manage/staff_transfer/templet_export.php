<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="USER_ID,TRANSFER_PERSON,TRANSFER_TYPE,TRANSFER_DATE,TRANSFER_EFFECTIVE_DATE,TRAN_COMPANY_BEFORE,TRAN_COMPANY_AFTER,TRAN_POSITION_BEFORE,TRAN_POSITION_AFTER,TRAN_DEPT_BEFORE,TRAN_DEPT_AFTER,MATERIALS_CONDITION,REMARK,SMS_REMIND,TRAN_REASON";
else
   $EXCEL_OUT=array(_("用户名"),_("调动人员"),_("调动类型"),_("调动日期"),_("调动生效日期"),_("调动前单位"),_("调动后单位"),_("调动前职务"),_("调动后职务"),_("调动前部门"),_("调动后部门"),_("调动手续办理"),_("备注"),_("发送事务提醒"),_("调动原因"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("人事调动"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>