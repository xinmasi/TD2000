<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
    $EXCEL_OUT="ID,NAME,MAJOR,ACADEMY_DEGREE,DEGREE,POSITION,WITNESS,SCHOOL,SCHOOL_ADDRESS,START_DATE,END_DATE,AWARDING,CERTIFICATES,MEMO";
else
    $EXCEL_OUT=array(_("用户名"),_("单位员工"),_("所学专业"),_("所获学历"),_("所获学位"),_("曾任班干"),_("证明人"),_("所在院校"),_("院校所在地"),_("开始日期"),_("结束日期"),_("获奖情况"),_("所获证书"),_("备注"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("员工学习经历"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>