<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("����Ա���ػ�");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------У��-------------------------------------
if($CARE_DATE!="" && !is_date($CARE_DATE))
{
    Message("",_("�ػ�����ӦΪ�����ͣ��磺1999-01-01"));
    Button_Back();
    exit;
}

//--------- �ϴ����� ----------
if(count($_FILES)>1)
{
    $ATTACHMENTS=upload();
    $CARE_CONTENT=ReplaceImageSrc($CARE_CONTENT, $ATTACHMENTS);

    $ATTACHMENT_ID=$ATTACHMENTS["ID"];
    $ATTACHMENT_NAME=$ATTACHMENTS["NAME"];
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

//------------------- ����Ա���ػ� -----------------------
$query="insert into HR_STAFF_CARE(CREATE_USER_ID,CREATE_DEPT_ID,BY_CARE_STAFFS,CARE_DATE,CARE_CONTENT,PARTICIPANTS,CARE_EFFECTS,CARE_FEES,CARE_TYPE,ATTACHMENT_ID,ATTACHMENT_NAME,ADD_TIME,LAST_UPDATE_TIME) values ('".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$BY_CARE_STAFFS','$CARE_DATE','$CARE_CONTENT','$PARTICIPANTS','$CARE_EFFECTS','$CARE_FEES','$CARE_TYPE','$ATTACHMENT_ID','$ATTACHMENT_NAME','$CUR_TIME','$CUR_TIME')";
exequery(TD::conn(),$query);
$SEND_TIME=$CARE_DATE.' 08:00:00';
//------- �������� --------
$CARE_ID = mysql_insert_id();
$REMAND_USERS = $PARTICIPANTS.$BY_CARE_STAFFS;
if($SMS_REMIND=="on")
{

    $REMIND_URL="ipanel/hr/care_detail.php?CARE_ID=".$CARE_ID;
    $SMS_CONTENT=_("��鿴Ա���ػ���");
    send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,57,$SMS_CONTENT,$REMIND_URL,$CARE_ID);
}

if($SMS2_REMIND=="on")
{
    $SMS_CONTENT=_("OAԱ���ػ�:").csubstr($CARE_CONTENT,0,50);
    send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,57);
}

/*if($OP==0)
  header("location:index1.php");
else
{*/
Message("",_("Ա���ػ��½��ɹ���"));

//}
?>
<br><center><input type="button" value="<?=_("����")?>" class="BigButton" onClick="location.href='care_new.php'"></center>
</body>
</html>
