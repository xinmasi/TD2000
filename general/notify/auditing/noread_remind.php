<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");

$SEND_TIME=date("Y-m-d H:i:s",time());


$query="SELECT SUBJECT,FROM_ID FROM NOTIFY WHERE NOTIFY_ID='$NOTIFY_ID'";
$cursor= exequery(TD::conn(),$query);
if ($ROW=mysql_fetch_array($cursor))
{
    $SUBJECT=$ROW["SUBJECT"];
    $FROM_ID=$ROW["FROM_ID"];
}

$USER_NAME=td_trim(GetUserNameById($FROM_ID));

$REMIND_URL="1:notify/show/read_notify.php?NOTIFY_ID=".$NOTIFY_ID;
if ($SMS_REMIND=="on")
{
    if ($USER_ID_STR!="")
    {
        $SMS_CONTENT=_("��鿴����֪ͨ��")."\n"._("���⣺").csubstr($SUBJECT,0,100);
        send_sms($SEND_TIME,$FROM_ID,$USER_ID_STR,1,$SMS_CONTENT,$REMIND_URL,$NOTIFY_ID);
    }
}

if ($SMS2_REMIND=="on")
{
    $SMS_CONTENT=sprintf(_("OA����,����%s"),$USER_NAME.":".$SUBJECT);
    if($USER_ID_STR!="")
        send_mobile_sms_user($SEND_TIME,$FROM_ID,$USER_ID_STR,$SMS_CONTENT,1);
}
Message(_(""), _("������Ϣ�ѷ���"));
Button_Back();


?>