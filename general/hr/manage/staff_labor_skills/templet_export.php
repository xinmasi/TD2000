<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
    $EXCEL_OUT="ID,NAME,ABILITY_NAME,SPECIAL_WORK,SKILLS_LEVEL,SKILLS_CERTIFICATE,ISSUE_DATE,EXPIRES,EXPIRE_DATE,ISSUING_AUTHORITY,MEMO";
else
    $EXCEL_OUT=array(_("�û���"),_("��λԱ��"),_("��������"),_("�Ƿ�������ҵ"),_("����"),_("�Ƿ��м���֤"),_("��֤����"),_("��Ч��"),_("��������"),_("��֤����/��λ"),_("��ע"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("Ա���Ͷ�����"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>