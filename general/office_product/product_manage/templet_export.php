<?
include_once("inc/auth.inc.php");
ob_end_clean();

if (MYOA_IS_UN == 1)
    $EXCEL_OUT = "NAME,DEPOSITORY,TYPE,CODE,PRICE,DESCRIBE,MEASURE_UNIT,SUPPLIER,LOWSTOCK,MAXSTOCK,STOCK,CREATOR,MANAGER,AUDITOR,REG_DEPT,PRODUCT_TYPE";
else
    $EXCEL_OUT = array (
        _("�칫��Ʒ����"),
        _("�칫��Ʒ��"),
        _("�칫��Ʒ���"),
        _("����"),
        _("����"),
        _("�칫��Ʒ����"),
        _("������λ"),
        _("��Ӧ��"),
        _("��;�����"),
        _("��߾�����"),
        _("��ǰ���"),
        _("������"),
        _("�Ǽ�Ȩ��(�û�)"),
        _("������"),
        _("�Ǽ�Ȩ��(����)"),
        _("�Ǽ�����")
    );

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("�칫��Ʒ��Ϣģ��"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>