<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
    $EXCEL_OUT="ID,NAME,MAJOR,ACADEMY_DEGREE,DEGREE,POSITION,WITNESS,SCHOOL,SCHOOL_ADDRESS,START_DATE,END_DATE,AWARDING,CERTIFICATES,MEMO";
else
    $EXCEL_OUT=array(_("�û���"),_("��λԱ��"),_("��ѧרҵ"),_("����ѧ��"),_("����ѧλ"),_("���ΰ��"),_("֤����"),_("����ԺУ"),_("ԺУ���ڵ�"),_("��ʼ����"),_("��������"),_("�����"),_("����֤��"),_("��ע"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("Ա��ѧϰ����"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>