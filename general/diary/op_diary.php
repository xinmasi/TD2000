<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("get_diary_data.func.php");
ob_end_clean();
$dia_id = $_GET['diary_id'];
$op=$_GET['op'];
$to_id=$_GET['value'];
$to_id = td_iconv($to_id, "utf-8", MYOA_CHARSET);
$dia_id = intval($dia_id);
if($op=="del") //删除日志
{
    $result = del_diary($dia_id);
}
if($op=="share") //设置共享
{
    $query  = "select * from diary where DIA_ID='$dia_id'";
    $cursor = exequery(TD::conn(), $query);
    if($ROW = mysql_fetch_array($cursor)){
        $subject    = $ROW["SUBJECT"];
        $to_id_old  = $ROW["TO_ID"];
    }
    $to_id_old_arr  = explode(",",$to_id_old);
    $to_id_new_arr  = explode(",",$to_id);
    $to_id_arr      = array_diff($to_id_new_arr,$to_id_old_arr);
    $to_id1         = implode(",",$to_id_arr);
    $s_remind_url   = "1:diary/show_diary.php?dia_id=".$dia_id."&FROM_FLAG=1";
    $s_sms_content  = _("请查看共享工作日志！")."\n"._("标题：").csubstr($subject,0,80);
    $s_cur_time     = date("Y-m-d H:i:s", time());

    $SYS_PARA_ARRAY = get_sys_para("SMS_REMIND");
    $PARA_VALUE     = $SYS_PARA_ARRAY["SMS_REMIND"];
    $SMS_REMIND     = substr($PARA_VALUE,0,strpos($PARA_VALUE,"|"));
    if($to_id1 != ""  && find_id($SMS_REMIND,"13"))
    {
        send_sms($s_cur_time, $_SESSION["LOGIN_USER_ID"], $to_id1, 13, $s_sms_content, $s_remind_url,$dia_id);
    }
    $result = set_share($dia_id,$to_id);
}
echo $result;
?>