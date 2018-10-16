<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_org.php");
include_once("../get_diary_data.func.php");
$HTML_PAGE_TITLE = _("新建日志");
include_once("inc/header.inc.php");

$s_content = $_POST['CONTENT'];
?>
<body class="bodycolor">
<?
//------------------锁定检测----------------------
$s_cur_date = date("Y-m-d",time());
$s_cur_time = date("Y-m-d H:i:s",time());
if($dia_date == "")
{
    $dia_date = $s_cur_date;
}

$a_para_array = get_sys_para("LOCK_TIME,ALL_SHARE");
$s_lock_time = $a_para_array["LOCK_TIME"];
$s_all_share = $a_para_array["ALL_SHARE"];
if($s_lock_time != "")
{
    $a_lock_time = explode(",",$s_lock_time);
    $s_start = $a_lock_time[0];
    $s_end = $a_lock_time[1];
    $s_days = intval($a_lock_time[2]);
}

if($s_days != 0 && date("Y-m-d", strtotime("+".$s_days."day", strtotime($dia_date))) <= $s_cur_date)
{
    Message(_("提示"),_("所填的日志日期在锁定范围内！"));
    Button_Back();
    exit;
}
if($s_start == "" && $s_end != "" && compare_date($dia_date, $s_end) <= 0)
{
    Message(_("提示"),_("所填的日志日期在锁定范围内！"));
    Button_Back();
    exit;
}
else if($s_start != "" && $s_end == "" && compare_date($dia_date, $s_start) >= 0)
{
    Message(_("提示"),_("所填的日志日期在锁定范围内！"));
    Button_Back();
    exit;
}
else if($s_start != "" && $s_end != "" && (compare_date($dia_date, $s_end) <= 0 && compare_date($dia_date, $s_start) >= 0))
{
    Message(_("提示"),_("所填的日志日期在锁定范围内！"));
    Button_Back();
    exit;
}

if ($dia_date > $s_cur_date)
{
    Message(_("错误"),_("选择新建日志日期大于当前日期！"));
    Button_Back();
    exit;
}
//--------- 上传附件 ----------

if(count($_FILES) > 1)
{
    $a_attachments = upload();
    $s_content = ReplaceImageSrc($s_content, $a_attachments);
    $s_attachment_id = $attachment_id_old.$a_attachments["ID"];
    $s_attachment_name = $attachment_name_old.$a_attachments["NAME"];
}
else{
    $s_attachment_id = $attachment_id_old;
    $s_attachment_name = $attachment_name_old; 
}
$s_attachment_id .= copy_sel_attach($ATTACH_NAME, $ATTACH_DIR, $DISK_ID);
$s_attachment_name .= $ATTACH_NAME;

$s_content = str_replace("http://".$HTTP_HOST."/inc/attach.php?", "/inc/attach.php?", $s_content);
$s_content = str_replace("http://".$HTTP_HOST."/module/editor/plugins/smiley/images/", "/module/editor/plugins/smiley/images/", $s_content);
$C = preg_match('/<img.*?\ssrc=\\\"\/inc\/attach.php\?(.*)MODULE=upload_temp/i',$s_content);
$s_content = replace_attach_url($s_content);
if($C==1)
{
    $ATTACHMENT_ID=move_attach($ATTACHMENT_ID,$ATTACHMENT_NAME,"","",true).",";
}
$s_content = stripslashes($s_content);
//--------------------判断是否允许共享----------
if($share_type == 0){
    $to_id = "";
    $to_name = "";
    $SMS_REMIND = "";
    $SMS2_REMIND = "";
    $share_init = "";
	$SNS_REMIND = "";
}

//--------------------判断是否为个人日志----------
if($dia_type == "2")
{
    $to_id = "";
    $to_name = "";
    $SMS_REMIND = "";
    $SMS2_REMIND = "";
    $share_init = "";
}
//--------------------判断是否指定为默认共享范围----------
if($share_init == "1"){
    $query = "SELECT * FROM diary_share WHERE USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
    $cursor = exequery(TD::conn(), $query);
    if($ROW = mysql_fetch_array($cursor))
    {
        $query2 = "UPDATE diary_share SET SHARE_NAME='$to_id' WHERE USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
        exequery(TD::conn(), $query2);
    }
    else
    {
        $query2 = "INSERT into diary_share(USER_ID,SHARE_NAME) values ('".$_SESSION["LOGIN_USER_ID"]."','$to_id')";
        exequery(TD::conn(), $query2);
    }
}

