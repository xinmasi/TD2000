<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
    $EXCEL_OUT="USER_ID,STAFF_NAME,STAFF_CONTRACT_NO,CONTRACT_TYPE,STATUS,CONTRACT_SPECIALIZATION,CONTRACT_ENTERPRIES,MAKE_CONTRACT,PROBATION_EFFECTIVE_DATE,CONTRACT_END_TIME,IS_TRIAL,TRAIL_OVER_TIME,PASS_OR_NOT,REMOVE_OR_NOT,CONTRACT_REMOVE_TIME,IS_RENEW,RENEW_TIME,REMARK";
else
    $EXCEL_OUT=array(_("�û���"),_("����"),_("��ͬ���"),_("��ͬ����"),_("��ͬ״̬"),_("��ͬ��������"),_("��ͬǩԼ��˾"),_("��ͬǩ������"),_("��ͬ��Ч����"),_("��ͬ��ֹ����"),_("�Ƿ�������"),_("���ý�ֹ����"),_("��Ա�Ƿ�ת��"),_("��ͬ�Ƿ��ѽ��"),_("��ͬ�������"),_("��ͬ�Ƿ���ǩ"),_("��ǩ��������"),_("��ע"));
//CREATE_USER_ID��CREATE_DEPT_ID
require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("��ͬ����ģ��"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>