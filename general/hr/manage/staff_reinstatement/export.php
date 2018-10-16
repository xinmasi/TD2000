<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------合法性校验---------
if($APPLICATION_DATE1!="")
{
    $TIME_OK=is_date($APPLICATION_DATE1);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $APPLICATION_DATE1=$APPLICATION_DATE1." 00:00:00";
}

if($APPLICATION_DATE2!="")
{
    $TIME_OK=is_date($APPLICATION_DATE2);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $APPLICATION_DATE2=$APPLICATION_DATE2." 23:59:59";
}

if($REAPPOINTMENT_TIME_FACT1!="")
{
    $TIME_OK=is_date($REAPPOINTMENT_TIME_FACT1);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $REAPPOINTMENT_TIME_FACT1=$REAPPOINTMENT_TIME_FACT1." 00:00:00";
}

if($REAPPOINTMENT_TIME_FACT2!="")
{
    $TIME_OK=is_date($REAPPOINTMENT_TIME_FACT2);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $REAPPOINTMENT_TIME_FACT2=$REAPPOINTMENT_TIME_FACT2." 23:59:59";
}
if(MYOA_IS_UN == 1)
    $OUTPUT_HEAD = "REINSTATEMENT_PERSON_NAME,REAPPOINTMENT_TYPE,NOW_POSITION,REAPPOINTMENT_DEPT_NAME,APPLICATION_DATE,REAPPOINTMENT_TIME_PLAN,REAPPOINTMENT_TIME_FACT,FIRST_SALARY_TIME,MATERIALS_CONDITION,REAPPOINTMENT_STATE,REMARK";
else
    $OUTPUT_HEAD = _("复职人员").","._("复职类型").","._("担任职务").","._("复职部门").","._("申请日期").","._("拟复职日期").","._("实际复职日期").","._("工资恢复日期").","._("复职手续办理").","._("复职说明").","._("备注");

ob_end_clean();

require_once ('inc/ExcelWriter.php');
$objExcel = new ExcelWriter();
$objExcel->setFileName(_("复职管理信息"));
$objExcel->addHead($OUTPUT_HEAD);


