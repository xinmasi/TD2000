<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();

$EXCEL_OUT=array(_("文件号"),_("文件主题词"),_("文件标题"),_("文件辅标题"),_("发文单位"),_("发文日期"),_("密级"),_("紧急等级"),_("文件分类"),_("公文类别"),_("文件页数"),_("打印页数"),_("备注"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("文件档案"));
$objExcel->addHead($EXCEL_OUT);

$DELETE_STR="'".str_replace(",","','",substr($DELETE_STR,0,-1))."'";
$query="select * from RMS_FILE where FILE_ID in ($DELETE_STR)";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	$FILE_CODE=$ROW["FILE_CODE"];
    $FILE_SUBJECT=$ROW["FILE_SUBJECT"];
    $FILE_TITLE=$ROW["FILE_TITLE"];
    $FILE_TITLE0=$ROW["FILE_TITLE0"];
    $SEND_UNIT=$ROW["SEND_UNIT"];
    $SEND_DATE=$ROW["SEND_DATE"];
    $SECRET=$ROW["SECRET"];
    $URGENCY=$ROW["URGENCY"];
    $FILE_TYPE=$ROW["FILE_TYPE"];
    $FILE_KIND=$ROW["FILE_KIND"];
    $FILE_PAGE=$ROW["FILE_PAGE"];
    $PRINT_PAGE=$ROW["PRINT_PAGE"];
    $REMARK=$ROW["REMARK"];
    $EXCEL_OUT=$FILE_CODE.",".$FILE_SUBJECT.",".$FILE_TITLE.",".$FILE_TITLE0.",".$SEND_UNIT.",".$SEND_DATE.",".get_code_name($SECRET,"RMS_SECRET").",".get_code_name($URGENCY,"RMS_URGENCY").",".get_code_name($FILE_TYPE,"RMS_FILE_TYPE").",".get_code_name($FILE_KIND,"RMS_FILE_KIND").",".$FILE_PAGE.",".$PRINT_PAGE.",".$REMARK;
    $objExcel->addRow($EXCEL_OUT);
}

$objExcel->Save();
?>
