<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_field.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("编辑联系人");

$a_select = array();
$a_input = array();
$a_end_info = array();
while(list($KEY, $VALUE) = each($_POST))
{
    $$KEY = trim($VALUE);
    /*
    if(strpos($KEY,"input_name") !== false )
    {
        $a_input[] = array("num" => substr($KEY,10),"value" => $$KEY);
    }
    
    if(strpos($KEY,"qt_name") !== false)
    {
        $a_select[] = array("num" => substr($KEY,7),"value" => $$KEY);
    }*/
}
/*
for($i = 0; $i < count($a_select); $i++)
{
    $$a_select[$i]['value'] = $a_input[$i]['value'];
}*/
if($notes == _("备注："))
{
    $notes = "";
}
?>

<body class="bodycolor">

<?
//----------- 合法性校验 ---------
if($birthday != "" && $birthday != "0000-00-00")
{
    $TIME_OK = is_date($birthday);
    
    if(!$TIME_OK)
    {
        Message(_("错误"),_("开始日期格式不对，应形如 1999-1-2"));
?>
        <br>
        <div align="center">
            <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="history.back();"> 
        </div>
<?
        exit;
    }
}

//判断是否为公共分组增加联系人
$s_user_id = "";
$s_user_id = (find_id($public_group_id_str,$group_id)) ? "" : $_SESSION["LOGIN_USER_ID"];

//--------- 上传头像附件 ----------
if($ATTACHMENT != "")
{
    if($attachment_id_old != "" && $attachment_name_old != "")
    {
        delete_attach($attachment_id_old, $attachment_name_old);
    }
    $ATTACHMENTS = upload();
    $ATTACHMENT_ID = trim($ATTACHMENTS["ID"],",");
    $ATTACHMENT_NAME = trim($ATTACHMENTS["NAME"],"*");
}
else
{
   $ATTACHMENT_ID = $attachment_id_old;
   $ATTACHMENT_NAME = $attachment_name_old;
}

//------------------- 保存 -----------------------
//隐藏中的字段：sex,birthday,nick_name,add_dept,post_no_dept,fax_no_dept,add_home,tel_no_home,mobil_no,bp_no,oicq_no,icq_no
//去掉的字段：MATE,CHILD,POST_NO_HOME,PSN_NO,BP_NO

$query ="UPDATE address SET USER_ID='$s_user_id',GROUP_ID='$group_id',PSN_NAME='$psn_name',SEX='$sex',BIRTHDAY='$birthday',NICK_NAME='$nick_name',";
$query.="MINISTRATION='$ministration',DEPT_NAME='$dept_name',ADD_DEPT='$add_dept',";
$query.="POST_NO_DEPT='$post_no_dept',TEL_NO_DEPT='$tel_no_dept',FAX_NO_DEPT='$fax_no_dept',ADD_HOME='$add_home',";
$query.="TEL_NO_HOME='$tel_no_home',MOBIL_NO='$mobil_no',EMAIL='$email',OICQ_NO='$oicq_no',ICQ_NO='$icq_no',NOTES='$notes',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME',PER_WEB='$per_web' ";
$query.="where ADD_ID=$add_id";

exequery(TD::conn(),$query);

save_field_data("ADDRESS",$add_id,$_POST);

//共享保存(公共分组没有共享)
if($s_user_id != "")
{
    $s_cur_time = date("Y-m-d H:i:s",time());
    if($share_start=="")
    {
        $share_start = $s_cur_time;
    }
    
    if($to_id != "")
    {
        $query = "UPDATE address SET SHARE_USER='$to_id',ADD_START='$share_start',ADD_END='$share_end' where ADD_ID='$add_id'";
        exequery(TD::conn(), $query);
        
        $query="update ADDRESS_GROUP set SHARE_USER_ID='$to_id',SHARE_GROUP_ID='$group_id' where GROUP_ID='$group_id'";
        exequery(TD::conn(), $query);
    }
    
    //发送共享提醒
    $query = "SELECT GROUP_NAME from address_group where GROUP_ID='$group_id'";
    $cursor = exequery(TD::conn(), $query);
    if($row = mysql_fetch_array($cursor))
    {
        $group_name = $row[0];
    }
    
    $to_id = str_replace($_SESSION["LOGIN_USER_ID"].',', '', $to_id);  
    if($sms == "on")
    {
        $s_remind_url = "1:address/private/index.php?FROM_FLAG=1";
        $s_sms_content = sprintf(_("%s共享了个人通讯簿中%s组的%s的通讯录"),$_SESSION["LOGIN_USER_NAME"],$group_name,$psn_name);
        if($to_id != "")
        {
            send_sms($s_cur_time, $_SESSION["LOGIN_USER_ID"], $to_id, 20, $s_sms_content, $s_remind_url);
        }
    }
}

$where_str2 = '';
if($where_str)
{
    $where_str2 = str_replace('``','&',$where_str);
    $where_str2 = str_replace('`','',$where_str2);
    $where_str2 = "&".$where_str2;
}
?>
<script>
    location="search_submit.php?start=<?=$start?><?=$where_str2?>";
</script>

</body>
</html>
