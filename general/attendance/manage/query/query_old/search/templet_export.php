<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

if(MYOA_IS_UN == 1)
 $EXCEL_OUT="ID,REGISTRATION_TIME,REGISTER_IP,MEMO";
else
 $EXCEL_OUT=array(_("�û���"),_("�Ǽ�ʱ��"),_("�Ǽ�IP"),_("��ע"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("����ģ��"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>