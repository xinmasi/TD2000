<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
 $EXCEL_OUT="FILE_CODE,FILE_SUBJECT,TITLE,TITLE0,SEND_UNIT,SEND_DATE,SECRET,URGENCY,TYPE,KIND,FILE_PAGE,PRINT_PAGE,MEMO,ROLL_ID";
else
 $EXCEL_OUT=array(_("�ļ���"),_("�ļ������"),_("�ļ�����"),_("�ļ�������"),_("���ĵ�λ"),_("��������"),_("�ܼ�"),_("�����ȼ�"),_("�ļ�����"),_("�������"),_("�ļ�ҳ��"),_("��ӡҳ��"),_("��ע"),_("������������"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("�ļ�����"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>
