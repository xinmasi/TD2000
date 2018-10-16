<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
    $OUTPUT_HEAD = "REQU_NO,REQU_DEPT,REQU_JOB,REQU_NUM,REQU_TIME,PETITIONER,REMARK,ADD_TIME,REQU_REQUIRES";
else
    $OUTPUT_HEAD = _("需求编号").","._("需求部门").","._("需求岗位").","._("需求人数").","._("用工日期").","._("申请人").","._("备注").","._("登记时间").","._("岗位要求");

$CONDITION_STR="";
if($REQU_NO!="")
    $CONDITION_STR.=" and REQU_NO='$REQU_NO'";
if($REQU_NUM!="")
    $CONDITION_STR.=" and REQU_NUM='$REQU_NUM'";
if($REQU_JOB!="")
    $CONDITION_STR.=" and REQU_JOB like '%".$REQU_JOB."%'";
if($REQU_DEPT!="")
    $CONDITION_STR.=" and REQU_DEPT='$REQU_DEPT'";
if($REQU_TIME1!="")
    $CONDITION_STR.=" and REQU_TIME>='$REQU_TIME1'";
if($REQU_TIME2!="")
    $CONDITION_STR.=" and REQU_TIME<='$REQU_TIME2'";
if($REQUIREMENTS_ID!="")
    $CONDITION_STR.=" and find_in_set(REQUIREMENTS_ID,'$REQUIREMENTS_ID') ";

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("招聘需求信息"));
$objExcel->addHead($OUTPUT_HEAD);



$query = "SELECT * from HR_RECRUIT_REQUIREMENTS where 1='1' ".$CONDITION_STR. "order by ADD_TIME desc ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $REQUIREMENTS_ID=$ROW["REQUIREMENTS_ID"];
    $USER_ID=$ROW["USER_ID"];
    $DEPT_ID=$ROW["DEPT_ID"];
    $REQU_NO=format_cvs($ROW["REQU_NO"]);
    $REQU_DEPT=$ROW["REQU_DEPT"];
    $REQU_JOB=format_cvs($ROW["REQU_JOB"]);
    $REQU_NUM=format_cvs($ROW["REQU_NUM"]);

    $REQU_TIME=format_cvs($ROW["REQU_TIME"]);
    $PETITIONER=$ROW["PETITIONER"];
    $REMARK=format_cvs($ROW["REMARK"]);
    $ADD_TIME =format_cvs($ROW["ADD_TIME"]);
    $REQU_REQUIRES="\"".format_cvs($ROW["REQU_REQUIRES"])."\"";

    $REQU_DEPT_NAME=format_cvs(substr(GetDeptNameById($REQU_DEPT),0,-1));
    $PETITIONER_NAME=format_cvs(substr(GetUserNameById($PETITIONER),0,-1));
    $REQU_REQUIRES = strip_tags($REQU_REQUIRES);
    $REQU_REQUIRES = str_replace("&nbsp;","  ",$REQU_REQUIRES);

    $OUTPUT="$REQU_NO,$REQU_DEPT_NAME,$REQU_JOB,$REQU_NUM,$REQU_TIME,$PETITIONER_NAME,$REMARK,$ADD_TIME,$REQU_REQUIRES\r\n";
    $objExcel->addRow($OUTPUT);

}

$objExcel->Save();
?>