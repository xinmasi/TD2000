<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="ID,NAME,MEMBER,RELATIONSHIP,BIRTHDAY,POLITICS,JOB_OCCUPATION,POST_OF_JOB,PERSONAL_TEL,HOME_TEL,OFFICE_TEL,WORK_UNIT,UNIT_ADDRESS,HOME_ADDRESS,MEMO";
else
   $EXCEL_OUT=array(_("�û���"),_("��λԱ��"),_("��Ա����"),_("�뱾�˹�ϵ"),_("��������"),_("������ò"),_("ְҵ"),_("����ְ��"),_("��ϵ�绰�����ˣ�"),_("��ϵ�绰����ͥ��"),_("��ϵ�绰����λ��"),_("������λ"),_("��λ��ַ"),_("��ͥסַ"),_("��ע"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("Ա������ϵ"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>