<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------合法性校验---------

if($START_DATE!="")
{
    $TIME_OK=is_date($START_DATE);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $START_DATE=$START_DATE." 00:00:00";
}

if($END_DATE!="")
{
    $TIME_OK=is_date($END_DATE);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $END_DATE=$END_DATE." 23:59:59";
}

if(MYOA_IS_UN == 1)
    $OUTPUT_HEAD = "STAFF_NAME,WORK_UNIT,START_DATE,END_DATE,MOBILE,WORK_BRANCH,POST_OF_JOB,WITNESS,WORK_CONTENT,KEY_PERFORMANCE,REASON_FOR_LEAVING,REMARK";
else
    $OUTPUT_HEAD = _("单位员工").","._("工作单位").","._("开始日期").","._("结束日期").","._("行业类别").","._("所在部门").","._("担任职务").","._("证明人").","._("工作内容").","._("主要业绩").","._("离职原因").","._("备注");

ob_end_clean();

require_once ('inc/ExcelWriter.php');
$objExcel = new ExcelWriter();
$objExcel->setFileName(_("工作经历信息"));
$objExcel->addHead($OUTPUT_HEAD);


//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($HR_TABLE==1)
{
    $YEAR=date("Y",time());
    $START_TIME=$YEAR."-01-01";
    $END_TIME=$YEAR."-12-31";
    $CONDITION_STR.=" and START_DATE >= '$START_TIME' and END_DATE <= '$END_TIME'";
}
else
{
    if($STAFF_NAME!="")
        $CONDITION_STR.=" and STAFF_NAME='$STAFF_NAME'";
    if($POST_OF_JOB!="")
        $CONDITION_STR.=" and POST_OF_JOB like '%".$POST_OF_JOB."%'";
    if($WORK_UNIT!="")
        $CONDITION_STR.=" and WORK_UNIT like '%".$WORK_UNIT."%'";
    if($MOBILE!="")
        $CONDITION_STR.=" and MOBILE like '%".$MOBILE."%'";
    if($WORK_CONTENT!="")
        $CONDITION_STR.=" and WORK_CONTENT like '%".$WORK_CONTENT."%'";
    if($KEY_PERFORMANCE!="")
        $CONDITION_STR.=" and KEY_PERFORMANCE like '%".$KEY_PERFORMANCE."%'";
}
$CONDITION_STR = hr_priv("STAFF_NAME").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_WORK_EXPERIENCE where ".$CONDITION_STR."order by ADD_TIME desc";

$cursor=exequery(TD::conn(),$query);
$LEAVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $EXPERIENCE_COUNT++;
    $STAFF_NAME=format_cvs($ROW["STAFF_NAME"]);
    $START_DATE=format_cvs($ROW["START_DATE"]);
    $END_DATE=format_cvs($ROW["END_DATE"]);
    $WORK_UNIT=format_cvs($ROW["WORK_UNIT"]);
    $MOBILE=format_cvs($ROW["MOBILE"]);
    $WORK_BRANCH=format_cvs($ROW["WORK_BRANCH"]);
    $POST_OF_JOB=format_cvs($ROW["POST_OF_JOB"]);
    $WORK_CONTENT=format_cvs($ROW["WORK_CONTENT"]);
    $KEY_PERFORMANCE=format_cvs($ROW["KEY_PERFORMANCE"]);
    $REASON_FOR_LEAVING=format_cvs($ROW["REASON_FOR_LEAVING"]);
    $WITNESS=format_cvs($ROW["WITNESS"]);
    $REMARK=format_cvs($ROW["REMARK"]);

    $KEY_PERFORMANCE = strip_tags($KEY_PERFORMANCE);
    $KEY_PERFORMANCE = str_replace("&nbsp;","  ",$KEY_PERFORMANCE);

    $STAFF_NAME1=td_trim(GetUserNameById($STAFF_NAME));
    if($STAFF_NAME1=="")
    {
        $query2 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
        $cursor2= exequery(TD::conn(),$query2);
        if($ROW2=mysql_fetch_array($cursor2))
            $STAFF_NAME1=$ROW2["STAFF_NAME"];
        $STAFF_NAME1=$STAFF_NAME1._("（用户已删除）");
    }
    if(strlen($WORK_UNIT) > 20)
        $WORK_UNIT=substr($WORK_UNIT, 0, 20);
    $OUTPUT = $STAFF_NAME1.",".$WORK_UNIT.",".$START_DATE.",".$END_DATE.",".$MOBILE.",".$WORK_BRANCH.",".$POST_OF_JOB.",".$WITNESS.",".$WORK_CONTENT.",".$KEY_PERFORMANCE.",".$REASON_FOR_LEAVING.",".$REMARK;
    $objExcel->addRow($OUTPUT);
}

$objExcel->Save();
?>
</body>

</html>
