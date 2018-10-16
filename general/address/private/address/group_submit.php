<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("编辑分组");
include_once("inc/header.inc.php");

while(list($KEY, $VALUE) = each($_POST))
{
    $$KEY = trim($VALUE);
}
?>
<body class="bodycolor">
<?
//------------------锁定检测----------------------
if($GROUP_NAME==_("默认") && $GROUP_ID != '0')
{
    Message("",_("该组名已经存在！"));
    Button_Back();
    exit;
}

$query = "select * from ADDRESS_GROUP where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and GROUP_ID!='$GROUP_ID'";
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

//修改组名
$query = "update ADDRESS_GROUP set GROUP_NAME='$group_name' where GROUP_ID='$GROUP_ID'";
exequery(TD::conn(),$query);

//批量处理分组人员
$query="UPDATE address set GROUP_ID='$GROUP_ID' where  1=1 and find_in_set(ADD_ID,'$FLD_STR')";
exequery(TD::conn(), $query);

//删除本组中成员直接掉到默认组中
$query = "select ADD_ID from address where GROUP_ID='$GROUP_ID' and !find_in_set(ADD_ID,'$FLD_STR')";
$cursor = exequery(TD::conn(), $query);
if($row = mysql_fetch_array($cursor))
{
    $ADD_ID = $row[0];
    $query="UPDATE address SET GROUP_ID='0' where ADD_ID='$ADD_ID'";
    exequery(TD::conn(), $query);
}

//设置共享信息
$s_cur_time = date("Y-m-d H:i:s",time());
if($share_start == "")
{
    $share_start = $s_cur_time;
}

//MANAGE_USER,ADD_SHARE_NAME字段去除掉
if($to_id != "")
{
    $query="UPDATE address SET SHARE_USER='$to_id',ADD_START='$share_start',ADD_END='$share_end' where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and find_in_set(ADD_ID,'$add_id_str')";
    exequery(TD::conn(), $query);
}

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
if($s_new_str=="")
{
    $query="UPDATE address SET SHARE_USER='',ADD_START='',ADD_END='' where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and GROUP_ID = '$GROUP_ID'";
    exequery(TD::conn(), $query);
}
$query="update ADDRESS_GROUP set SHARE_USER_ID='$s_new_str',SHARE_GROUP_ID='$GROUP_ID' where GROUP_ID='$GROUP_ID'";
exequery(TD::conn(), $query);

//发送共享提醒
$to_id = str_replace($_SESSION["LOGIN_USER_ID"].',', '', $to_id);
if($sms == "on")
{
    //$s_remind_url = "1:address/private/index.php?FROM_FLAG=1";
    $s_remind_url = "1:address/private/index.php?";
    $s_sms_content = sprintf(_("%s共享了个人通讯簿中%s组的通讯录"),$_SESSION["LOGIN_USER_NAME"],$group_name);
    if($to_id != "")
    {
        send_sms($s_cur_time, $_SESSION["LOGIN_USER_ID"], $to_id, 807, $s_sms_content, $s_remind_url);
    }
}

?>

<script>
    alert('<?=_("修改成功！")?>');
    parent.parent.location.reload();
</script>
</body>
</html>
