<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");
include_once("sql_inc.php");

$SEND_TIME=date("Y-m-d H:i:s",time());


$query="SELECT SUBJECT,FROM_ID FROM NOTIFY WHERE NOTIFY_ID='$NOTIFY_ID'";
$cursor= exequery(TD::conn(),$query);
if ($ROW=mysql_fetch_array($cursor))
{
	$SUBJECT=$ROW["SUBJECT"];
	$FROM_ID=$ROW["FROM_ID"];	
}

$USER_NAME=td_trim(GetUserNameById($FROM_ID));



$un_userid_str = td_trim($un_userid_str);

$REMIND_URL="1:notify/show/read_notify.php?NOTIFY_ID=".$NOTIFY_ID;
if ($SMS_REMIND=="on")
{
	if ($un_userid_str!="")
	{
		 $SMS_CONTENT=_("请查看公告通知！")."\n"._("标题：").csubstr($SUBJECT,0,100);
		 send_sms("",$FROM_ID,$un_userid_str,1,$SMS_CONTENT,$REMIND_URL,$NOTIFY_ID);
	}
}

if ($SMS2_REMIND=="on")
{
	$SMS_CONTENT=sprintf(_("OA公告,来自%s"),$USER_NAME.":".$SUBJECT);
	   if($USER_ID_STR!="")  
	      send_mobile_sms_user("",$FROM_ID,$un_userid_str,$SMS_CONTENT,1);
}
Message(_(""), _("提醒信息已发送"));
Button_Back();


?>