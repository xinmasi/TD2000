<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
    $EXCEL_OUT="ID,NAME,ITEM,DATE,SALARY_MONTH,TYPE,AMOUNT,DESCRIPTION,MEMO";
else
    $EXCEL_OUT=array(_("�û���"),_("��λԱ��"),_("������Ŀ"),_("��������"),_("�����·�"),_("��������"),_("���ͽ�Ԫ��"),_("����˵��"),_("��ע"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("���͹�����Ϣ"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>