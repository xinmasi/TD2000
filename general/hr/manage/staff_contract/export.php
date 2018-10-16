<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_field.php");
ob_end_clean();

$TO_ID=str_replace(",","','",$TO_ID);
$TO_ID2=str_replace(",","','",$TO_ID2);
$PRIV_ID=str_replace(",","','",$PRIV_ID);



if($TO_ID=="ALL_DEPT")
    $TO_ID="";
$str="select USER_ID from user  ";
if($TO_ID!="" || $TO_ID2!="" ||$PRIV_ID!=="")
{
    $mark=0;
    $str.="  where  ";
    if($PRIV_ID!="")
    {
        $mark=1;
        $str.=" USER_PRIV in ('".$PRIV_ID."-1')";
    }
    if($TO_ID2!="")
    {
        if($mark==1)
        {
            $str.=" or  ";
        }
        $str.=" USER_ID in ('".$TO_ID2."-1')";
        $mark=1;
    }
    if($TO_ID!="")
    {
        if($TO_ID!="ALL_DEPT") //-1是避免传来的字符串多出来的“，”
        {
            if($mark==1)
            {
                $str.=" or  ";
            }
            $str.=" DEPT_ID in ('".$TO_ID."-1')";
        }
    }
}

$ss="";
if($BEGIN_DATE!="" && $END_DATE!="")
    $ss.=" and ADD_TIME>='".$BEGIN_DATE."' and ADD_TIME<='".$END_DATE."'";

//	$fieldArr=array('NAME','USER_ID','DEPTNAME','CONTRACT_NO','CONTRACT_TYPE','CONTRACT_SPECIALIZATION','MAKE_CONTRACT','TRAIL_EFFECTIVE_TIME','PROBATIONARY_PERIOD','TRAIL_OVER_TIME','PASS_OR_NOT','PROBATION_END_DATE','PROBATION_EFFECTIVE_DATE','ACTIVE_PERIOD','CONTRACT_END_TIME','REMOVE_OR_NOT','CONTRACT_REMOVE_TIME','STATUS','SIGN_TIMES','ADD_TIME','REMIND_TIME','REMIND_USER_NAME','REMARK');
//	$thArr=array(_("姓名"),_("用户名"),_("部门"),_("合同编号"),_("合同类型"),_("合同属性"),_("合同签订日期"),_("试用生效日期"),_("试用期限"),_("试用到期日期"),_("合同是否转正"),_("合同转正日期"),_("合同生效日期"),_("合同期限"),_("合同到期日期"),_("合同是否解除"),_("合同解除日期"),_("合同状态"),_("签约次数"),_("登记时间"),_("提醒时间"),_("提醒人员"),_("备注"));

$thArr= array(_("用户名"),_("姓名"),_("合同编号"),_("合同类型"),_("合同状态"),_("合同期限属性"),_("合同签约公司"),_("合同签订日期"),_("合同生效日期"),_("合同终止日期"),_("是否含试用期"),_("试用截止日期"),_("雇员是否转正"),_("合同是否已解除"),_("合同解除日期"),_("合同是否续签"),_("续签到期日期"),_("备注"));
$fieldArr= array('USER_ID','STAFF_NAME','STAFF_CONTRACT_NO','CONTRACT_TYPE','STATUS','CONTRACT_SPECIALIZATION','CONTRACT_ENTERPRIES','MAKE_CONTRACT','PROBATION_EFFECTIVE_DATE','CONTRACT_END_TIME','IS_TRIAL','TRAIL_OVER_TIME','PASS_OR_NOT','REMOVE_OR_NOT','CONTRACT_REMOVE_TIME','IS_RENEW','RENEW_TIME','REMARK');

if($isSelected == "selected" && $fieldArrStr!="")//选择字段
{
    $fieldArr = explode(",",$fieldArrStr);
    if(MYOA_IS_UN == 1)
        $OUTPUT_HEAD = explode(",",$fieldArrStr);
    else
        $OUTPUT_HEAD = explode(",",$CONTENT_NAME);
}
else
{
    if(MYOA_IS_UN == 1)
        $OUTPUT_HEAD = $fieldArr;
    else
        $OUTPUT_HEAD = $thArr;
}
require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("员工合同"));
$objExcel->addHead($OUTPUT_HEAD);

