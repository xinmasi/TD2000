<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_field.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("�½���ϵ��");
include_once("inc/header.inc.php");

$a_select = array();
$a_input = array();
$a_end_info = array();
while(list($KEY, $VALUE) = each($_POST))
{
    $$KEY = trim($VALUE);
    
/*    if(strpos($KEY,"input_name") !== false )
    {
        $a_input[] = array("num" => substr($KEY,10),"value" => $$KEY);
    }
    
    if(strpos($KEY,"qt_name") !== false)
    {
        $a_select[] = array("num" => substr($KEY,7),"value" => $$KEY);
    }*/
}

/*
if($sex == _("��"))
{
    $sex = 0;
}
else if($sex == _("Ů"))
{
    $sex = 1;
}
else
{
    $sex = "";
}

for($i = 0; $i < count($a_select); $i++)
{
    $$a_select[$i]['value'] = $a_input[$i]['value'];
}*/
if($notes == _("��ע��"))
{
    $notes = "";
}
?>

<body class="bodycolor">

<?
$s_cur_time = date("Y-m-d H:i:s",time());
//----------- �Ϸ���У�� ---------
if($birthday != "" && $birthday != "0000-00-00")
{
    $TIME_OK = is_date($birthday);
    
    if(!$TIME_OK)
    {
        Message(_("����"),_("��ʼ���ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
?>
        <br>
        <div align="center">
            <input type="button" value="<?=_("����")?>" class="BigButton" onClick="history.back();"> 
        </div>
<?
        exit;
    }
}
if($psn_name=='')
{
   Message(_("����"),_("��������Ϊ�գ�")); 
   ?>
        <br>
        <div align="center">
            <input type="button" value="<?=_("����")?>" class="BigButton" onClick="history.back();"> 
        </div>
<?
        exit;
}
//�ж��Ƿ�Ϊ��������������ϵ��
$s_user_id = "";
$s_user_id = (find_id($public_group_id_str,$group_id)) ? "" : $_SESSION["LOGIN_USER_ID"];

//--------- �ϴ�ͷ�񸽼� ----------
$ATTACHMENT = $_FILES['ATTACHMENT']['name'];
if($ATTACHMENT != "")
{
   $ATTACHMENTS = upload();
   $ATTACHMENT_ID = trim($ATTACHMENTS["ID"],",");
   $ATTACHMENT_NAME = trim($ATTACHMENTS["NAME"],"*");
}

//------------------- ���� -----------------------
//�����е��ֶΣ�sex,birthday,nick_name,add_dept,post_no_dept,fax_no_dept,add_home,tel_no_home,mobil_no,oicq_no,icq_no
//ȥ�����ֶΣ�MATE,CHILD,POST_NO_HOME,PSN_NO,BP_NO

$query="insert into ADDRESS(USER_ID,GROUP_ID,PSN_NAME,SEX,BIRTHDAY,NICK_NAME,MINISTRATION,DEPT_NAME,ADD_DEPT,POST_NO_DEPT,TEL_NO_DEPT,FAX_NO_DEPT,ADD_HOME,TEL_NO_HOME,MOBIL_NO,EMAIL,OICQ_NO,ICQ_NO,NOTES,ATTACHMENT_ID,ATTACHMENT_NAME,PER_WEB) ";
$query.=" values ('$s_user_id','$group_id','$psn_name','$sex','$birthday','$nick_name','$ministration','$dept_name','$add_dept','$post_no_dept','$tel_no_dept','$fax_no_dept','$add_home','$tel_no_home','$mobil_no','$email','$oicq_no','$icq_no','$notes','$ATTACHMENT_ID','$ATTACHMENT_NAME','$per_web')";
exequery(TD::conn(),$query);

$ADD_ID1 = mysql_insert_id();
save_field_data("ADDRESS",$ADD_ID1,$_POST);

//������(�����޹���˵��)
if($s_user_id != "")
{
    $s_cur_time = date("Y-m-d H:i:s",time());
    if($share_start=="")
    {
        $share_start = $s_cur_time;
    }
    
    $query = "UPDATE address SET SHARE_USER='$to_id',ADD_START='$share_start',ADD_END='$share_end' where ADD_ID='$ADD_ID1'";
    exequery(TD::conn(), $query);
    
    if($to_id != "")
    {
        $query="update ADDRESS_GROUP set SHARE_USER_ID='$to_id',SHARE_GROUP_ID='$group_id' where GROUP_ID='$group_id'";
        exequery(TD::conn(), $query);
    }
    
    //���͹�������
    $query = "SELECT GROUP_NAME from address_group where GROUP_ID='$group_id'";
    $cursor = exequery(TD::conn(), $query);
    if($row = mysql_fetch_array($cursor))
    {
        $group_name = $row[0];
    }
    
    $to_id = str_replace($_SESSION["LOGIN_USER_ID"].',', '', $to_id);  
    if($sms == "on")
    {
        //$s_remind_url = "1:address/private/index.php?FROM_FLAG=1";
        $s_remind_url = "1:address/private/index.php?";
        $s_sms_content = sprintf(_("%s�����˸���ͨѶ����%s���%s��ͨѶ¼"),$_SESSION["LOGIN_USER_NAME"],$group_name,$psn_name);
        if($to_id != "")
        {
            send_sms($s_cur_time, $_SESSION["LOGIN_USER_ID"], $to_id, 807, $s_sms_content, $s_remind_url);
        }
    }
}
?>
<script>
    parent.talklist.location.reload();
    parent.document.getElementById('hide_new').click();
    parent.document.getElementById('iframe_new').src = "address/new.php";
</script>

</body>
</html>
