<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("�½�֤����Ϣ");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------У��-------------------------------------
if($GET_LICENSE_DATE!="" && !is_date($GET_LICENSE_DATE))
{
    Message("",_("ȡ֤����ӦΪ�����ͣ��磺1999-01-01"));
    Button_Back();
    exit;
}
if($EFFECTIVE_DATE!="" && !is_date($EFFECTIVE_DATE))
{
    Message("",_("��Ч����ӦΪ�����ͣ��磺1999-01-01"));
    Button_Back();
    exit;
}
if($EXPIRE_DATE!="" && !is_date($EXPIRE_DATE))
{
    Message("",_("֤�鵽������ӦΪ�����ͣ��磺1999-01-01"));
    Button_Back();
    exit;
}

if($REMIND_TIME!="" && !is_date_time($REMIND_TIME))
{
    Message("",_("����ʱ��ӦΪʱ���ͣ��磺1999-01-01 10:08:10"));
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

//------------------- ֤����Ϣ -----------------------
if($EXPIRATION_PERIOD==_("��"))
    $EXPIRATION_PERIOD=1;
if($EXPIRATION_PERIOD==_("��"))
    $EXPIRATION_PERIOD=0;

$REMIND_TYPE=0;
if($SMS_REMIND=="on" && $SMS2_REMIND=="on")
    $REMIND_TYPE=3;
else if($SMS_REMIND=="on" && $SMS2_REMIND!="on")
    $REMIND_TYPE=1;
else if($SMS2_REMIND=="on" && $SMS_REMIND!="on")
    $REMIND_TYPE=2;

$query="insert into HR_STAFF_LICENSE (
  CREATE_USER_ID,
  CREATE_DEPT_ID,
  LICENSE_DEPT,
  STAFF_NAME,
  LICENSE_TYPE,
  LICENSE_NO,
  LICENSE_NAME,
  NOTIFIED_BODY,
  GET_LICENSE_DATE,
  EFFECTIVE_DATE,
  EXPIRATION_PERIOD,
  EXPIRE_DATE,
  STATUS,
  REMARK,
  REMIND_USER,
  ATTACHMENT_ID,
  ATTACHMENT_NAME,
  REMIND_TIME,
  REMIND_TYPE,
  ADD_TIME,
  LAST_UPDATE_TIME,
  HAS_REMINDED)
values
( '".$_SESSION["LOGIN_USER_ID"]."',
	'".$_SESSION["LOGIN_DEPT_ID"]."',
    '$LICENSE_DEPT',
	'$STAFF_NAME',
	'$LICENSE_TYPE',
	'$LICENSE_NO',
	'$LICENSE_NAME',
	'$NOTIFIED_BODY',
	'$GET_LICENSE_DATE',
	'$EFFECTIVE_DATE',
	'$EXPIRATION_PERIOD',
	'$EXPIRE_DATE',
	'$STATUS',
	'$REMARK',
	'$TO_ID',	
	'$ATTACHMENT_ID',
	'$ATTACHMENT_NAME',
	'$REMIND_TIME',
	'$REMIND_TYPE',
	'$CUR_TIME',
	'$CUR_TIME',
	'0')";
exequery(TD::conn(),$query);
$REMAND_USERS = $TO_ID.$STAFF_NAME;
//------- �������� --------�ڶ�ʱ�����е����������д���
/*
$LICENSE_ID = mysql_insert_id();

if($REMIND_TIME!="" && $SMS_REMIND=="on")
{
	$REMIND_URL="ipanel/hr/license_detail.php?LICENSE_ID=".$LICENSE_ID;
  $SMS_CONTENT=sprintf(_("����%s���ڣ�"), $LICENSE_NAME);
  send_sms($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,59,$SMS_CONTENT,$REMIND_URL);
}

if($REMIND_TIME!="" && $SMS2_REMIND=="on")
{
   $SMS_CONTENT=sprintf(_("OA֤�չ���:%s���ڣ�"), $LICENSE_NAME);
   send_mobile_sms_user($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,59);
}
*/
Message("",_("�ɹ�����֤����Ϣ��"));

?>
<br><center><input type="button" value="<?=_("����")?>" class="BigButton" onClick="location.href='license_new.php'"></center>
</body>
</html>
