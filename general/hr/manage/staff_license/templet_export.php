<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="ID,NAME,LICENSE_TYPE,LICENSE_NO,LICENSE_NAME,GET_LICENSE_DATE,EFFECTIVE_DATE,STATUS,EXPIRATION_PERIOD,EXPIRE_DATE,NOTIFIED_BODY,LICENSE_DEPT,MEMO";
else
   $EXCEL_OUT=array(_("�û���"),_("��λԱ��"),_("֤������"),_("֤�ձ��"),_("֤������"),_("ȡ֤����"),_("��Ч����"),_("״̬"),_("�Ƿ���������"),_("��������"),_("��֤����"),_("����"),_("��ע"));

require_once ('inc/ExcelWriter.php');
$objExcel = new ExcelWriter();
$objExcel->setFileName(_("֤�չ�����Ϣ"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>