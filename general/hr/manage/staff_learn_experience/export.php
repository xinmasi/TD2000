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
    { Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $START_DATE=$START_DATE." 00:00:00";
}

if($END_DATE!="")
{
    $TIME_OK=is_date($END_DATE);

    if(!$TIME_OK)
    { Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $END_DATE=$END_DATE." 23:59:59";
}

if(MYOA_IS_UN == 1)
    $OUTPUT_HEAD = "STAFF_NAME,MAJOR,ACADEMY_DEGREE,DEGREE,POSITION,WITNESS,SCHOOL,SCHOOL_ADDRESS,START_DATE,END_DATE,AWARDING,CERTIFICATES,REMARK";
else
    $OUTPUT_HEAD = _("单位员工").","._("所学专业").","._("所获学历").","._("所获学位").","._("曾任班干").","._("证明人").","._("所在院校").","._("院校所在地").","._("开始日期").","._("结束日期").","._("获奖情况").","._("所获证书").","._("备注");

require_once ('inc/ExcelWriter.php');
ob_end_clean();
$objExcel = new ExcelWriter();
$objExcel->setFileName(_("学习经历信息"));
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
    if($MAJOR!="")
        $CONDITION_STR.=" and MAJOR like '%".$MAJOR."%'";
    if($ACADEMY_DEGREE!="")
        $CONDITION_STR.=" and ACADEMY_DEGREE ='".$ACADEMY_DEGREE."'";
    if(trim($SCHOOL)!="")
        $CONDITION_STR.=" and SCHOOL like '%".$SCHOOL."%'";
    if($CERTIFICATES!="")
        $CONDITION_STR.=" and CERTIFICATES like '%".$CERTIFICATES."%'";
}
$CONDITION_STR = hr_priv("STAFF_NAME").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_LEARN_EXPERIENCE where".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$LEAVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $EXPERIENCE_COUNT++;
    $STAFF_NAME=format_cvs($ROW["STAFF_NAME"]);
    $START_DATE=format_cvs($ROW["START_DATE"]);
    $END_DATE=format_cvs($ROW["END_DATE"]);
    $SCHOOL=format_cvs($ROW["SCHOOL"]);
    $SCHOOL_ADDRESS=format_cvs($ROW["SCHOOL_ADDRESS"]);
    $MAJOR=format_cvs($ROW["MAJOR"]);
    $ACADEMY_DEGREE=format_cvs($ROW["ACADEMY_DEGREE"]);
    $ACADEMY_DEGREE=format_cvs(get_hrms_code_name($ACADEMY_DEGREE,'STAFF_HIGHEST_SCHOOL'));
    $DEGREE=format_cvs($ROW["DEGREE"]);
    $DEGREE=format_cvs(get_hrms_code_name($DEGREE,"EMPLOYEE_HIGHEST_DEGREE"));
    $POSITION=format_cvs($ROW["POSITION"]);
    $AWARDING=format_cvs($ROW["AWARDING"]);
    $CERTIFICATES=format_cvs($ROW["CERTIFICATES"]);
    $WITNESS=format_cvs($ROW["WITNESS"]);
    $REMARK=format_cvs($ROW["REMARK"]);
    $ATTACHMENT_ID=format_cvs($ROW["ATTACHMENT_ID"]);
    $ATTACHMENT_NAME=format_cvs($ROW["ATTACHMENT_NAME"]);
    $ADD_TIME=format_cvs($ROW["ADD_TIME"]);
    $LAST_UPDATE_TIME =format_cvs($ROW["LAST_UPDATE_TIME"]);

    $STAFF_NAME1=td_trim(GetUserNameById($STAFF_NAME));
    if($STAFF_NAME1=="")
    {
        $query2 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
        $cursor2= exequery(TD::conn(),$query2);
        if($ROW2=mysql_fetch_array($cursor2))
            $STAFF_NAME1=$ROW2["STAFF_NAME"];
        $STAFF_NAME1=$STAFF_NAME1._("（用户已删除）");
    }

    $OUTPUT = $STAFF_NAME1.",".$MAJOR.",".$ACADEMY_DEGREE.",".$DEGREE.",".$POSITION.",".$WITNESS.",".$SCHOOL.",".$SCHOOL_ADDRESS.",".$START_DATE.",".$END_DATE.",".$AWARDING.",".$CERTIFICATES.",".$REMARK;
    $objExcel->addRow($OUTPUT);
}

$objExcel->Save();
?>
</body>

</html>