//------------------- 保存 -----------------------
if($subject == "")
{
    $subject = $s_cur_date._(" 日志");
}
if($FROM == "calender")
{
    $query = "SELECT DIA_ID,COMPRESS_CONTENT FROM diary WHERE to_days(DIA_DATE)=to_days('$dia_date') and DIA_TYPE='$dia_type' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by DIA_TIME desc limit 0,1";
    $cursor = exequery(TD::conn(), $query);
    if($ROW = mysql_fetch_array($cursor))
    {
        $dia_id = $ROW["DIA_ID"];
        $s_content_old = @gzuncompress($ROW["COMPRESS_CONTENT"]);
        $s_content = $s_content_old."<br><br>".$subject."<br>".$s_content;
        $s_notags_content = addslashes(strip_tags($s_content));
        $s_compress_content = bin2hex(gzcompress($s_content));
        $query = "UPDATE diary SET CONTENT='$s_notags_content',COMPRESS_CONTENT=0x$s_compress_content WHERE DIA_ID='$dia_id'";
        exequery(TD::conn(), $query);
    }
    else
    {
        $s_notags_content = addslashes(strip_tags($s_content));
        $s_compress_content = bin2hex(gzcompress($s_content));
        $query="INSERT into diary(USER_ID,DIA_DATE,DIA_TIME,DIA_TYPE,SUBJECT,CONTENT,COMPRESS_CONTENT,ATTACHMENT_ID,ATTACHMENT_NAME,TO_ID) values ('".$_SESSION["LOGIN_USER_ID"]."','$dia_date','$s_cur_time','$dia_type','$subject','$s_notags_content',0x$s_compress_content,'$s_attachment_id','$s_attachment_name','$to_id')";
        exequery(TD::conn(), $query);
        cache_diary();
    }
}
else
{
    $s_notags_content = addslashes(strip_tags($s_content));
    $s_compress_content = bin2hex(gzcompress($s_content));
    $query = "INSERT into diary(USER_ID,DIA_DATE,DIA_TIME,DIA_TYPE,SUBJECT,CONTENT,COMPRESS_CONTENT,ATTACHMENT_ID,ATTACHMENT_NAME,TO_ID,TO_ALL) values ('".$_SESSION["LOGIN_USER_ID"]."','$dia_date','$s_cur_time','$dia_type','$subject','$s_notags_content',0x$s_compress_content,'$s_attachment_id','$s_attachment_name','$to_id','0')";
    exequery(TD::conn(), $query);
    $dia_id = mysql_insert_id(); 
    cache_diary(); 
}
$to_id = str_replace($_SESSION["LOGIN_USER_ID"].',', '', $to_id);
$subject = stripslashes($subject);
if($SMS_REMIND == "on")
{
    $s_remind_url = "1:diary/show_diary.php?dia_id=".$dia_id."&FROM_FLAG=1";
    $s_sms_content = _("请查看共享工作日志！")."\n"._("标题：").csubstr($subject, 0, 80);
    if($to_id != "")
    {
        mysql_set_charset(MYOA_DB_CHARSET, TD::conn());
        send_sms($s_cur_time, $_SESSION["LOGIN_USER_ID"], $to_id, 13, $s_sms_content, $s_remind_url,$dia_id);
    }
    //推送微信信息
    include_once("inc/itask/itask.php");
    /*$WX_OPTIONS = array(
       "module" => "news",
       "module_action" => "diary.read",
       "user" => $to_id,
       "content" => $_SESSION["LOGIN_USER_NAME"]._("：")._("请查看日志！")._("标题：").csubstr($subject,0,20),
       "params" => array(
            "dia_id" => $dia_id
        )
    );
    WXQY_DIARY($WX_OPTIONS);*/
  
}
if($SMS2_REMIND=="on")
{
    $s_sms_content = sprintf(_("工作日志,来自%s共享:$s"), $_SESSION["LOGIN_USER_NAME"],$subject);
    if($to_id != "")
    {
        send_mobile_sms_user($s_cur_time, $_SESSION["LOGIN_USER_ID"], $to_id, $s_sms_content, 13);
    }
    
}

if($SNS_REMIND=="on")
{
    $push_to_uid = ($dia_type=='2' ? "" : GetUidsByDiaryId($_SESSION['LOGIN_UID']));
    $push_to_id = $to_id.",".GetUserIDByUid($push_to_uid);
    $push_arr = array(
        'module' => 'diary',
        'mid' => $dia_id,
        'user_id' => $push_to_id,
        'priv_id' => '',
        'dept_id' => '',
        'subject' => $subject
    );
    push_to_wxshare($push_arr);
}

if($OP == "0")
{
    header("location: edit.php?dia_id=$dia_id&IS_MAIN=1");
}
else
{
    header("location: ../index.php?IS_MAIN=1");
}
?>

</body>
</html>
