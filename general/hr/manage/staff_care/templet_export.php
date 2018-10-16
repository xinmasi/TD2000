<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
    $EXCEL_OUT="TYPE,FEES,ID,NAME,DATE,EFFECTS,PARTICIPANTS,CONTENT";
else
    $EXCEL_OUT=array(_("关怀类型"),_("关怀开支费用/人"),_("被关怀员工用户名"),_("被关怀员工"),_("关怀日期"),_("关怀效果"),_("参与人（中文逗号隔开）"),_("关怀内容"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("员工关怀信息"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>