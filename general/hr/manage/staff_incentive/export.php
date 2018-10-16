<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------合法性校验---------

if($INCENTIVE_TIME1!="")
{
    $TIME_OK=is_date($INCENTIVE_TIME1);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $INCENTIVE_TIME1=$INCENTIVE_TIME1." 00:00:00";
}

if($INCENTIVE_TIME2!="")
{
    $TIME_OK=is_date($INCENTIVE_TIME2);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $INCENTIVE_TIME2=$INCENTIVE_TIME2." 23:59:59";
}

if(MYOA_IS_UN == 1)
    $OUTPUT_HEAD = "NAME,ITEM,DATE,SALARY_MONTH,TYPE,AMOUNT,DESCRIPTION,ADD_TIME,LAST_UPDATE_TIME,MEMO";
else
    $OUTPUT_HEAD = _("单位员工").","._("奖惩项目").","._("奖惩日期").","._("工资月份").","._("奖惩属性").","._("奖惩金额（元）").","._("奖惩说明").","._("登记时间").","._("最后修改时间").","._("备注");

require_once ('inc/ExcelWriter.php');
ob_end_clean();
$objExcel = new ExcelWriter();
$objExcel->setFileName(_("奖惩管理信息"));
$objExcel->addHead($OUTPUT_HEAD);


//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($HR_TABLE==1)
{
    $YEAR=date("Y",time());
    $START_DATE=$YEAR."-01-01";
    $END_DATE=$YEAR."-12-31";
    $CONDITION_STR.=" and INCENTIVE_TIME >= '$START_DATE' and INCENTIVE_TIME <= '$END_DATE'";
}
else
{
    if($STAFF_NAME!="")
        $CONDITION_STR.=" and STAFF_NAME='$STAFF_NAME'";
    if($INCENTIVE_ITEM!="")
        $CONDITION_STR.=" and INCENTIVE_ITEM='$INCENTIVE_ITEM'";
    if($INCENTIVE_TYPE!="")
        $CONDITION_STR.=" and INCENTIVE_TYPE='$INCENTIVE_TYPE'";
    if($INCENTIVE_TIME1!="")
        $CONDITION_STR.=" and INCENTIVE_TIME>='$INCENTIVE_TIME1'";
    if($INCENTIVE_TIME2!="")
        $CONDITION_STR.=" and INCENTIVE_TIME<='$INCENTIVE_TIME2'";
}

$CONDITION_STR = hr_priv("STAFF_NAME").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_INCENTIVE where".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);

$INCENTIVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $INCENTIVE_COUNT++;

    $STAFF_NAME=format_cvs($ROW["STAFF_NAME"]);
    $INCENTIVE_TIME=format_cvs($ROW["INCENTIVE_TIME"]);
    $INCENTIVE_ITEM=format_cvs($ROW["INCENTIVE_ITEM"]);
    $INCENTIVE_TYPE=format_cvs($ROW["INCENTIVE_TYPE"]);
    $SALARY_MONTH=format_cvs($ROW["SALARY_MONTH"]);
    $INCENTIVE_AMOUNT=format_cvs($ROW["INCENTIVE_AMOUNT"]);
    $INCENTIVE_DESCRIPTION=format_cvs($ROW["INCENTIVE_DESCRIPTION"]);
    $REMARK=format_cvs($ROW["REMARK"]);
    $ATTACHMENT_ID=format_cvs($ROW["ATTACHMENT_ID"]);
    $ATTACHMENT_NAME=format_cvs($ROW["ATTACHMENT_NAME"]);
    $ADD_TIME=format_cvs($ROW["ADD_TIME"]);
    $LAST_UPDATE_TIME =format_cvs($ROW["LAST_UPDATE_TIME"]);

    $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
    if($STAFF_NAME1=="")
        $STAFF_NAME1="<font color='red'>"._("用户已删除")."</font>";

    $INCENTIVE_ITEM=get_hrms_code_name($INCENTIVE_ITEM,"HR_STAFF_INCENTIVE1");
    $INCENTIVE_TYPE=get_hrms_code_name($INCENTIVE_TYPE,"INCENTIVE_TYPE");
    $STAFF_NAME1 = strip_tags($STAFF_NAME1);
    $INCENTIVE_DESCRIPTION = strip_tags($INCENTIVE_DESCRIPTION);
    $INCENTIVE_DESCRIPTION = str_replace("&nbsp;","  ",$INCENTIVE_DESCRIPTION);

    if($LAST_UPDATE_TIME=="0000-00-00 00:00:00")
    {
        $LAST_UPDATE_TIME="";
    }


    $OUTPUT = $STAFF_NAME1.",".$INCENTIVE_ITEM.",".$INCENTIVE_TIME.",".$SALARY_MONTH.",".$INCENTIVE_TYPE.",".$INCENTIVE_AMOUNT.",".$INCENTIVE_DESCRIPTION.",".$ADD_TIME.",".$LAST_UPDATE_TIME.",".$REMARK;
    $objExcel->addRow($OUTPUT);
}

$objExcel->Save();
?>