$query = "SELECT HR_STAFF_CONTRACT.*,DEPARTMENT.DEPT_NAME FROM HR_STAFF_CONTRACT LEFT OUTER JOIN USER  ON USER_ID=STAFF_NAME LEFT OUTER JOIN DEPARTMENT ON DEPARTMENT.DEPT_ID=USER.DEPT_ID where ";
$query.= hr_priv("STAFF_NAME");
$query.= " and STAFF_NAME IN (".$str.")".$ss;
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $STAFF_NAME=format_cvs(substr(GetUserNameById($ROW["STAFF_NAME"]),0,-1));
    $USER_ID = format_cvs($ROW["STAFF_NAME"]);
    $STAFF_CONTRACT_NO=format_cvs($ROW["STAFF_CONTRACT_NO"]);
    $CONTRACT_TYPE=format_cvs(get_hrms_code_name($ROW["CONTRACT_TYPE"],"HR_STAFF_CONTRACT1"));
    $STATUS=format_cvs(get_hrms_code_name($ROW["STATUS"],"HR_STAFF_CONTRACT2"));
    $CONTRACT_SPECIALIZATION=format_cvs($ROW["CONTRACT_SPECIALIZATION"]);
    $CONTRACT_ENTERPRIES=format_cvs(get_hrms_code_name($ROW["CONTRACT_ENTERPRIES"],"HR_ENTERPRISE"));
    $MAKE_CONTRACT=format_cvs($ROW["MAKE_CONTRACT"]=="0000-00-00"?"":$ROW["MAKE_CONTRACT"]);
    $PROBATION_EFFECTIVE_DATE=format_cvs($ROW["PROBATION_EFFECTIVE_DATE"]=="0000-00-00"?"":$ROW["PROBATION_EFFECTIVE_DATE"]);
    $CONTRACT_END_TIME=format_cvs($ROW["CONTRACT_END_TIME"]=="0000-00-00"?"":$ROW["CONTRACT_END_TIME"]);
    $IS_TRIAL=format_cvs($ROW["IS_TRIAL"]);
    $TRAIL_OVER_TIME=format_cvs($ROW["TRAIL_OVER_TIME"]=="0000-00-00"?"":$ROW["TRAIL_OVER_TIME"]);
    $PASS_OR_NOT=$ROW["PASS_OR_NOT"];
    $REMOVE_OR_NOT=$ROW["REMOVE_OR_NOT"];
    $CONTRACT_REMOVE_TIME=format_cvs($ROW["CONTRACT_REMOVE_TIME"]=="0000-00-00"?"":$ROW["CONTRACT_REMOVE_TIME"]);
    $IS_RENEW=$ROW["IS_RENEW"];
    $RENEW_TIME=format_cvs($ROW["RENEW_TIME"]=="0000-00-00"?"":$ROW["RENEW_TIME"]);


    $REMARK=format_cvs($ROW["REMARK"]);
    if($CONTRACT_SPECIALIZATION==1)
        $CONTRACT_SPECIALIZATION=_("有固定期限");
    elseif($CONTRACT_SPECIALIZATION==2)
        $CONTRACT_SPECIALIZATION=_("无固定期限");
    elseif($CONTRACT_SPECIALIZATION==3)
        $CONTRACT_SPECIALIZATION=_("以完成一定工作任务为期限");

    if($PASS_OR_NOT==1)
        $PASS_OR_NOT=_("是");
    else
        $PASS_OR_NOT=_("否");

    if($REMOVE_OR_NOT==1)
        $REMOVE_OR_NOT=_("是");
    else
        $REMOVE_OR_NOT=_("否");

    if($IS_TRIAL==1)
        $IS_TRIAL=_("是");
    else
        $IS_TRIAL=_("否");
    if($IS_RENEW==1)
        $IS_RENEW=_("是");
    else
        $IS_RENEW=_("否");
    $OUT_PUT="";
    $count=0;
    foreach($fieldArr as $value)
        $OUT_PUT.=$$value.",";
    $objExcel->addRow("$OUT_PUT");
}
$objExcel->Save();
?>