<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");
ob_end_clean();
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------合法性校验---------

if(MYOA_IS_UN == 1)
    $OUTPUT_HEAD = "BY_CARE_STAFFS_NAME,TYPE_NAME,CARE_FEES,CARE_DATE,PARTICIPANTS_NAME,CARE_CONTENT,CARE_EFFECTS";
else
    $OUTPUT_HEAD = _("被关怀员工").","._("关怀类型").","._("关怀开支费用/人").","._("关怀日期").","._("参与人").","._("关怀内容").","._("关怀效果");

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("员工关怀信息"));
$objExcel->addHead($OUTPUT_HEAD);


//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($HR_TABLE==1)
{
    $YEAR=date("Y",time());
    $START_DATE=$YEAR."-01-01";
    $END_DATE=$YEAR."-12-31";
    $CONDITION_STR.=" and CARE_DATE >= '$START_DATE' and CARE_DATE <= '$END_DATE'";
    $CONDITION_STR.=" and CARE_FEES >= '$START_DATE' and CARE_FEES <= '$END_DATE'";
}
else
{
    if($CARE_CONTENT!="")
        $CONDITION_STR.=" and CARE_CONTENT like '%".$CARE_CONTENT."%'";
    if($CARE_TYPE!="")
        $CONDITION_STR.=" and CARE_TYPE='$CARE_TYPE'";
    if($BY_CARE_STAFFS!="")
        $CONDITION_STR.=" and BY_CARE_STAFFS='$BY_CARE_STAFFS'";
    if($CARE_DATE1!="")
        $CONDITION_STR.=" and CARE_DATE>='$CARE_DATE1'";
    if($CARE_DATE2!="")
        $CONDITION_STR.=" and CARE_DATE<='$CARE_DATE2'";
    if($CARE_FEES1!="")
        $CONDITION_STR.=" and CARE_FEES>='$CARE_FEES1'";
    if($CARE_FEES2!="")
        $CONDITION_STR.=" and CARE_FEES<='$CARE_FEES2'";
    if($PARTICIPANTS!="")
        $CONDITION_STR.=" and PARTICIPANTS like '%".$PARTICIPANTS."%'";
}
$CONDITION_STR = hr_priv("BY_CARE_STAFFS").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_CARE where ".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$LEAVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $LEAVE_COUNT++;
    $BY_CARE_STAFFS=format_cvs($ROW["BY_CARE_STAFFS"]);
    $CARE_DATE=format_cvs($ROW["CARE_DATE"]);
    $CARE_CONTENT=format_cvs($ROW["CARE_CONTENT"]);
    $PARTICIPANTS=$ROW["PARTICIPANTS"];
    $CARE_EFFECTS=format_cvs($ROW["CARE_EFFECTS"]);
    $CARE_FEES=format_cvs($ROW["CARE_FEES"]);
    $CARE_TYPE=format_cvs($ROW["CARE_TYPE"]);
    $ATTACHMENT_ID=format_cvs($ROW["ATTACHMENT_ID"]);
    $ATTACHMENT_NAME =format_cvs($ROW["ATTACHMENT_NAME"]);
    $CARE_FEES_ALL+=$CARE_FEES;

    $TYPE_NAME=get_hrms_code_name($CARE_TYPE,"HR_STAFF_CARE");

    $BY_CARE_STAFFS_NAME = td_trim(GetUserNameById($BY_CARE_STAFFS));
    if($BY_CARE_STAFFS_NAME=="")
    {
        $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$BY_CARE_STAFFS'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW1=mysql_fetch_array($cursor1))
            $BY_CARE_STAFFS_NAME=$ROW1["STAFF_NAME"];
        $BY_CARE_STAFFS_NAME=$BY_CARE_STAFFS_NAME."("._("用户已删除").")";
    }
    else
    {
        $query2 = "SELECT DEPT_ID from USER where USER_ID='$BY_CARE_STAFFS'";
        $cursor2= exequery(TD::conn(),$query2);
        if($ROW2=mysql_fetch_array($cursor2))
            $DEPT_ID=$ROW2['DEPT_ID'];
        if($DEPT_ID=='0')
            $BY_CARE_STAFFS_NAME=$BY_CARE_STAFFS_NAME."("._("离职/外部人员").")";
    }

    $CARE_CONTENT = strip_tags($CARE_CONTENT);
    $CARE_CONTENT = str_replace("&nbsp;","  ",$CARE_CONTENT);

    $PARTICIPANTS_NAME = substr(GetUserNameById($PARTICIPANTS),0,-1);
    $PARTICIPANTS_NAME=str_replace(",",_("，"),$PARTICIPANTS_NAME);
    $OUTPUT = $BY_CARE_STAFFS_NAME.",".$TYPE_NAME.",".$CARE_FEES.",".$CARE_DATE.",".$PARTICIPANTS_NAME.",".$CARE_CONTENT.",".$CARE_EFFECTS;
    $objExcel->addRow($OUTPUT);
}

$objExcel->Save();
?>
</body>

</html>
