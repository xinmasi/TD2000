<?
include_once("inc/auth.inc.php");
ob_end_clean();

$SAL_ITEM="";
$query = "SELECT ITEM_ID,ITEM_NAME from SAL_ITEM ORDER BY `ITEM_ID`";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $SAL_ITEM.=",".$ROW["ITEM_NAME"];
}

$SAL_ITEM.=","._("保险基数");
$SAL_ITEM.=","._("养老保险");
$SAL_ITEM.=","._("单位养老");
$SAL_ITEM.=","._("个人养老");
$SAL_ITEM.=","._("医疗保险");
$SAL_ITEM.=","._("单位医疗");
$SAL_ITEM.=","._("个人医疗");
$SAL_ITEM.=","._("生育保险");
$SAL_ITEM.=","._("单位生育");
$SAL_ITEM.=","._("失业保险");
$SAL_ITEM.=","._("单位失业");
$SAL_ITEM.=","._("个人失业");
$SAL_ITEM.=","._("工伤保险");
$SAL_ITEM.=","._("单位工伤");
$SAL_ITEM.=","._("住房公积金");
$SAL_ITEM.=","._("单位住房");
$SAL_ITEM.=","._("个人住房");

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="ID,NAME".$SAL_ITEM.",MEMO";
else
   $EXCEL_OUT=_("用户名").","._("姓名").$SAL_ITEM;

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("员工薪酬基数"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>