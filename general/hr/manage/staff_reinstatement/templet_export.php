<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="USER_ID,REINSTATEMENT_PERSON,REAPPOINTMENT_TYPE,APPLICATION_DATE,NOW_POSITION,REAPPOINTMENT_TIME_PLAN,REAPPOINTMENT_TIME_FACT,FIRST_SALARY_TIME,REAPPOINTMENT_DEPT,MATERIALS_CONDITION,REMARK,REAPPOINTMENT_STATE";
else
   $EXCEL_OUT=array(_("�û���"),_("��ְ��Ա"),_("��ְ����"),_("��������"),_("����ְ��"),_("�⸴ְ����"),_("ʵ�ʸ�ְ����"),_("���ʻָ�����"),_("��ְ����"),_("��ְ��������"),_("��ע"),_("��ְ˵��"));
require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("Ա����ְ"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>