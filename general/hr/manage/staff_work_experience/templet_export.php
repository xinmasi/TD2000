<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="ID,NAME,POST_OF_JOB,WORK_BRANCH,WITNESS,START_DATE,END_DATE,MOBILE,WORK_UNIT,WORK_CONTENT,REASON_FOR_LEAVING,MEMO,KEY_PERFORMANCE";
else
   $EXCEL_OUT=array(_("用户名"),_("单位员工"),_("担任职务"),_("所在部门"),_("证明人"),_("开始日期"),_("结束日期"),_("行业类别"),_("工作单位"),_("工作内容"),_("离职原因"),_("备注"),_("主要业绩"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("员工工作经历"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>