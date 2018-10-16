<?
include_once("inc/auth.inc.php");
ob_end_clean();

if (MYOA_IS_UN == 1)
    $EXCEL_OUT = "NAME,DEPOSITORY,TYPE,CODE,PRICE,DESCRIBE,MEASURE_UNIT,SUPPLIER,LOWSTOCK,MAXSTOCK,STOCK,CREATOR,MANAGER,AUDITOR,REG_DEPT,PRODUCT_TYPE";
else
    $EXCEL_OUT = array (
        _("办公用品名称"),
        _("办公用品库"),
        _("办公用品类别"),
        _("编码"),
        _("单价"),
        _("办公用品描述"),
        _("计量单位"),
        _("供应商"),
        _("最低警戒库存"),
        _("最高警戒库存"),
        _("当前库存"),
        _("创建人"),
        _("登记权限(用户)"),
        _("审批人"),
        _("登记权限(部门)"),
        _("登记类型")
    );

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("办公用品信息模板"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>