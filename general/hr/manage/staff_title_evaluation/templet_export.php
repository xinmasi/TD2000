<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="BY_EVALU_STAFFS,ID,APPROVE_PERSON,POST_NAME,GET_METHOD,REPORT_TIME,RECEIVE_TIME,APPROVE_NEXT,APPROVE_NEXT_TIME,EMPLOY_POST,EMPLOY_COMPANY,START_DATE,END_DATE,EVALUATION_DETAILS";
else
   $EXCEL_OUT=array(_("��������"),_("���������û���"),_("��׼��"),_("��ȡְ��"),_("��ȡ��ʽ"),_("�걨ʱ��"),_("��ȡʱ��"),_("�´��걨ְ��"),_("�´��걨ʱ��"),_("Ƹ��ְ��"),_("Ƹ�õ�λ"),_("Ƹ�ÿ�ʼʱ��"),_("Ƹ�ý���ʱ��"),_("��������"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("Ա��ְ������"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>