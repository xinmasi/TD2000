<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="CPTL_NO,CPTL_NAME,TYPE_ID,DEPT_ID,CPTL_KIND,PRCS_ID,CPTL_VAL,CPTL_BAL,DPCT_YY,SUM_DPCT,LICENSE_DEPT,FROM_YYMM,KEEPER,REMARK";
else
   $EXCEL_OUT=array(_("�ʲ����"),_("�ʲ�����"),_("�ʲ����"),_("��������"),_("�ʲ�����"),_("��������"),_("�ʲ�ԭֵ"),_("��ֵ��"),_("�۾�����"),_("�ۼ��۾�"),_("���۾ɶ�"),_("��������"),_("������"),_("��ע"));

//��ѯ�Զ����ֶ�
$sql = "SELECT * from FIELDSETTING where TABLENAME='CP_CPTL_INFO' order by ORDERNO";
$cursor= exequery(TD::conn(),$sql);
while($ROW=mysql_fetch_array($cursor))
{
    if(MYOA_IS_UN == 1)
    {
        $EXCEL_OUT .= $ROW['FIELDNAME'].",";
    }
    else
    {
        Array_push($EXCEL_OUT, $ROW['FIELDNAME']);
    }
}
if(MYOA_IS_UN == 1)
{
    $EXCEL_OUT = td_trim($EXCEL_OUT);
}

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("�̶��ʲ�������Ϣ"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>