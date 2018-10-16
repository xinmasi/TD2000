<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");
ob_end_clean();

$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());


//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($STAFF_USER_ID!="")
    $CONDITION_STR.=" and STAFF_USER_ID='$STAFF_USER_ID'";
if($TO_ID!="")
    $CONDITION_STR.=" and T_PLAN_NO='$TO_ID'";
if($T_INSTITUTION_NAME!="")
    $CONDITION_STR.=" and T_INSTITUTION_NAME='$T_INSTITUTION_NAME'";
if($TRAINNING_COST!="")
    $CONDITION_STR.=" and TRAINNING_COST='$TRAINNING_COST'";
if($DUTY_SITUATION!="")
    $CONDITION_STR.=" and DUTY_SITUATION like '%".$DUTY_SITUATION."%'";

/*$CONDITION_STR = hr_priv("STAFF_NAME").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_INCENTIVE where".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$INCENTIVE_COUNT=0;
*/

$WHERE_STR = hr_priv("STAFF_USER_ID");
$query = "SELECT * from  HR_TRAINING_RECORD WHERE".$WHERE_STR.$CONDITION_STR;
$cursor=exequery(TD::conn(),$query);
$STAFF_COUNT = 0;

if(MYOA_IS_UN == 1)
    $OUTPUT_HEAD="PLAN_NO,PLAN_NAME,TRAINING_INSTITUTIONS,TRAINING_COSTS,RESULTS,LEVEL,TRAINEES,ATTENDANCE,SUMMARY_COMPLETION,COMMENT,MEMO";
else
    $OUTPUT_HEAD=array(_("培训计划编号"),_("培训计划名称"),_("培训机构"),_("培训费用"),_("培训考核成绩"),_("培训考核等级"),_("受训人"),_("出勤情况"),_("总结完成情况"),_("评论"),_("备注"));

require_once ('inc/ExcelWriter.php');
$objExcel = new ExcelWriter();
$objExcel->setFileName(_("培训计划"));
$objExcel->addHead($OUTPUT_HEAD);

while($ROW=mysql_fetch_array($cursor))
{
    $STAFF_COUNT++;

    $RECORD_ID=$ROW["RECORD_ID"];
    $STAFF_USER_ID=td_trim(GetUserNameById($ROW["STAFF_USER_ID"]));
    $T_PLAN_NO=$ROW["T_PLAN_NO"];
    $T_PLAN_NAME=$ROW["T_PLAN_NAME"];
    $T_INSTITUTION_NAME=$ROW["T_INSTITUTION_NAME"];
    $TRAINNING_COST=$ROW["TRAINNING_COST"];
    $DUTY_SITUATION=$ROW["DUTY_SITUATION"];
    $TRAINNING_SITUATION=$ROW["TRAINNING_SITUATION"];
    $T_EXAM_RESULTS=$ROW["T_EXAM_RESULTS"];
    $T_EXAM_LEVEL=$ROW["T_EXAM_LEVEL"];
    $T_COMMENT=$ROW["T_COMMENT"];
    $REMARK=$ROW["REMARK"];

    $EXCEL_OUT= format_cvs($T_PLAN_NO).",".format_cvs($T_PLAN_NAME).",".format_cvs($T_INSTITUTION_NAME).",".format_cvs($TRAINNING_COST).",".format_cvs($T_EXAM_RESULTS).",".format_cvs($T_EXAM_LEVEL) .",". format_cvs($STAFF_USER_ID).",".format_cvs($DUTY_SITUATION).",".format_cvs($TRAINNING_SITUATION).",".format_cvs($T_COMMENT).",".format_cvs($REMARK);
    $objExcel->addRow($EXCEL_OUT);
}
$objExcel->Save();

?>