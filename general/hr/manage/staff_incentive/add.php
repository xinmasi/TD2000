<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("新建奖惩信息");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------校验-------------------------------------
if($INCENTIVE_TIME!="" && !is_date($INCENTIVE_TIME))
{
   Message("",_("奖惩日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}
//--------- 上传附件 ----------
$ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
$ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
if(count($_FILES)>1)
{
   $ATTACHMENTS=upload();

   $ATTACHMENT_ID.=$ATTACHMENTS["ID"];
   $ATTACHMENT_NAME.=$ATTACHMENTS["NAME"];
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;
$C = preg_match('/<img.*?\ssrc=\\\"\/inc\/attach.php\?(.*)MODULE=upload_temp/i',$INCENTIVE_DESCRIPTION);
$INCENTIVE_DESCRIPTION = replace_attach_url($INCENTIVE_DESCRIPTION);
if($C==1)
{
    $ATTACHMENT_ID=move_attach($ATTACHMENT_ID,$ATTACHMENT_NAME,"","",true).",";
}

//保存
$j=substr_count("$STAFF_NAME",",");
$STAFF_NAME1_PIECES= explode(",",$STAFF_NAME);
for($i=0;$i<$j;$i++)
{
   $INCENTIVE_TYPE_ARRAY[]= $INCENTIVE_TYPE;
}
for($i=0;$i<$j;$i++)
{
 $STAFF_NAME1_EACH=$STAFF_NAME1_PIECES[$i];
 $INCENTIVE_TYPE = $INCENTIVE_TYPE_ARRAY[$i];
 $query="insert into HR_STAFF_INCENTIVE (
   CREATE_USER_ID,
   CREATE_DEPT_ID,
   STAFF_NAME,
   INCENTIVE_TIME,
   INCENTIVE_ITEM,
   INCENTIVE_TYPE,
   SALARY_MONTH,
   INCENTIVE_AMOUNT,
   INCENTIVE_DESCRIPTION,
   REMARK,
   ATTACHMENT_ID,
   ATTACHMENT_NAME,
   ADD_TIME,
   LAST_UPDATE_TIME)
 values
 ( '".$_SESSION["LOGIN_USER_ID"]."',
 	'".$_SESSION["LOGIN_DEPT_ID"]."',
 	'$STAFF_NAME1_EACH',
 	'$INCENTIVE_TIME',
 	'$INCENTIVE_ITEM',
 	'$INCENTIVE_TYPE',
 	'$SALARY_MONTH',
 	'$INCENTIVE_AMOUNT',
 	'$INCENTIVE_DESCRIPTION',
 	'$REMARK',
 	'$ATTACHMENT_ID',
 	'$ATTACHMENT_NAME',
 	'$CUR_TIME',
 	'$CUR_TIME')";
 exequery(TD::conn(),$query);

$INCENTIVE_ID = mysql_insert_id();

if($SMS_REMIND=="on")
{
  $INCENTIVE_TYPE=get_hrms_code_name($INCENTIVE_TYPE,"INCENTIVE_TYPE");
  $REMIND_URL="ipanel/hr/incentive_detail.php?INCENTIVE_ID=".$INCENTIVE_ID;
  $SMS_CONTENT=sprintf(_("请查看%s信息！"), $INCENTIVE_TYPE);
  send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$STAFF_NAME1_EACH,58,$SMS_CONTENT,$REMIND_URL,$INCENTIVE_ID);

}
 
}
$REMAND_USERS = $STAFF_NAME;
$INCENTIVE_TYPE=get_hrms_code_name($INCENTIVE_TYPE,"INCENTIVE_TYPE");

//------- 事务提醒 --------


if($SMS2_REMIND=="on")
{
	$REMAND_USERS_NAME = GetUserNameById($REMAND_USERS);
	$SMS_CONTENT=_("OA奖惩管理:").$INCENTIVE_TYPE.$REMAND_USERS_NAME.$INCENTIVE_AMOUNT;
	send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,58);
}

Message("",sprintf(_("成功录入%s信息！"), $INCENTIVE_TYPE));
?>
<br><center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location.href='new.php'"></center>
</body>
</html>
