<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("get_diary_data.func.php");
ob_end_clean();
mysql_set_charset(MYOA_DB_CHARSET, TD::conn());
$dia_id = $_POST['diary-id'];
$sms_remind = $_POST['SMS_REMIND'];
$sms2_remind = $_POST['SMS2_REMIND'];
$content = $_POST['feed-submit-cmt-context'];
$to_id = $_POST['comment-to'];
$comment_id = $_POST['comment-id'];
$type = $_POST['comment-type'];
$au_id = $_POST['au-id'];
$dia_id = intval($dia_id);
$to_id = td_iconv($to_id, "utf-8", MYOA_CHARSET);
$au_id = td_iconv($au_id, "utf-8", MYOA_CHARSET);
$content = td_iconv($content, "utf-8", MYOA_CHARSET);
if($op=="add")//тЖ╪сфюбш
{
    if($type!="sub")
    {
        $result=retJson(add_comment($dia_id,$sms_remind,$sms2_remind,$content));
    }
    else
    {
        $result=retJson(add_comment_reply($dia_id,$au_id,$comment_id,$to_id,$sms_remind,$sms2_remind,$content));	
    }
}
if($op=="del")
{
    $result=del_comment($type,$id);
}
echo $result;
?>