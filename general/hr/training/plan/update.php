<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if(count($_FILES)>1)
{
    $ATTACHMENTS=upload();

    $ATTACHMENT_ID=$ATTACHMENT_ID_OLD.$ATTACHMENTS["ID"];
    $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD.$ATTACHMENTS["NAME"];
}
else
{
    $ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
    $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------合法性校验-------------------------------------
if($COURSE_START_TIME!="" && !is_date_time($COURSE_START_TIME))
{
    Message("",_("开课时间应为日期时间型，如：1999-01-01 00:00:00"));
    Button_Back();
    exit;
}
if($COURSE_END_TIME!="" && !is_date_time($COURSE_END_TIME))
{
    Message("",_("结课时间应为日期时间型，如：1999-01-01 00:00:00"));
    Button_Back();
    exit;
}
//-- 保存 --
$query="UPDATE HR_TRAINING_PLAN
        set
        T_PLAN_NO='$T_PLAN_NO',
        T_PLAN_NAME='$T_PLAN_NAME',
        T_CHANNEL='$T_CHANNEL',
        T_BCWS='$T_BCWS',
        COURSE_START_TIME='$COURSE_START_TIME',
        COURSE_END_TIME='$COURSE_END_TIME',
        ASSESSING_OFFICER='$ASSESSING_OFFICER',
        T_JOIN_NUM='$T_JOIN_NUM',
        T_JOIN_DEPT='$T_JOIN_DEPT',
        T_JOIN_PERSON='$T_JOIN_PERSON',
        T_REQUIRES='$T_REQUIRES',
        T_INSTITUTION_NAME='$T_INSTITUTION_NAME',
        T_INSTITUTION_INFO='$T_INSTITUTION_INFO',
        T_INSTITUTION_CONTACT='$T_INSTITUTION_CONTACT',
        T_INSTITU_CONTACT_INFO='$T_INSTITU_CONTACT_INFO',
        T_COURSE_NAME='$T_COURSE_NAME',
        SPONSORING_DEPARTMENT='$SPONSORING_DEPARTMENT',
        CHARGE_PERSON='$CHARGE_PERSON',
        COURSE_HOURS='$COURSE_HOURS',
        COURSE_PAY='$COURSE_PAY',
        T_COURSE_TYPES='$T_COURSE_TYPES',
        T_DESCRIPTION='$T_DESCRIPTION',
        REMARK='$REMARK',
        T_ADDRESS='$T_ADDRESS',
        T_CONTENT='$T_CONTENT',
        ADD_TIME='$ADD_TIME',
        ATTACHMENT_ID='$ATTACHMENT_ID',
        ATTACHMENT_NAME='$ATTACHMENT_NAME',
        ADD_TIME='$CUR_TIME'
        WHERE T_PLAN_ID='$T_PLAN_ID'";
exequery(TD::conn(),$query);
if ($RESUBMIT==1)
{
    $query="UPDATE HR_TRAINING_PLAN set ASSESSING_STATUS='0' WHERE T_PLAN_ID='$T_PLAN_ID'";
    exequery(TD::conn(),$query);
}

$REMAND_USERS = $ASSESSING_OFFICER;
//------- 事务提醒 --------
if($ASSESSING_STATUS ==0 && $SMS_REMIND=="on")
{
    $REMIND_URL="hr/training/approval/index1.php?T_PLAN_ID=".$T_PLAN_ID;
    $SMS_CONTENT=sprintf(_("%s提交培训计划，请批示！"), $_SESSION["LOGIN_USER_NAME"]);
    send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,61,$SMS_CONTENT,$REMIND_URL,$T_PLAN_ID);
}

if($ASSESSING_STATUS ==0 && $SMS2_REMIND=="on")
{
    $SMS_CONTENT=sprintf(_("%s提交培训计划，请批示！"), $_SESSION["LOGIN_USER_NAME"]);
    send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,61);
}
if ($RESUBMIT==1)
    Message(_("提示"),sprintf(_("%s 的培训计划已重新提交审批。"), $T_PLAN_NAME));
else
    Message(_("提示"),sprintf(_("%s 的培训计划已保存。"), $T_PLAN_NAME));
header("location: index1.php?connstatus=1");
?>
</body>
</html>
