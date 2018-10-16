<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("奖惩信息修改保存");
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
if($INCENTIVE_TIME!="" && !is_date($INCENTIVE_TIME))
{
    Message("",_("奖惩日期应为日期型，如：1999-01-01"));
    Button_Back();
    exit;
}

$query="UPDATE HR_STAFF_INCENTIVE
		              SET
			STAFF_NAME='$STAFF_NAME',
			INCENTIVE_TIME='$INCENTIVE_TIME',
			INCENTIVE_ITEM='$INCENTIVE_ITEM',
			INCENTIVE_TYPE='$INCENTIVE_TYPE',
			SALARY_MONTH='$SALARY_MONTH',
			INCENTIVE_AMOUNT='$INCENTIVE_AMOUNT',
			INCENTIVE_DESCRIPTION='$INCENTIVE_DESCRIPTION',
			REMARK='$REMARK',
			ATTACHMENT_ID='$ATTACHMENT_ID',
			ATTACHMENT_NAME='$ATTACHMENT_NAME',
			LAST_UPDATE_TIME='$CUR_TIME'
		  WHERE INCENTIVE_ID = '$INCENTIVE_ID'";
exequery(TD::conn(),$query);

$REMAND_USERS = $STAFF_NAME;
$INCENTIVE_TYPE=get_hrms_code_name($INCENTIVE_TYPE,"INCENTIVE_TYPE");

//------- 事务提醒 --------
if($SMS_REMIND=="on")
{
    $REMIND_URL="ipanel/hr/incentive_detail.php?INCENTIVE_ID=".$INCENTIVE_ID;
    $SMS_CONTENT=sprintf(_("请查看%s信息！"), $INCENTIVE_TYPE);
    send_sms($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,58,$SMS_CONTENT,$REMIND_URL,$INCENTIVE_ID);
}

if($SMS2_REMIND=="on")
{
    $REMAND_USERS_NAME = GetUserNameById($REMAND_USERS);
    $SMS_CONTENT=_("OA奖惩管理:").$INCENTIVE_TYPE.$REMAND_USERS_NAME.$INCENTIVE_AMOUNT;
    send_mobile_sms_user($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,58);
}

Message("",_("修改成功！"));
parse_str($_SERVER["HTTP_REFERER"], $tmp_url);
$paras = strpos($_SERVER["HTTP_REFERER"], "?") ? isset($tmp_url["connstatus"]) ? $_SERVER["HTTP_REFERER"] : $_SERVER["HTTP_REFERER"]."&connstatus=1" : $paras = $_SERVER["HTTP_REFERER"]."?connstatus=1";
?>
<center>
    <input type="button" class="BigButton" value="<?=_("返回")?>" onclick="window.location.href='<?=$paras?>'"/>
</center>
</body>
</html>