//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($HR_TABLE==1)
{
    $YEAR=date("Y",time());
    $START_DATE=$YEAR."-01-01";
    $END_DATE=$YEAR."-12-31";
    $CONDITION_STR.=" and APPLICATION_DATE >= '$START_DATE' and APPLICATION_DATE <= '$END_DATE'";
    $CONDITION_STR.=" and REAPPOINTMENT_TIME_FACT >= '$START_DATE' and REAPPOINTMENT_TIME_FACT <= '$END_DATE'";
}
else
{
    if($REAPPOINTMENT_STATE!="")
        $CONDITION_STR.=" and REAPPOINTMENT_STATE like '%".$REAPPOINTMENT_STATE."%'";
    if($MATERIALS_CONDITION!="")
        $CONDITION_STR.=" and MATERIALS_CONDITION like '%".$MATERIALS_CONDITION."%'";
    if($REINSTATEMENT_PERSON!="")
        $CONDITION_STR.=" and REINSTATEMENT_PERSON='$REINSTATEMENT_PERSON'";
    if($REAPPOINTMENT_TYPE!="")
        $CONDITION_STR.=" and REAPPOINTMENT_TYPE='$REAPPOINTMENT_TYPE'";
    if($APPLICATION_DATE1!="")
        $CONDITION_STR.=" and APPLICATION_DATE>='$APPLICATION_DATE1'";
    if($APPLICATION_DATE2!="")
        $CONDITION_STR.=" and APPLICATION_DATE<='$APPLICATION_DATE2'";
    if($REAPPOINTMENT_TIME_FACT1!="")
        $CONDITION_STR.=" and REAPPOINTMENT_TIME_FACT>='$REAPPOINTMENT_TIME_FACT1'";
    if($REAPPOINTMENT_TIME_FACT2!="")
        $CONDITION_STR.=" and REAPPOINTMENT_TIME_FACT<='$REAPPOINTMENT_TIME_FACT2'";
}
$CONDITION_STR = hr_priv("REINSTATEMENT_PERSON").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_REINSTATEMENT where ".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$LEAVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $LEAVE_COUNT++;
    $REAPPOINTMENT_TIME_FACT=format_cvs($ROW["REAPPOINTMENT_TIME_FACT"]);
    $REAPPOINTMENT_TYPE=format_cvs($ROW["REAPPOINTMENT_TYPE"]);
    $REAPPOINTMENT_STATE=format_cvs($ROW["REAPPOINTMENT_STATE"]);
    $REMARK=format_cvs($ROW["REMARK"]);
    $REINSTATEMENT_PERSON=format_cvs($ROW["REINSTATEMENT_PERSON"]);
    $REAPPOINTMENT_TIME_PLAN=format_cvs($ROW["REAPPOINTMENT_TIME_PLAN"]);
    $NOW_POSITION=format_cvs($ROW["NOW_POSITION"]);
    $APPLICATION_DATE=format_cvs($ROW["APPLICATION_DATE"]);
    $MATERIALS_CONDITION=format_cvs($ROW["MATERIALS_CONDITION"]);
    $FIRST_SALARY_TIME=format_cvs($ROW["FIRST_SALARY_TIME"]);
    $ADD_TIME=format_cvs($ROW["ADD_TIME"]);
    $REAPPOINTMENT_DEPT =format_cvs($ROW["REAPPOINTMENT_DEPT"]);
    $LAST_UPDATE_TIME =format_cvs($ROW["LAST_UPDATE_TIME"]);
    $ATTACHMENT_ID=format_cvs($ROW["ATTACHMENT_ID"]);
    $ATTACHMENT_NAME=format_cvs($ROW["ATTACHMENT_NAME"]);

    if($LAST_UPDATE_TIME=="0000-00-00 00:00:00")
        $LAST_UPDATE_TIME="";
    if($APPLICATION_DATE=="0000-00-00")
        $APPLICATION_DATE="";
    if($REAPPOINTMENT_TIME_PLAN=="0000-00-00")
        $REAPPOINTMENT_TIME_PLAN="";
    if($REAPPOINTMENT_TIME_FACT=="0000-00-00")
        $REAPPOINTMENT_TIME_FACT="";
    if($FIRST_SALARY_TIME=="0000-00-00")
        $FIRST_SALARY_TIME="";

    $REAPPOINTMENT_TYPE=get_hrms_code_name($REAPPOINTMENT_TYPE,"HR_STAFF_REINSTATEMENT");

    $REINSTATEMENT_PERSON_NAME=td_trim(GetUserNameById($REINSTATEMENT_PERSON));
    if($REINSTATEMENT_PERSON_NAME=="")
    {
        $query2 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$REINSTATEMENT_PERSON'";
        $cursor2= exequery(TD::conn(),$query2);
        if($ROW2=mysql_fetch_array($cursor2))
            $REINSTATEMENT_PERSON_NAME=$ROW2["STAFF_NAME"];
        $REINSTATEMENT_PERSON_NAME=$REINSTATEMENT_PERSON_NAME._("（用户已删除）");
    }
    $REAPPOINTMENT_DEPT_NAME=td_trim(GetDeptNameById($REAPPOINTMENT_DEPT));
    $REAPPOINTMENT_STATE = strip_tags($REAPPOINTMENT_STATE);
    $REAPPOINTMENT_STATE = str_replace("&nbsp;","  ",$REAPPOINTMENT_STATE);


    $OUTPUT = $REINSTATEMENT_PERSON_NAME.",".$REAPPOINTMENT_TYPE.",".$NOW_POSITION.",".$REAPPOINTMENT_DEPT_NAME.",".$APPLICATION_DATE.",".$REAPPOINTMENT_TIME_PLAN.",".$REAPPOINTMENT_TIME_FACT.",".$FIRST_SALARY_TIME.",".$MATERIALS_CONDITION.",".$REAPPOINTMENT_STATE.",".$REMARK;
    $objExcel->addRow($OUTPUT);
}

$objExcel->Save();
?>