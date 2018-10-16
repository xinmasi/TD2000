<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

if(MYOA_IS_UN == 1)
 $EXCEL_OUT="ID,REGISTRATION_TIME,REGISTER_IP,MEMO";
else
 $EXCEL_OUT=array(_("用户名"),_("登记时间"),_("登记IP"),_("备注"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("考勤模板"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>