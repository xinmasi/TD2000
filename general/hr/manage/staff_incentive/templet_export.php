<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
    $EXCEL_OUT="ID,NAME,ITEM,DATE,SALARY_MONTH,TYPE,AMOUNT,DESCRIPTION,MEMO";
else
    $EXCEL_OUT=array(_("用户名"),_("单位员工"),_("奖惩项目"),_("奖惩日期"),_("工资月份"),_("奖惩属性"),_("奖惩金额（元）"),_("奖惩说明"),_("备注"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("奖惩管理信息"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>