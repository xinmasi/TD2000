<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="CPTL_NO,CPTL_NAME,TYPE_ID,DEPT_ID,CPTL_KIND,PRCS_ID,CPTL_VAL,CPTL_BAL,DPCT_YY,SUM_DPCT,LICENSE_DEPT,FROM_YYMM,KEEPER,REMARK";
else
   $EXCEL_OUT=array(_("资产编号"),_("资产名称"),_("资产类别"),_("所属部门"),_("资产性质"),_("增加类型"),_("资产原值"),_("残值率"),_("折旧年限"),_("累计折旧"),_("月折旧额"),_("启用日期"),_("保管人"),_("备注"));

//查询自定义字段
$sql = "SELECT * from FIELDSETTING where TABLENAME='CP_CPTL_INFO' order by ORDERNO";
$cursor= exequery(TD::conn(),$sql);
while($ROW=mysql_fetch_array($cursor))
{
    if(MYOA_IS_UN == 1)
    {
        $EXCEL_OUT .= $ROW['FIELDNAME'].",";
    }
    else
    {
        Array_push($EXCEL_OUT, $ROW['FIELDNAME']);
    }
}
if(MYOA_IS_UN == 1)
{
    $EXCEL_OUT = td_trim($EXCEL_OUT);
}

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("固定资产管理信息"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>