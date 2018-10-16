<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("需求信息修改保存");
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
if($REQU_TIME!="" && !is_date($REQU_TIME))
{
    Message("",_("用工日期应为日期型，如：1999-01-01"));
    Button_Back();
    exit;
}

$query="UPDATE HR_RECRUIT_REQUIREMENTS
        SET
        REQU_NO='$REQU_NO',
        REQU_DEPT='$REQU_DEPT',
        REQU_JOB='$REQU_JOB',
        REQU_NUM='$REQU_NUM',
        REQU_REQUIRES='$REQU_REQUIRES',
        REQU_TIME='$REQU_TIME',
        REMARK='$REMARK',
        ATTACHMENT_ID='$ATTACHMENT_ID',
        ATTACHMENT_NAME='$ATTACHMENT_NAME',
        ADD_TIME='$CUR_TIME'
        WHERE REQUIREMENTS_ID = '$REQUIREMENTS_ID'";
exequery(TD::conn(),$query);

//------- 事务提醒 --------
$query="select * from HR_MANAGER where DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $DEPT_HR_MANAGER=$ROW["DEPT_HR_MANAGER"];
$TMP_ARRAY = explode(",",substr($DEPT_HR_MANAGER,0,-1));
for($I=0;$I< count($TMP_ARRAY);$I++)
{
    $REMAND_USERS.=$TMP_ARRAY[$I].',';
}

if($REMAND_USERS!="" && $SMS_REMIND=="on")
{
    $REMIND_URL="ipanel/hr/requirements_detail.php?REQUIREMENTS_ID=".$REQUIREMENTS_ID;
    $SMS_CONTENT=_("请查看招聘需求").$REQU_NO;
    send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,60,$SMS_CONTENT,$REMIND_URL,$REQUIREMENTS_ID);
}

if($REMAND_USERS!="" && $SMS2_REMIND=="on")
{
    $SMS_CONTENT=_("OA招聘需求:请查看招聘需求").$REQU_NO;
    send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,60);
}

header("location:index1.php?REQUIREMENTS_ID=$REQUIREMENTS_ID&connstatus=1")

?>
</body>
</html>
