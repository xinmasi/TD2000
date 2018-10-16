<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------合法性校验---------
if($REPORT_TIME1!="")
{
    $TIME_OK=is_date($REPORT_TIME1);
    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $REPORT_TIME1=$REPORT_TIME1." 00:00:00";
}

if($REPORT_TIME2!="")
{
    $TIME_OK=is_date($REPORT_TIME2);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $REPORT_TIME2=$REPORT_TIME2." 23:59:59";
}

if($RECEIVE_TIME1!="")
{
    $TIME_OK=is_date($RECEIVE_TIME1);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $RECEIVE_TIME1=$RECEIVE_TIME1." 00:00:00";
}

if($RECEIVE_TIME2!="")
{
    $TIME_OK=is_date($RECEIVE_TIME2);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $RECEIVE_TIME2=$RECEIVE_TIME2." 23:59:59";
}
if(MYOA_IS_UN == 1)
    $OUTPUT_HEAD = "BY_EVALU_NAME,APPROVE_PERSON_NAME,POST_NAME,GET_METHOD,REPORT_TIME,RECEIVE_TIME,APPROVE_NEXT_TIME,APPROVE_NEXT,EMPLOY_POST,EMPLOY_COMPANY,START_DATE,END_DATE,REMARK";
else
    $OUTPUT_HEAD = _("评定对象").","._("批准人").","._("获取职称").","._("获取方式").","._("申报时间").","._("获取时间").","._("下次申报时间").","._("下次申报职称").","._("聘用职务").","._("聘用单位").","._("聘用开始时间").","._("聘用结束时间").","._("评定详情");

ob_end_clean();

require_once ('inc/ExcelWriter.php');
$objExcel = new ExcelWriter();
$objExcel->setFileName(_("职称评定管理信息"));
$objExcel->addHead($OUTPUT_HEAD);


//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($HR_TABLE==1)
{
    $YEAR=date("Y",time());
    $START_DATE=$YEAR."-01-01";
    $END_DATE=$YEAR."-12-31";
    $CONDITION_STR.=" and REPORT_TIME >= '$START_DATE' and REPORT_TIME <= '$END_DATE'";
    $CONDITION_STR.=" and RECEIVE_TIME >= '$START_DATE' and RECEIVE_TIME <= '$END_DATE'";
}
else
{
    if($REMARK!="")
        $CONDITION_STR.=" and REMARK like '%".$REMARK."%'";
    if($EMPLOY_COMPANY!="")
        $CONDITION_STR.=" and EMPLOY_COMPANY like '%".$EMPLOY_COMPANY."%'";
    if($EMPLOY_POST!="")
        $CONDITION_STR.=" and EMPLOY_POST like '%".$EMPLOY_POST."%'";
    if($GET_METHOD!="")
        $CONDITION_STR.=" and GET_METHOD like '%".$GET_METHOD."%'";
    if($POST_NAME!="")
        $CONDITION_STR.=" and POST_NAME='$POST_NAME'";
    if($APPROVE_PERSON!="")
        $CONDITION_STR.=" and APPROVE_PERSON='$APPROVE_PERSON'";
    if($BY_EVALU_STAFFS!="")
        $CONDITION_STR.=" and BY_EVALU_STAFFS='$BY_EVALU_STAFFS'";
    if($REPORT_TIME1!="")
        $CONDITION_STR.=" and REPORT_TIME>='$REPORT_TIME1'";
    if($REPORT_TIME2!="")
        $CONDITION_STR.=" and REPORT_TIME<='$REPORT_TIME2'";
    if($RECEIVE_TIME1!="")
        $CONDITION_STR.=" and RECEIVE_TIME>='$RECEIVE_TIME1'";
    if($RECEIVE_TIME2!="")
        $CONDITION_STR.=" and RECEIVE_TIME<='$RECEIVE_TIME2'";
}
$CONDITION_STR = hr_priv("BY_EVALU_STAFFS").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_TITLE_EVALUATION where ".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$LEAVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $LEAVE_COUNT++;
    $POST_NAME=format_cvs($ROW["POST_NAME"]);
    $GET_METHOD=format_cvs($ROW["GET_METHOD"]);
    $REPORT_TIME=format_cvs($ROW["REPORT_TIME"]);
    $RECEIVE_TIME=format_cvs($ROW["RECEIVE_TIME"]);
    $APPROVE_PERSON=format_cvs($ROW["APPROVE_PERSON"]);
    $APPROVE_NEXT=format_cvs($ROW["APPROVE_NEXT"]);
    $APPROVE_NEXT_TIME=format_cvs($ROW["APPROVE_NEXT_TIME"]);
    $REMARK=format_cvs($ROW["REMARK"]);
    $EMPLOY_POST=format_cvs($ROW["EMPLOY_POST"]);
    $START_DATE=format_cvs($ROW["START_DATE"]);
    $END_DATE=format_cvs($ROW["END_DATE"]);
    $EMPLOY_COMPANY=format_cvs($ROW["EMPLOY_COMPANY"]);
    $BY_EVALU_STAFFS=format_cvs($ROW["BY_EVALU_STAFFS"]);
    $ADD_TIME=format_cvs($ROW["ADD_TIME"]);
    $LAST_UPDATE_TIME =format_cvs($ROW["LAST_UPDATE_TIME"]);

    $GET_METHOD=get_hrms_code_name($GET_METHOD,"HR_STAFF_TITLE_EVALUATION");

    $BY_EVALU_NAME=td_trim(GetUserNameById($BY_EVALU_STAFFS));
    if($BY_EVALU_NAME=="")
    {
        $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$BY_EVALU_STAFFS'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW1=mysql_fetch_array($cursor1))
            $BY_EVALU_NAME=$ROW1["STAFF_NAME"];
        $BY_EVALU_NAME=$BY_EVALU_NAME."("._("用户已删除").")";
    }
    $APPROVE_PERSON_NAME=td_trim(GetUserNameById($APPROVE_PERSON));
    $REMARK = strip_tags($REMARK);
    $REMARK = str_replace("&nbsp;","  ",$REMARK);

    $OUTPUT = $BY_EVALU_NAME.",".$APPROVE_PERSON_NAME.",".$POST_NAME.",".$GET_METHOD.",".$REPORT_TIME.",".$RECEIVE_TIME.",".$APPROVE_NEXT_TIME.",".$APPROVE_NEXT.",".$EMPLOY_POST.",".$EMPLOY_COMPANY.",".$START_DATE.",".$END_DATE.",".$REMARK;
    $objExcel->addRow($OUTPUT);
}

$objExcel->Save();
?>
</body>

</html>
