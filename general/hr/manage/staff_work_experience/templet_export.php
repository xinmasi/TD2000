<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="ID,NAME,POST_OF_JOB,WORK_BRANCH,WITNESS,START_DATE,END_DATE,MOBILE,WORK_UNIT,WORK_CONTENT,REASON_FOR_LEAVING,MEMO,KEY_PERFORMANCE";
else
   $EXCEL_OUT=array(_("�û���"),_("��λԱ��"),_("����ְ��"),_("���ڲ���"),_("֤����"),_("��ʼ����"),_("��������"),_("��ҵ���"),_("������λ"),_("��������"),_("��ְԭ��"),_("��ע"),_("��Ҫҵ��"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("Ա����������"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>