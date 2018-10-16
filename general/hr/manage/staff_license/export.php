<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");
include_once("inc/utility_field.php");


$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------合法性校验---------

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
//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($STAFF_NAME!="")
    $CONDITION_STR.=" and STAFF_NAME='$STAFF_NAME'";
if($LICENSE_TYPE!="")
    $CONDITION_STR.=" and LICENSE_TYPE='$LICENSE_TYPE'";
if($LICENSE_NO!="")
    $CONDITION_STR.=" and LICENSE_NO like '%".$LICENSE_NO."%'";
if($LICENSE_NAME!="")
    $CONDITION_STR.=" and LICENSE_NAME like '%".$LICENSE_NAME."%'";
if($STATUS!="")
    $CONDITION_STR.=" and STATUS='$STATUS'";
if($NOTIFIED_BODY!="")
    $CONDITION_STR.=" and NOTIFIED_BODY like '%".$NOTIFIED_BODY."%'";
if($EXPIRE_DATE1!="")
    $CONDITION_STR.=" and EXPIRE_DATE>='$EXPIRE_DATE1'";
if($EXPIRE_DATE2!="")
    $CONDITION_STR.=" and EXPIRE_DATE<='$EXPIRE_DATE2'";

ob_end_clean();

require_once ('inc/ExcelWriter.php');
$objExcel = new ExcelWriter();
$objExcel->setFileName(_("证照信息"));
if(MYOA_IS_UN == 1)
    $OUTPUT_HEAD = array('STAFF_NAME','LICENSE_TYPE','LICENSE_NO','LICENSE_NAME','STATUS','EFFECTIVE_DATE','EXPIRE_DATE ','NOTIFIED_BODY');
else
    $OUTPUT_HEAD = array(_("单位员工"),_("证照类型"),_("证照编号"),_("证照名称"),_("状态"),_("生效日期"),_("到期日期"),_("发证机构"));

$objExcel->addHead($OUTPUT_HEAD);
$CONDITION_STR = hr_priv("STAFF_NAME").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_LICENSE where".$CONDITION_STR."order by ADD_TIME desc";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $NAME=format_cvs(substr(GetUserNameById($ROW["STAFF_NAME"]),0,-1));
    $LICENSE_TYPE=format_cvs($ROW["LICENSE_TYPE"]);
    $LICENSE_NO=format_cvs($ROW["LICENSE_NO"]);
    $LICENSE_NAME=format_cvs($ROW["LICENSE_NAME"]);
    $STATUS=format_cvs($ROW["STATUS"]);
    $EXPIRE_DATE1=format_cvs($ROW["EFFECTIVE_DATE"]);
    $EXPIRE_DATE2=format_cvs($ROW["EXPIRE_DATE"]);
    $NOTIFIED_BODY=format_cvs($ROW["NOTIFIED_BODY"]);
    $LICENSE_TYPE=get_hrms_code_name($LICENSE_TYPE,"HR_STAFF_LICENSE1");
    $STATUS=get_hrms_code_name($STATUS,"HR_STAFF_LICENSE2");
    $OUT_PUT=$NAME.",".$LICENSE_TYPE.",".$LICENSE_NO.",".$LICENSE_NAME.",".$STATUS.",".$EXPIRE_DATE1.",".$EXPIRE_DATE2.",".$NOTIFIED_BODY;
    $objExcel->addRow("$OUT_PUT");
}
$objExcel->Save();
?>