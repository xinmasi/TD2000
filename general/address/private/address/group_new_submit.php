<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("�½�����");
include_once("inc/header.inc.php");

while(list($KEY, $VALUE) = each($_POST))
{
   $$KEY = trim($VALUE);
}
?>
<body class="bodycolor">
<?
//------------------�������----------------------
if($GROUP_NAME==_("Ĭ��"))
{
	Message("",_("�������Ѿ����ڣ�"));
	Button_Back();
	exit;
}

$query = "select * from ADDRESS_GROUP where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor = exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	$GROUP_NAME1 = $ROW["GROUP_NAME"];
	
	if($GROUP_NAME1 == $GROUP_NAME)
	{
        Message("",_("�������Ѿ����ڣ�"));
        Button_Back();
        exit;
	}
}

//��������
$query="insert into ADDRESS_GROUP (GROUP_NAME,USER_ID) values ('$group_name','".$_SESSION["LOGIN_USER_ID"]."')";
exequery(TD::conn(),$query);

$GROUP_ID = mysql_insert_id();

//�������������Ա
$query="UPDATE address set GROUP_ID='$GROUP_ID' where  1=1 and find_in_set(ADD_ID,'$FLD_STR')";
exequery(TD::conn(), $query);

//�½����鲻���Թ���
/*
//���ù�����Ϣ
$s_cur_time = date("Y-m-d H:i:s",time());
if($share_start == "")
{
    $share_start = $s_cur_time;
}

//MANAGE_USER,ADD_SHARE_NAME�ֶ�ȥ����
$query="UPDATE address SET SHARE_USER='$to_id',ADD_START='$share_start',ADD_END='$share_end' where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and find_in_set(ADD_ID,'$FLD_STR')";
exequery(TD::conn(), $query);

//��֮ǰ������Ա����Ϊ�¹���
$s_new_str = '';
$a_to_id = array();
if($to_id != '')
{
    $query = "SELECT SHARE_USER_ID FROM address_group WHERE GROUP_ID='$GROUP_ID'";
    $cursor = exequery(TD::conn(), $query);
    if($row = mysql_fetch_array($cursor))
    {
        $s_new_str = $row[0];
        
        if($s_new_str != '')
        {
            $s_new_str .= $to_id;
            $a_to_id = explode(",",$s_new_str);
            $a_to_id = array_unique($a_to_id);
            $s_new_str = implode(",",$a_to_id);
        }
        else
        {
            $s_new_str = $to_id;
        }
    }
}
$query="update ADDRESS_GROUP set SHARE_USER_ID='$s_new_str',SHARE_GROUP_ID='$GROUP_ID' where GROUP_ID='$GROUP_ID'";
exequery(TD::conn(), $query);

//���͹�������
$to_id = str_replace($_SESSION["LOGIN_USER_ID"].',', '', $to_id);
if($sms == "on")
{
    $s_remind_url = "1:address/private/index.php?FROM_FLAG=1";
    $s_sms_content = sprintf(_("%s�����˸���ͨѶ����%s���ͨѶ¼"),$_SESSION["LOGIN_USER_NAME"],$group_name);
    if($to_id != "")
    {
        send_sms($s_cur_time, $_SESSION["LOGIN_USER_ID"], $to_id, 20, $s_sms_content, $s_remind_url);
    }
}
Message('', '�½��ɹ�', '', array(array('value' => _("����"), 'href' => 'show_group.php?GROUP_ID=<?=$GROUP_ID?>')));

*/

?>
<script>
    alert('<?=_("�½��ɹ���")?>');
    parent.location.reload();
</script>
</body>
</html>
