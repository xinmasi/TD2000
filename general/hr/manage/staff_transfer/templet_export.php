<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="USER_ID,TRANSFER_PERSON,TRANSFER_TYPE,TRANSFER_DATE,TRANSFER_EFFECTIVE_DATE,TRAN_COMPANY_BEFORE,TRAN_COMPANY_AFTER,TRAN_POSITION_BEFORE,TRAN_POSITION_AFTER,TRAN_DEPT_BEFORE,TRAN_DEPT_AFTER,MATERIALS_CONDITION,REMARK,SMS_REMIND,TRAN_REASON";
else
   $EXCEL_OUT=array(_("�û���"),_("������Ա"),_("��������"),_("��������"),_("������Ч����"),_("����ǰ��λ"),_("������λ"),_("����ǰְ��"),_("������ְ��"),_("����ǰ����"),_("��������"),_("������������"),_("��ע"),_("������������"),_("����ԭ��"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("���µ���"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>