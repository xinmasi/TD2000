<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("get_diary_data.func.php");
ob_end_clean();
ob_start();
$DIA_ID = intval($DIA_ID);
sms_remind(13);
$para_array=get_sys_para("LOCK_TIME");
$sms_op=ob_get_contents(); 
$sms_op=str_replace("id=\"SMS_REMIND\"","id=\"SMS_REMIND_".$DIA_ID."\"",$sms_op);
$sms_op=str_replace("for=\"SMS_REMIND\"","for=\"SMS_REMIND_".$DIA_ID."\"",$sms_op);
$sms_op=str_replace("id=\"SMS2_REMIND\"","id=\"SMS2_REMIND_".$DIA_ID."\"",$sms_op);
$sms_op=str_replace("for=\"SMS2_REMIND\"","for=\"SMS2_REMIND_".$DIA_ID."\"",$sms_op);
$diary_array=array();
if(isset($DIA_ID)&& $DIA_ID!="")
{
	$diary_array=array("sms_op"=> $sms_op,"comment_data"=>get_diary_commentdate($DIA_ID,$para_array,1,$db));
}
echo retJson($diary_array);
?>