<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
    $EXCEL_OUT="DEPARTMENT,BOOK_NAME,AUTHOR,BOOK_NO,BOOK_TYPE,ISBN,PUBLISH_HOUSE,PUBLISH_DATE,PLACE,AMOUNT,PRICE,BRIEF,OPEN,BORR_PERSON,MEMO";
else
    $EXCEL_OUT=array(_("����"),_("����"),_("����"),_("ͼ����"),_("ͼ�����"),_("ISBN��"),_("������"),_("��������"),_("��ŵص�"),_("����"),_("�۸�"),_("���ݼ��"),_("���ķ�Χ"),_("¼����"),_("��ע"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("ͼ����Ϣ����ģ��"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>