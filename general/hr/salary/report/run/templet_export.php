<?
include_once("inc/auth.inc.php");
ob_end_clean();

$SAL_ITEM="";
$query = "SELECT ITEM_NAME from SAL_ITEM WHERE ISREPORT=1 order by ITEM_ID";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $SAL_ITEM.=",".$ROW["ITEM_NAME"];
}

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="ID,NAME".$SAL_ITEM;
else
   $EXCEL_OUT=_("�û���").","._("����").$SAL_ITEM;

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("�����ϱ�����ģ��"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>