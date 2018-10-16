<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("新建证照信息");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------校验-------------------------------------
if($GET_LICENSE_DATE!="" && !is_date($GET_LICENSE_DATE))
{
    Message("",_("取证日期应为日期型，如：1999-01-01"));
    Button_Back();
    exit;
}
if($EFFECTIVE_DATE!="" && !is_date($EFFECTIVE_DATE))
{
    Message("",_("生效日期应为日期型，如：1999-01-01"));
    Button_Back();
    exit;
}
if($EXPIRE_DATE!="" && !is_date($EXPIRE_DATE))
{
    Message("",_("证书到期日期应为日期型，如：1999-01-01"));
    Button_Back();
    exit;
}

if($REMIND_TIME!="" && !is_date_time($REMIND_TIME))
{
    Message("",_("提醒时间应为时间型，如：1999-01-01 10:08:10"));
    Button_Back();
    exit;
}
//--------- 上传附件 ----------
if(count($_FILES)>1)
{
    $ATTACHMENTS=upload();

    $ATTACHMENT_ID=$ATTACHMENTS["ID"];
    $ATTACHMENT_NAME=$ATTACHMENTS["NAME"];
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

//------------------- 证照信息 -----------------------
if($EXPIRATION_PERIOD==_("是"))
    $EXPIRATION_PERIOD=1;
if($EXPIRATION_PERIOD==_("否"))
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
//------- 事务提醒 --------在定时任务中的事务提醒中处理
/*
$LICENSE_ID = mysql_insert_id();

if($REMIND_TIME!="" && $SMS_REMIND=="on")
{
	$REMIND_URL="ipanel/hr/license_detail.php?LICENSE_ID=".$LICENSE_ID;
  $SMS_CONTENT=sprintf(_("您的%s到期！"), $LICENSE_NAME);
  send_sms($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,59,$SMS_CONTENT,$REMIND_URL);
}

if($REMIND_TIME!="" && $SMS2_REMIND=="on")
{
   $SMS_CONTENT=sprintf(_("OA证照管理:%s到期！"), $LICENSE_NAME);
   send_mobile_sms_user($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,59);
}
*/
Message("",_("成功增加证照信息！"));

?>
<br><center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location.href='license_new.php'"></center>
</body>
</html>
