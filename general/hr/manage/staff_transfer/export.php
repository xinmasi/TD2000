<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------合法性校验---------
if($TRANSFER_DATE1!="")
{
    $TIME_OK=is_date($TRANSFER_DATE1);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $TRANSFER_DATE1=$TRANSFER_DATE1." 00:00:00";
}

if($TRANSFER_DATE2!="")
{
    $TIME_OK=is_date($TRANSFER_DATE2);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $TRANSFER_DATE2=$TRANSFER_DATE2." 23:59:59";
}

if($TRANSFER_EFFECTIVE_DATE1!="")
{
    $TIME_OK=is_date($TRANSFER_EFFECTIVE_DATE1);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $TRANSFER_EFFECTIVE_DATE1=$TRANSFER_EFFECTIVE_DATE1." 00:00:00";
}

if($TRANSFER_EFFECTIVE_DATE2!="")
{
    $TIME_OK=is_date($TRANSFER_EFFECTIVE_DATE2);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $TRANSFER_EFFECTIVE_DATE2=$TRANSFER_EFFECTIVE_DATE2." 23:59:59";
}
if(MYOA_IS_UN == 1)
    $OUTPUT_HEAD = "TRANSFER_PERSON_NAME,TRANSFER_TYPE,TRANSFER_DATE,TRANSFER_EFFECTIVE_DATE,TRAN_COMPANY_BEFORE,TRAN_COMPANY_AFTER,TRAN_DEPT_BEFORE_NAME,TRAN_DEPT_AFTER_NAME,TRAN_POSITION_BEFORE,TRAN_POSITION_AFTER,MATERIALS_CONDITION,TRAN_REASON,REMARK";
else
    $OUTPUT_HEAD = _("调动人员").","._("调动类型").","._("调动日期").","._("调动生效日期").","._("调动前单位").","._("调动后单位").","._("调动前部门").","._("调动后部门").","._("调动前职务").","._("调动后职务").","._("调动手续办理").","._("调动原因").","._("备注");

ob_end_clean();

require_once ('inc/ExcelWriter.php');
$objExcel = new ExcelWriter();
$objExcel->setFileName(_("人事调动信息"));
$objExcel->addHead($OUTPUT_HEAD);


//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($HR_TABLE==1)
{
    $YEAR=date("Y",time());
    $START_TIME=$YEAR."-01-01";
    $END_TIME=$YEAR."-12-31";
    $CONDITION_STR.=" and TRANSFER_DATE >= '$START_TIME' and TRANSFER_DATE <= '$END_TIME'";
    $CONDITION_STR.=" and TRANSFER_EFFECTIVE_DATE >= '$START_TIME' and TRANSFER_EFFECTIVE_DATE <= '$END_TIME'";
}
else
{
    if($TRAN_REASON!="")
        $CONDITION_STR.=" and TRAN_REASON like '%".$TRAN_REASON."%'";
    if($TRANSFER_PERSON!="")
        $CONDITION_STR.=" and TRANSFER_PERSON='$TRANSFER_PERSON'";
    if($TRANSFER_TYPE!="")
        $CONDITION_STR.=" and TRANSFER_TYPE='$TRANSFER_TYPE'";
    if($TRANSFER_DATE1!="")
        $CONDITION_STR.=" and TRANSFER_DATE>='$TRANSFER_DATE1'";
    if($TRANSFER_DATE2!="")
        $CONDITION_STR.=" and TRANSFER_DATE<='$TRANSFER_DATE2'";
    if($TRANSFER_EFFECTIVE_DATE1!="")
        $CONDITION_STR.=" and TRANSFER_EFFECTIVE_DATE>='$TRANSFER_EFFECTIVE_DATE1'";
    if($TRANSFER_EFFECTIVE_DATE2!="")
        $CONDITION_STR.=" and TRANSFER_EFFECTIVE_DATE<='$TRANSFER_EFFECTIVE_DATE2'";
}
$CONDITION_STR = hr_priv("TRANSFER_PERSON").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_TRANSFER where ".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$LEAVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $EXPERIENCE_COUNT++;
    $TRANSFER_PERSON=format_cvs($ROW["TRANSFER_PERSON"]);
    $TRANSFER_DATE=format_cvs($ROW["TRANSFER_DATE"]);
    $TRANSFER_EFFECTIVE_DATE=format_cvs($ROW["TRANSFER_EFFECTIVE_DATE"]);
    $TRANSFER_TYPE=format_cvs($ROW["TRANSFER_TYPE"]);
    $TRAN_COMPANY_BEFORE=format_cvs($ROW["TRAN_COMPANY_BEFORE"]);
    $TRAN_DEPT_BEFORE=format_cvs($ROW["TRAN_DEPT_BEFORE"]);
    $TRAN_POSITION_BEFORE=format_cvs($ROW["TRAN_POSITION_BEFORE"]);
    $TRAN_COMPANY_AFTER=format_cvs($ROW["TRAN_COMPANY_AFTER"]);
    $TRAN_DEPT_AFTER=format_cvs($ROW["TRAN_DEPT_AFTER"]);
    $TRAN_POSITION_AFTER=format_cvs($ROW["TRAN_POSITION_AFTER"]);
    $TRAN_REASON=format_cvs($ROW["TRAN_REASON"]);
    $MATERIALS_CONDITION=format_cvs($ROW["MATERIALS_CONDITION"]);
    $ATTACHMENT_ID=format_cvs($ROW["ATTACHMENT_ID"]);
    $ATTACHMENT_NAME=format_cvs($ROW["ATTACHMENT_NAME"]);
    $REMARK=format_cvs($ROW["REMARK"]);
    $ADD_TIME=format_cvs($ROW["ADD_TIME"]);
    $LAST_UPDATE_TIME =format_cvs($ROW["LAST_UPDATE_TIME"]);

    $TRANSFER_TYPE=get_hrms_code_name($TRANSFER_TYPE,"HR_STAFF_TRANSFER");

    $TRANSFER_PERSON_NAME=td_trim(GetUserNameById($TRANSFER_PERSON));
    if($TRANSFER_PERSON_NAME=="")
    {
        $query2 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
        $cursor2= exequery(TD::conn(),$query2);
        if($ROW2=mysql_fetch_array($cursor2))
            $TRANSFER_PERSON_NAME=$ROW2["STAFF_NAME"];
        $TRANSFER_PERSON_NAME=$TRANSFER_PERSON_NAME._("（用户已删除）");
    }
    $TRAN_DEPT_BEFORE_NAME=td_trim(GetDeptNameById($TRAN_DEPT_BEFORE));
    $TRAN_DEPT_AFTER_NAME=td_trim(GetDeptNameById($TRAN_DEPT_AFTER));

    $TRAN_REASON = strip_tags($TRAN_REASON);
    $TRAN_REASON = str_replace("&nbsp;","  ",$TRAN_REASON);

    $OUTPUT = $TRANSFER_PERSON_NAME.",".$TRANSFER_TYPE.",".$TRANSFER_DATE.",".$TRANSFER_EFFECTIVE_DATE.",".$TRAN_COMPANY_BEFORE.",".$TRAN_COMPANY_AFTER.",".$TRAN_DEPT_BEFORE_NAME.",".$TRAN_DEPT_AFTER_NAME.",".$TRAN_POSITION_BEFORE.",".$TRAN_POSITION_AFTER.",".$MATERIALS_CONDITION.",".$TRAN_REASON.",".$REMARK;
    $objExcel->addRow($OUTPUT);
}

$objExcel->Save();
?>
</body>

</html>
