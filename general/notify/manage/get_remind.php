<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
ob_end_clean();

$PARA_ARRAY=get_sys_para("NOTIFY_AUDITING_SINGLE,NOTIFY_AUDITING_SINGLE_NEW,NOTIFY_AUDITING_EXCEPTION");
$NOTIFY_AUDITING_SINGLE1=$PARA_ARRAY["NOTIFY_AUDITING_SINGLE"];//�Ƿ���Ҫ����
$NOTIFY_AUDITING_SINGLE_NEW=$PARA_ARRAY["NOTIFY_AUDITING_SINGLE_NEW"];
$NOTIFY_AUDITING_EXCEPTION=$PARA_ARRAY["NOTIFY_AUDITING_EXCEPTION"];

$NOTIFY_TYPE_ARRAY=explode(",", $NOTIFY_AUDITING_SINGLE_NEW);
for ($I=0;$I<count($NOTIFY_TYPE_ARRAY);$I++)
{
	$TYPE_ID_ARRAY=explode("-", $NOTIFY_TYPE_ARRAY[$I]);
	if ($TYPE_ID_ARRAY[0]!=0)
	    $TYPE_ID_STR.=$TYPE_ID_ARRAY[0].",";
}

$NOTIFY_TYPE_ID=$TYPE_ID."-1";
$NOTIFY_TYPE_ID_NO=$TYPE_ID."-0";

if (find_id($NOTIFY_AUDITING_SINGLE_NEW, $NOTIFY_TYPE_ID_NO) ||  find_id($NOTIFY_AUDITING_EXCEPTION,$_SESSION["LOGIN_USER_ID"]) || (find_id($NOTIFY_AUDITING_SINGLE_NEW, 'TYPE-0')&&!find_id($TYPE_ID_STR, $TYPE_ID)))
{
      echo sms_remind(1);
}

?>