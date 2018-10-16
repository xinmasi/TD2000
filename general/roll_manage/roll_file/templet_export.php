<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
 $EXCEL_OUT="FILE_CODE,FILE_SUBJECT,TITLE,TITLE0,SEND_UNIT,SEND_DATE,SECRET,URGENCY,TYPE,KIND,FILE_PAGE,PRINT_PAGE,MEMO,ROLL_ID";
else
 $EXCEL_OUT=array(_("文件号"),_("文件主题词"),_("文件标题"),_("文件辅标题"),_("发文单位"),_("发文日期"),_("密级"),_("紧急等级"),_("文件分类"),_("公文类别"),_("文件页数"),_("打印页数"),_("备注"),_("所属案卷名称"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("文件档案"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>
