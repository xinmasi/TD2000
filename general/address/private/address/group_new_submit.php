<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("新建分组");
include_once("inc/header.inc.php");

while(list($KEY, $VALUE) = each($_POST))
{
   $$KEY = trim($VALUE);
}
?>
<body class="bodycolor">
<?
//------------------锁定检测----------------------
if($GROUP_NAME==_("默认"))
{
	Message("",_("该组名已经存在！"));
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
        Message("",_("该组名已经存在！"));
        Button_Back();
        exit;
	}
}

//新增组名
$query="insert into ADDRESS_GROUP (GROUP_NAME,USER_ID) values ('$group_name','".$_SESSION["LOGIN_USER_ID"]."')";
exequery(TD::conn(),$query);

$GROUP_ID = mysql_insert_id();

//批量处理分组人员
$query="UPDATE address set GROUP_ID='$GROUP_ID' where  1=1 and find_in_set(ADD_ID,'$FLD_STR')";
exequery(TD::conn(), $query);

//新建分组不可以共享
/*
//设置共享信息
$s_cur_time = date("Y-m-d H:i:s",time());
if($share_start == "")
{
    $share_start = $s_cur_time;
}

//MANAGE_USER,ADD_SHARE_NAME字段去除掉
$query="UPDATE address SET SHARE_USER='$to_id',ADD_START='$share_start',ADD_END='$share_end' where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and find_in_set(ADD_ID,'$FLD_STR')";
exequery(TD::conn(), $query);

//与之前共享人员相连为新共享串
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

//发送共享提醒
$to_id = str_replace($_SESSION["LOGIN_USER_ID"].',', '', $to_id);
if($sms == "on")
{
    $s_remind_url = "1:address/private/index.php?FROM_FLAG=1";
    $s_sms_content = sprintf(_("%s共享了个人通讯簿中%s组的通讯录"),$_SESSION["LOGIN_USER_NAME"],$group_name);
    if($to_id != "")
    {
        send_sms($s_cur_time, $_SESSION["LOGIN_USER_ID"], $to_id, 20, $s_sms_content, $s_remind_url);
    }
}
Message('', '新建成功', '', array(array('value' => _("返回"), 'href' => 'show_group.php?GROUP_ID=<?=$GROUP_ID?>')));

*/

?>
<script>
    alert('<?=_("新建成功！")?>');
    parent.location.reload();
</script>
</body>
</html>
