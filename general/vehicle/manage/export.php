<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
ob_end_clean();

$EXCEL_OUT=array(_("车牌号"),_("维护类型"),_("维护原因"),_("维护日期"),_("经办人"),_("维护费用"),_("备注"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("车辆维护记录"));
$objExcel->addHead($EXCEL_OUT);

$WHERE_STR="";
if($V_ID!="")
    $WHERE_STR.=" and V_ID='$V_ID'";
if($BEGIN_DATE!="")
    $WHERE_STR.=" and VM_REQUEST_DATE>='$BEGIN_DATE'";
if($END_DATE!="")
    $WHERE_STR.=" and VM_REQUEST_DATE<='$END_DATE'";
if($VM_TYPE!="")
    $WHERE_STR.=" and VM_TYPE='$VM_TYPE'";
if($VM_REASON!="")
    $WHERE_STR.=" and VM_REASON like '%$VM_REASON%'";
if($VM_PERSON!="")
    $WHERE_STR.=" and VM_PERSON like '%$VM_PERSON%'";
if($VM_FEE_MIN!="")
    $WHERE_STR.=" and VM_FEE>='$VM_FEE_MIN'";
if($VM_FEE_MAX!="")
    $WHERE_STR.=" and VM_FEE='$VM_FEE_MAX'";
if($VM_REMARK!="")
    $WHERE_STR.=" and VM_REMARK like '%$VM_REMARK%'";

if($ASC_DESC=="")
    $ASC_DESC="1";
if($FIELD=="")
    $FIELD="VM_REQUEST_DATE";

$query = "SELECT * from VEHICLE_MAINTENANCE where 1=1".$WHERE_STR;
$query .= " order by $FIELD";
if($ASC_DESC=="1")
    $query .= " desc";
else
    $query .= " asc";
$cursor= exequery(TD::conn(),$query);
$cursor= exequery(TD::conn(),$query);
$VM_COUNT=0;
$VM_FEE_SUM=0;
while($ROW=mysql_fetch_array($cursor))
{
    $VM_COUNT++;

    $VM_ID=$ROW["VM_ID"];
    $V_ID=$ROW["V_ID"];
    $VM_REQUEST_DATE=$ROW["VM_REQUEST_DATE"];
    $VM_TYPE=$ROW["VM_TYPE"];
    $VM_REASON=$ROW["VM_REASON"];
    $VM_FEE=$ROW["VM_FEE"];
    $VM_PERSON=$ROW["VM_PERSON"];
    $VM_REMARK =$ROW["VM_REMARK"];

    $VM_FEE_SUM+=$VM_FEE;

    if($VM_REQUEST_DATE=="0000-00-00")
        $VM_REQUEST_DATE="";

    $query = "SELECT * from VEHICLE where V_ID='$V_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW1=mysql_fetch_array($cursor1))
        $V_NUM=$ROW1["V_NUM"];

    $query2="select CODE_NAME,CODE_EXT from SYS_CODE where PARENT_NO='VEHICLE_REPAIR_TYPE' and CODE_NO ='$VM_TYPE'";
    $cursor2= exequery(TD::conn(),$query2);
    if($ROW2=mysql_fetch_array($cursor2))
    {
        $VM_TYPE_DESC=$ROW2["CODE_NAME"];
        $CODE_EXT=unserialize($ROW["CODE_EXT"]);
        if(is_array($CODE_EXT) && $CODE_EXT[MYOA_LANG_COOKIE] != "")
            $VM_TYPE_DESC = $CODE_EXT[MYOA_LANG_COOKIE];
    }

    $EXCEL_OUT=$V_NUM.",".$VM_TYPE_DESC.",".$VM_REASON.",".$VM_REQUEST_DATE.",".$VM_PERSON.",".$VM_FEE.",".$VM_REMARK;
    $objExcel->addRow($EXCEL_OUT);
}

$objExcel->Save();
?>