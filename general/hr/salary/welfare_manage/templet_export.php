<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="NAME,ID,WELFARE_ITEM,PAYMENT_DATE,WELFARE_MONTH,WELFARE_PAYMENT,TAX_AFFAIRS,FREE_GIFT,MEMO";
else
   $EXCEL_OUT=array(_("姓名"),_("用户名"),_("福利项目"),_("发放日期"),_("工资月份"),_("福利金额"),_("是否纳税"),_("发放物品"),_("备注"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("员工福利记录"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>