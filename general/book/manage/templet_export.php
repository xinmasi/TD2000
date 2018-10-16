<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
    $EXCEL_OUT="DEPARTMENT,BOOK_NAME,AUTHOR,BOOK_NO,BOOK_TYPE,ISBN,PUBLISH_HOUSE,PUBLISH_DATE,PLACE,AMOUNT,PRICE,BRIEF,OPEN,BORR_PERSON,MEMO";
else
    $EXCEL_OUT=array(_("部门"),_("书名"),_("作者"),_("图书编号"),_("图书类别"),_("ISBN号"),_("出版社"),_("出版日期"),_("存放地点"),_("数量"),_("价格"),_("内容简介"),_("借阅范围"),_("录入人"),_("备注"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("图书信息导入模板"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>