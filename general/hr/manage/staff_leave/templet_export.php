<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="USER_ID,LEAVE_PERSON,POSITION,QUIT_TYPE,APPLICATION_DATE,QUIT_TIME_PLAN,QUIT_TIME_FACT,LAST_SALARY_TIME,LEAVE_DEPT,TRACE,MATERIALS_CONDITION,REMARK,TO_NAME,QUIT_REASON,SALARY";
else
   $EXCEL_OUT=array(_("�û���"),_("��ְ��Ա"),_("����ְ��"),_("��ְ����"),_("��������"),_("����ְ����"),_("ʵ����ְ����"),_("���ʽ�ֹ����"),_("��ְ����"),_("ȥ��"),_("��ְ��������"),_("��ע"),_("������Ա"),_("��ְԭ��"),_("��ְ����н��"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("Ա����ְ"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>