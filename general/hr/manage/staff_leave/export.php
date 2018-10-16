<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");


$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------合法性校验---------

if($QUIT_TIME_PLAN1!="")
{
    $TIME_OK=is_date($QUIT_TIME_PLAN1);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $QUIT_TIME_PLAN1=$QUIT_TIME_PLAN1." 00:00:00";
}

if($QUIT_TIME_FACT1!="")
{
    $TIME_OK=is_date($QUIT_TIME_FACT1);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $QUIT_TIME_FACT1=$QUIT_TIME_FACT1." 23:59:59";
}

if($QUIT_TIME_PLAN2!="")
{
    $TIME_OK=is_date($QUIT_TIME_PLAN2);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $QUIT_TIME_PLAN2=$QUIT_TIME_PLAN2." 00:00:00";
}

if($QUIT_TIME_FACT2!="")
{
    $TIME_OK=is_date($QUIT_TIME_FACT2);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $QUIT_TIME_FACT2=$QUIT_TIME_FACT2." 23:59:59";
}

if(MYOA_IS_UN == 1)
    $OUTPUT_HEAD = "LEAVE_PERSON,LEAVE_DEPT_NAME,JOB_POSITION,QUIT_TYPE,APPLICATION_DATE,QUIT_TIME_PLAN,QUIT_TIME_FACT,LAST_SALARY_TIM,TRACE,MATERIALS_CONDITION,QUIT_REASON,REMARK,SALARY,STAFF_CARD_NO";
else
    $OUTPUT_HEAD = _("离职人员").","._("离职部门").","._("担任职务").","._("离职类型").","._("申请日期").","._("拟离职日期").","._("实际离职日期").","._("工资截止日期").","._("去向").","._("离职手续办理").","._("离职原因").","._("备注").","._("离职当月薪资").","._("身份证号");

ob_end_clean();
require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("离职管理信息"));
$objExcel->addHead($OUTPUT_HEAD);


//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($HR_TABLE==1)
{
    $YEAR=date("Y",time());
    $START_DATE=$YEAR."-01-01";
    $END_DATE=$YEAR."-12-31";
    $CONDITION_STR.=" and QUIT_TIME_FACT >= '$START_DATE' and QUIT_TIME_FACT <= '$END_DATE'";
}
else
{
    if($QUIT_REASON!="")
        $CONDITION_STR.=" and QUIT_REASON like '%".$QUIT_REASON."%'";
    if($MATERIALS_CONDITION!="")
        $CONDITION_STR.=" and MATERIALS_CONDITION like '%".$MATERIALS_CONDITION."%'";
    if($LEAVE_PERSON!="")
        $CONDITION_STR.=" and LEAVE_PERSON='$LEAVE_PERSON'";
    if($QUIT_TYPE!="")
        $CONDITION_STR.=" and QUIT_TYPE='$QUIT_TYPE'";
    if($LEAVE_DEPT!="")
        $CONDITION_STR.=" and LEAVE_DEPT='$LEAVE_DEPT'";
    if($QUIT_TIME_PLAN1!="")
        $CONDITION_STR.=" and QUIT_TIME_PLAN>='$QUIT_TIME_PLAN1'";
    if($QUIT_TIME_PLAN2!="")
        $CONDITION_STR.=" and QUIT_TIME_PLAN<='$QUIT_TIME_PLAN2'";
    if($QUIT_TIME_FACT1!="")
        $CONDITION_STR.=" and QUIT_TIME_FACT>='$QUIT_TIME_FACT1'";
    if($QUIT_TIME_FACT2!="")
        $CONDITION_STR.=" and QUIT_TIME_FACT<='$QUIT_TIME_FACT2'";
}

$CONDITION_STR = hr_priv("CREATE_USER_ID").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_LEAVE where ".$CONDITION_STR."order by ADD_TIME desc";

$cursor=exequery(TD::conn(),$query);
$LEAVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $LEAVE_COUNT++;
    $LEAVE_ID=format_cvs($ROW["LEAVE_ID"]);
    $CREATE_USER_ID=format_cvs($ROW["CREATE_USER_ID"]);
    $CREATE_DEPT_ID=format_cvs($ROW["CREATE_DEPT_ID"]);
    $APPLICATION_DATE =format_cvs($ROW["APPLICATION_DATE"]);
    $QUIT_TIME_PLAN=format_cvs($ROW["QUIT_TIME_PLAN"]);
    $QUIT_TYPE=$ROW["QUIT_TYPE"];
    $LAST_SALARY_TIME=format_cvs($ROW["LAST_SALARY_TIME"]);
    $LEAVE_PERSON=format_cvs($ROW["LEAVE_PERSON"]);
    $ADD_TIME=format_cvs($ROW["ADD_TIME"]);
    $QUIT_TIME_FACT=format_cvs($ROW["QUIT_TIME_FACT"]);
    $POSITION=format_cvs($ROW["POSITION"]);
    $IS_REINSTATEMENT=format_cvs($ROW["IS_REINSTATEMENT"]);
    $LEAVE_DEPT =$ROW["LEAVE_DEPT"];
    $TRACE=format_cvs($ROW["TRACE"]);
    $MATERIALS_CONDITION=format_cvs($ROW["MATERIALS_CONDITION"]);
    $QUIT_REASON=format_cvs($ROW["QUIT_REASON"]);
    $REMARK=format_cvs($ROW["REMARK"]);
    $SALARY=format_cvs($ROW["SALARY"]);

    $LEAVE_DEPT_NAME=format_cvs(substr(GetDeptNameById($LEAVE_DEPT),0,-1));
    $QUIT_TYPE=format_cvs(get_hrms_code_name($QUIT_TYPE,"HR_STAFF_LEAVE"));

    if($POSITION=="")
    {
        $query1 = "SELECT JOB_POSITION from HR_STAFF_INFO where USER_ID='$LEAVE_PERSON'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW1=mysql_fetch_array($cursor1))
            $POSITION=format_cvs($ROW1["JOB_POSITION"]);
    }

    /* $query1 = "SELECT JOB_POSITION from HR_STAFF_INFO where USER_ID='$LEAVE_PERSON'";
     $cursor1= exequery(TD::conn(),$query1);
     if($ROW1=mysql_fetch_array($cursor1))
        $JOB_POSITION=format_cvs($ROW1["JOB_POSITION"]);
     */
    $query1 = "SELECT STAFF_CARD_NO from HR_STAFF_INFO where USER_ID='$LEAVE_PERSON'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW1=mysql_fetch_array($cursor1))
        $STAFF_CARD_NO=format_cvs($ROW1["STAFF_CARD_NO"]);

    $LEAVE_PERSON=substr(GetUserNameById($LEAVE_PERSON),0,-1);
    $QUIT_TIME_PLAN=="0000-00-00"?"":$QUIT_TIME_PLAN;
    $QUIT_TIME_FACT=="0000-00-00"?"":$QUIT_TIME_FACT;
    $LAST_SALARY_TIME=="0000-00-00"?"":$LAST_SALARY_TIME;
    $OUTPUT = $LEAVE_PERSON.",".$LEAVE_DEPT_NAME.",".$POSITION.",".$QUIT_TYPE.",".$APPLICATION_DATE.",".$QUIT_TIME_PLAN.",".$QUIT_TIME_FACT.",".$LAST_SALARY_TIME.",".$TRACE.",".$MATERIALS_CONDITION.",".strip_tags($QUIT_REASON).",".$REMARK.",".$SALARY.",".$STAFF_CARD_NO;
    $objExcel->addRow($OUTPUT);
}

$objExcel->Save();
?>
</body>

</html>
