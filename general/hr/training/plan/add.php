<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("�½���ѵ�ƻ�");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------У��-------------------------------------
if($COURSE_START_TIME!="" && !is_date_time($COURSE_START_TIME))
{
    Message("",_("����ʱ��ӦΪ����ʱ���ͣ��磺1999-01-01 00:00:00"));
    Button_Back();
    exit;
}
if($COURSE_END_TIME!="" && !is_date_time($COURSE_END_TIME))
{
    Message("",_("���ʱ��ӦΪ����ʱ���ͣ��磺1999-01-01 00:00:00"));
    Button_Back();
    exit;
}
//--------- �ϴ����� ----------
if(count($_FILES)>1)
{
    $ATTACHMENTS=upload();

    $ATTACHMENT_ID=$ATTACHMENTS["ID"];
    $ATTACHMENT_NAME=$ATTACHMENTS["NAME"];
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;
//------------------- ������ѵ�ƻ���Ϣ -----------------------

$query="INSERT INTO hr_training_plan(
    CREATE_USER_ID ,
    CREATE_DEPT_ID,
    T_PLAN_NO,
    T_PLAN_NAME,
    T_CHANNEL,
    T_BCWS,
    COURSE_START_TIME,
    COURSE_END_TIME,
    ASSESSING_OFFICER,
    T_JOIN_NUM,
    T_JOIN_DEPT,
    T_JOIN_PERSON,
    T_REQUIRES,
    T_INSTITUTION_NAME,
    T_INSTITUTION_INFO,
    T_INSTITUTION_CONTACT,
    T_INSTITU_CONTACT_INFO,
    T_COURSE_NAME ,
    SPONSORING_DEPARTMENT,
    CHARGE_PERSON,
    COURSE_HOURS ,
    T_COURSE_TYPES,
    T_DESCRIPTION ,
    T_ADDRESS,
    T_CONTENT,
    REMARK,
    ATTACHMENT_ID,
    ATTACHMENT_NAME,
    ADD_TIME
    ) VALUES (
    '".$_SESSION["LOGIN_USER_ID"]."',
    '".$_SESSION["LOGIN_DEPT_ID"]."',
    '$T_PLAN_NO',
    '$T_PLAN_NAME',
    '$T_CHANNEL',
    '$T_BCWS',
    '$COURSE_START_TIME',
    '$COURSE_END_TIME',
    '$ASSESSING_OFFICER',
    '$T_JOIN_NUM',
    '$T_JOIN_DEPT',
    '$T_JOIN_PERSON',
    '$T_REQUIRES',
    '$T_INSTITUTION_NAME',
    '$T_INSTITUTION_INFO',
    '$T_INSTITUTION_CONTACT',
    '$T_INSTITU_CONTACT_INFO',
    '$T_COURSE_NAME',
    '$SPONSORING_DEPARTMENT',
    '$CHARGE_PERSON',
    '$COURSE_HOURS',
    '$T_COURSE_TYPES',
    '$T_DESCRIPTION',
    '$T_ADDRESS',
    '$T_CONTENT',
    '$REMARK',
    '$ATTACHMENT_ID',
    '$ATTACHMENT_NAME',
    '$CUR_TIME'
    )";
exequery(TD::conn(),$query);

$REMAND_USERS = $ASSESSING_OFFICER;
//------- �������� --------
if($ASSESSING_STATUS ==0 && $SMS_REMIND=="on")
{
    $REMIND_URL="hr/training/approval/index1.php?T_PLAN_ID=".$T_PLAN_ID;
    $SMS_CONTENT=$_SESSION["LOGIN_USER_NAME"]._("�ύ��ѵ�ƻ�������ʾ��");
    send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,61,$SMS_CONTENT,$REMIND_URL,$T_PLAN_ID);
}

if($ASSESSING_STATUS ==0 && $SMS2_REMIND=="on")
{
    $SMS_CONTENT=$_SESSION["LOGIN_USER_NAME"]._("�ύ��ѵ�ƻ�������ʾ��");
    send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,61);
}
Message("",_("��ѵ�ƻ��½��ɹ���"));

?>
<br><center><input type="button" value="<?=_("����")?>" class="BigButton" onClick="location.href='new.php'"></center>
</body>
</html>
