<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");


$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------合法性校验---------

if($ISSUE_DATE1!="")
{
    $TIME_OK=is_date($ISSUE_DATE1);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $ISSUE_DATE1=$ISSUE_DATE1." 00:00:00";
}

if($ISSUE_DATE2!="")
{
    $TIME_OK=is_date($ISSUE_DATE2);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $ISSUE_DATE2=$ISSUE_DATE2." 23:59:59";
}

if($EXPIRE_DATE1!="")
{
    $TIME_OK=is_date($EXPIRE_DATE1);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $EXPIRE_DATE1=$EXPIRE_DATE1." 00:00:00";
}

if($EXPIRE_DATE2!="")
{
    $TIME_OK=is_date($EXPIRE_DATE2);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
        Button_Back();
        exit;
    }
    $EXPIRE_DATE2=$EXPIRE_DATE2." 23:59:59";
}

if(MYOA_IS_UN == 1)
    $OUTPUT_HEAD = "STAFF_NAME,ABILITY_NAME,SPECIAL_WORK,SKILLS_LEVEL,SKILLS_CERTIFICATE,ISSUE_DATE,EXPIRES,EXPIRE_DATE,ISSUING_AUTHORITY,REMARK";
else
    $OUTPUT_HEAD = _("单位员工").","._("技能名称").","._("特种作业").","._("级别").","._("技能证").","._("发证日期").","._("有效期").","._("到期日期").","._("发证机关/单位").","._("备注");

ob_end_clean();
require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("劳动技能信息"));
$objExcel->addHead($OUTPUT_HEAD);


//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($HR_TABLE==1)
{
    $YEAR=date("Y",time());
    $START_TIME=$YEAR."-01-01";
    $END_TIME=$YEAR."-12-31";
    $CONDITION_STR.=" and ISSUE_DATE1 >= '$START_TIME' and ISSUE_DATE2 <= '$END_TIME'";
    $CONDITION_STR.=" and EXPIRE_DATE1 >= '$START_TIME' and EXPIRE_DATE2 <= '$END_TIME'";
}
else
{
    if($ABILITY_NAME!="")
        $CONDITION_STR.=" and ABILITY_NAME like '%".$ABILITY_NAME."%'";
    if($STAFF_NAME!="")
        $CONDITION_STR.=" and STAFF_NAME like '%".$STAFF_NAME."%'";
    if($ISSUING_AUTHORITY!="")
        $CONDITION_STR.=" and ISSUING_AUTHORITY like '%".$ISSUING_AUTHORITY."%'";
    if($ISSUE_DATE1!="")
        $CONDITION_STR.=" and ISSUE_DATE>='$ISSUE_DATE1'";
    if($ISSUE_DATE2!="")
        $CONDITION_STR.=" and ISSUE_DATE<='$ISSUE_DATE2'";
    if($EXPIRE_DATE1!="")
        $CONDITION_STR.=" and EXPIRE_DATE>='$EXPIRE_DATE1'";
    if($EXPIRE_DATE2!="")
        $CONDITION_STR.=" and EXPIRE_DATE<='$EXPIRE_DATE2'";
}
$CONDITION_STR = hr_priv("STAFF_NAME").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_LABOR_SKILLS where ".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$LEAVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $EXPERIENCE_COUNT++;
    $STAFF_NAME=format_cvs($ROW["STAFF_NAME"]);
    $ABILITY_NAME=format_cvs($ROW["ABILITY_NAME"]);
    $SPECIAL_WORK=format_cvs($ROW["SPECIAL_WORK"]);
    $SKILLS_LEVEL=format_cvs($ROW["SKILLS_LEVEL"]);
    $SKILLS_CERTIFICATE=format_cvs($ROW["SKILLS_CERTIFICATE"]);
    $ISSUE_DATE=format_cvs($ROW["ISSUE_DATE"]);
    $EXPIRE_DATE=format_cvs($ROW["EXPIRE_DATE"]);
    $EXPIRES=format_cvs($ROW["EXPIRES"]);
    $ISSUING_AUTHORITY=format_cvs($ROW["ISSUING_AUTHORITY"]);
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
    if($SPECIAL_WORK=="1")
    {
        $SPECIAL_WORK=_("是");
    }
    else
    {
        $SPECIAL_WORK=_("否");
    }
    if($SKILLS_CERTIFICATE=="1")
    {
        $SKILLS_CERTIFICATE=_("是");
    }
    else
    {
        $SKILLS_CERTIFICATE=_("否");
    }
    $OUTPUT = $STAFF_NAME1.",".$ABILITY_NAME.",".$SPECIAL_WORK.",".$SKILLS_LEVEL.",".$SKILLS_CERTIFICATE.",".$ISSUE_DATE.",".$EXPIRES.",".$EXPIRE_DATE.",".$ISSUING_AUTHORITY.",".$REMARK;
    $objExcel->addRow($OUTPUT);
}

$objExcel->Save();
?>
</body>

</html>
