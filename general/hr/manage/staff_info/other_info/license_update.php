<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("证照信息修改保存");
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
//------------------- 证照信息 -----------------------
if($EXPIRATION_PERIOD==_("是"))
    $EXPIRATION_PERIOD=1;
if($EXPIRATION_PERIOD==_("否"))
    $EXPIRATION_PERIOD=0;
//-----------------合法性校验-------------------------------------
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
$query="UPDATE HR_STAFF_LICENSE
        SET
        STAFF_NAME='$STAFF_NAME',
        LICENSE_TYPE='$LICENSE_TYPE',
        LICENSE_NO='$LICENSE_NO',
        LICENSE_NAME='$LICENSE_NAME',
        GET_LICENSE_DATE='$GET_LICENSE_DATE',
        EFFECTIVE_DATE='$EFFECTIVE_DATE',
        EXPIRATION_PERIOD='$EXPIRATION_PERIOD',
        NOTIFIED_BODY='$NOTIFIED_BODY',
        EXPIRE_DATE='$EXPIRE_DATE',
        STATUS='$STATUS',
        REMIND_TIME='$REMIND_TIME',
        REMARK='$REMARK',
        ATTACHMENT_ID='$ATTACHMENT_ID',
        ATTACHMENT_NAME='$ATTACHMENT_NAME',
        REMIND_USER='$TO_ID',
        LAST_UPDATE_TIME='$CUR_TIME'
        WHERE LICENSE_ID = '$LICENSE_ID'";
exequery(TD::conn(),$query);

$REMAND_USERS = $TO_ID.$STAFF_NAME;
//------- 事务提醒 --------
if($REMIND_TIME!="" && $SMS_REMIND=="on")
{
    $REMIND_URL="ipanel/hr/license_detail.php?LICENSE_ID=".$LICENSE_ID;
    $SMS_CONTENT=sprintf(_("您的%s到期！"), $LICENSE_NAME);
    send_sms($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,59,$SMS_CONTENT,$REMIND_URL,$LICENSE_ID);
}

if($REMIND_TIME!="" && $SMS2_REMIND=="on")
{
    $SMS_CONTENT=sprintf(_("OA证照管理:%s到期！"), $LICENSE_NAME);
    send_mobile_sms_user($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,59);
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
