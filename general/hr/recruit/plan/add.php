<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("新建招聘计划");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$query = "SELECT * from HR_RECRUIT_PLAN WHERE PLAN_NO='$PLAN_NO'";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if(mysql_fetch_array($cursor))
{
    Message(_("错误"),sprintf(_("已存在编号")));
    Button_Back();
    exit;
}


$CUR_TIME=date("Y-m-d H:i:s",time());
//--------- 上传附件 ----------
if(count($_FILES)>1)
{
    $ATTACHMENTS=upload();

    $ATTACHMENT_ID=$ATTACHMENTS["ID"];
    $ATTACHMENT_NAME=$ATTACHMENTS["NAME"];
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

$query="INSERT INTO HR_RECRUIT_PLAN(
    CREATE_USER_ID,
    CREATE_DEPT_ID,
    PLAN_NO,
    PLAN_NAME,
    PLAN_DITCH,
    PLAN_BCWS,
    PLAN_RECR_NO,
    REGISTER_TIME,
    START_DATE,
    END_DATE,
    RECRUIT_DIRECTION,
    RECRUIT_REMARK,
    APPROVE_PERSON,
    PLAN_STATUS,
    ATTACHMENT_ID,
    ATTACHMENT_NAME,
    ADD_TIME
    ) VALUES (
    '".$_SESSION["LOGIN_USER_ID"]."',
    '".$_SESSION["LOGIN_DEPT_ID"]."',
    '$PLAN_NO',
    '$PLAN_NAME',
    '$PLAN_DITCH',
    '$PLAN_BCWS',
    '$PLAN_RECR_NO',
    '$CUR_TIME',
    '$START_DATE',
    '$END_DATE',
    '$RECRUIT_DIRECTION',
    '$RECRUIT_REMARK',
    '$APPROVE_PERSON',
    '0',
    '$ATTACHMENT_ID',
    '$ATTACHMENT_NAME',
    '$CUR_TIME'
    )";
exequery(TD::conn(),$query);

//------- 事务提醒 --------
$REMAND_USERS = $APPROVE_PERSON;
if($PLAN_STATUS==0 && $SMS_REMIND=="on")
{
    $REMIND_URL="hr/recruit/plan_approval/index1.php?PLAN_ID=".$PLAN_ID;
    $SMS_CONTENT=$_SESSION["LOGIN_USER_NAME"]._("提交招聘计划，请批示！");
    send_sms($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,62,$SMS_CONTENT,$REMIND_URL,$PLAN_ID);
}

if($PLAN_STATUS==0 && $SMS2_REMIND=="on")
{
    $SMS_CONTENT=$_SESSION["LOGIN_USER_NAME"]._("提交招聘计划，请批示！");
    send_mobile_sms_user($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,62);
}

Message("",_("成功增加招聘计划信息！"));
?>
<br><center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location.href='new.php'"></center>
</body>
</html>
