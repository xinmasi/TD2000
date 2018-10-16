<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
    $EXCEL_OUT=array("Person_onduty","Start_time","End_time","Scheduling_type","Duty_type","Duty_requirement","Remarks","Duty_log");
else
    $EXCEL_OUT=array(_("值班人"),_("值班开始时间"),_("值班结束时间"),_("排班类型"),_("值班类型"),_("值班要求"),_("备注"),_("值班日志"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("值班排班"));
$objExcel->addHead($EXCEL_OUT);

$WHERE_STR="";
if($TO_ID!="" && $TO_ID!="ALL_DEPT")
    $WHERE_STR = " and find_in_set(ZHIBANREN_DEPT,'$TO_ID')";
if($PAIBAN_TYPE!="")
    $WHERE_STR .= " and PAIBAN_TYPE='$PAIBAN_TYPE'";
if($ZHIBAN_TYPE!="")
    $WHERE_STR .= " and ZHIBAN_TYPE='$ZHIBAN_TYPE'";
if($ZBSJ_B!="")
{
    $ZBSJ_B.=" 00:00:01";
    $WHERE_STR .= " and ZBSJ_B >= '$ZBSJ_B'";
}
if($ZBSJ_E!="")
{
    $ZBSJ_E.=" 23:59:59";
    $WHERE_STR .= " and ZBSJ_E >= '$ZBSJ_E'";
}
if($ZBYQ!="")
    $WHERE_STR .= " and ZBYQ like '%$ZBYQ%'";
if($ZBYQ!="")
    $WHERE_STR .= " and ZBYQ like '%$ZBYQ%'";


//============================ 显示排班记录 =======================================
$query = "SELECT * from ZBAP_PAIBAN where 1='1' ".$WHERE_STR;
$query .= " order by ZBSJ_B desc";
$cursor= exequery(TD::conn(),$query);
$PAIBAN_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $PAIBAN_COUNT++;

    $PAIBAN_ID=format_cvs($ROW["PAIBAN_ID"]);
    $ZHIBANREN=format_cvs($ROW["ZHIBANREN"]);
    $PAIBAN_TYPE=format_cvs($ROW["PAIBAN_TYPE"]);
    $ZHIBAN_TYPE=format_cvs($ROW["ZHIBAN_TYPE"]);
    $ZBSJ_B=format_cvs($ROW["ZBSJ_B"]);
    $ZBSJ_E=format_cvs($ROW["ZBSJ_E"]);
    $ZBYQ=format_cvs($ROW["ZBYQ"]);
    $BEIZHU=format_cvs($ROW["BEIZHU"]);
    $PAIBAN_APR=format_cvs($ROW["PAIBAN_APR"]);
    $ANPAI_TIME=format_cvs($ROW["ANPAI_TIME"]);
    $ZB_RZ=format_cvs($ROW["ZB_RZ"]);

    $PAIBAN_TYPE_NAME = format_cvs(get_code_name($PAIBAN_TYPE,"PAIBAN_TYPE"));
    $ZHIBAN_TYPE_NAME = format_cvs(get_code_name($ZHIBAN_TYPE,"ZHIBAN_TYPE"));
    $USER_NAME=format_cvs(substr(GetUserNameByID($ZHIBANREN),0,-1));
    $DUTY_LOG=format_cvs(str_replace("\n","<br>",$ZB_RZ));

    $EXCEL_OUT=$USER_NAME.",".$ZBSJ_B.",".$ZBSJ_E.",".$PAIBAN_TYPE_NAME.",".$ZHIBAN_TYPE_NAME.",".$ZBYQ.",".$BEIZHU.",".$DUTY_LOG;
    $objExcel->addRow($EXCEL_OUT);
}
$objExcel->Save();
?>

