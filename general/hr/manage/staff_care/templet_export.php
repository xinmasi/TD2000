<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
    $EXCEL_OUT="TYPE,FEES,ID,NAME,DATE,EFFECTS,PARTICIPANTS,CONTENT";
else
    $EXCEL_OUT=array(_("�ػ�����"),_("�ػ���֧����/��"),_("���ػ�Ա���û���"),_("���ػ�Ա��"),_("�ػ�����"),_("�ػ�Ч��"),_("�����ˣ����Ķ��Ÿ�����"),_("�ػ�����"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("Ա���ػ���Ϣ"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>