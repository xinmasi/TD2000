<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="NAME,ID,WELFARE_ITEM,PAYMENT_DATE,WELFARE_MONTH,WELFARE_PAYMENT,TAX_AFFAIRS,FREE_GIFT,MEMO";
else
   $EXCEL_OUT=array(_("����"),_("�û���"),_("������Ŀ"),_("��������"),_("�����·�"),_("�������"),_("�Ƿ���˰"),_("������Ʒ"),_("��ע"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("Ա��������¼"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>