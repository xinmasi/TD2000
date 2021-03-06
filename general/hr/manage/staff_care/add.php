<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("发布员工关怀");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------校验-------------------------------------
if($CARE_DATE!="" && !is_date($CARE_DATE))
{
    Message("",_("关怀日期应为日期型，如：1999-01-01"));
    Button_Back();
    exit;
}

//--------- 上传附件 ----------
$ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
$ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
if(count($_FILES)>1)
{
    $ATTACHMENTS=upload();
    $CARE_CONTENT=ReplaceImageSrc($CARE_CONTENT, $ATTACHMENTS);

    $ATTACHMENT_ID.=$ATTACHMENTS["ID"];
    $ATTACHMENT_NAME.=$ATTACHMENTS["NAME"];
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

$C = preg_match('/<img.*?\ssrc=\\\"\/inc\/attach.php\?(.*)MODULE=upload_temp/i',$CARE_CONTENT);
$CARE_CONTENT = replace_attach_url($CARE_CONTENT);
if($C==1)
{
    $ATTACHMENT_ID=move_attach($ATTACHMENT_ID,$ATTACHMENT_NAME,"","",true).",";
}

$STAFF_USERS=$BY_CARE_STAFFS;
//------------------- 发布员工关怀 -----------------------
$BY_CARE_STAFFS=td_trim($BY_CARE_STAFFS);
$BY_CARE_STAFFS_ARR=explode(',',$BY_CARE_STAFFS);
foreach($BY_CARE_STAFFS_ARR as $key=>$value)
{
    $BY_CARE_STAFFS=$value;
    $query="insert into HR_STAFF_CARE(CREATE_USER_ID,CREATE_DEPT_ID,BY_CARE_STAFFS,CARE_DATE,CARE_CONTENT,PARTICIPANTS,CARE_EFFECTS,CARE_FEES,CARE_TYPE,ATTACHMENT_ID,ATTACHMENT_NAME,ADD_TIME,LAST_UPDATE_TIME) values ('".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$BY_CARE_STAFFS','$CARE_DATE','$CARE_CONTENT','$PARTICIPANTS','$CARE_EFFECTS','$CARE_FEES','$CARE_TYPE','$ATTACHMENT_ID','$ATTACHMENT_NAME','$CUR_TIME','$CUR_TIME')";
    exequery(TD::conn(),$query);
    $SEND_TIME=$CARE_DATE.' 08:00:00';
    //------- 事务提醒 --------
    $CARE_ID = mysql_insert_id();
    $REMAND_USERS = $BY_CARE_STAFFS;
    if($SMS_REMIND=="on")
    {

        $REMIND_URL="ipanel/hr/care_detail.php?CARE_ID=".$CARE_ID;
        $SMS_CONTENT=_("请查看员工关怀！");
        send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,57,$SMS_CONTENT,$REMIND_URL,$CARE_ID);
    }

    if($SMS2_REMIND=="on")
    {
        $SMS_CONTENT=_("OA员工关怀:").csubstr($CARE_CONTENT,0,50);
        send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,57);
    }

    if($OP==0)
        header("location:index1.php");
    else
    {
        Message("",_("员工关怀新建成功！"));
        Button_Back();
    }
}


$PAR_CONTENT=_("您参与了").trim(getUserNameById($STAFF_USERS),",")._("的员工关怀");
$REMAND_USERS=$PARTICIPANTS;
if($SMS_REMIND=="on")
{
    send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,57,$PAR_CONTENT);
}

if($SMS2_REMIND=="on")
{
    $SMS_CONTENT=_("OA员工关怀:").$PAR_CONTENT;
    send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,57);
}
?>
</body>
</html>
