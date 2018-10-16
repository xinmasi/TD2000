<?
include_once("inc/auth.inc.php");
//2013-04-11 主服务器查询
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$concern_arr = array();

$all_array = array();
$sql="SELECT * FROM concern_user WHERE group_id='$group_id' AND user_id = '".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$sql);
if($arr = mysql_fetch_array($cursor))
{
	$all_array['CONCERN_ID']         = $arr['CONCERN_ID'];
	$all_array['USER_ID']            = $arr['USER_ID'];
	$all_array['GROUP_ID']           = $arr['GROUP_ID'];
	$all_array['CONCERN_USER']       = $arr['CONCERN_USER'];
	$all_array['CONCERN_CONTENT']    = $arr['CONCERN_CONTENT'];
	$all_array['CONCERN_BEGIN_TIME'] = $arr['CONCERN_BEGIN_TIME'];
	$all_array['CONCERN_END_TIME']   = $arr['CONCERN_END_TIME'];
	
	$concern_arr = array(
		'group_id' => $group_id,
		'concern_id' => $concern_id,
		'concern_user' => $concern_user,
		'concern_user_name' => GetUserNameById($concern_user),
	);
}

$HTML_PAGE_TITLE = ($concern_id!="" ? _("编辑人员关注") : _("新建人员关注"));
include_once("inc/header.inc.php");
?>

<script>
function CheckForm()
{
    if(document.form1.group_id.value=="")
    {
        alert("<?=_("组名不能为空！")?>");
        return false;
    }
        
    if(document.form1.concern_name.value=="")
    {
        alert("<?=_("关注人员不能为空！")?>");
        return false;
    }
    
    document.form1.submit();
}

function show_concern_user(val)
{
	var concern_arr = document.GetElmentById('concern_arr_val').value;
	
	document.getElementById('concern_user').value = a;
	document.getElementById('concern_user_name').value = b;
	document.getElementById('concern_id').value = c;
}

</script>

<?
if($concern_id != "")
{
    $query = "SELECT * FROM concern_user WHERE concern_id='$concern_id'";
    $cursor = exequery(TD::conn(), $query);
    if($row = mysql_fetch_array($cursor))
    {
        while(list($key, $value) = each($row))
            $$key = $value;
    }
    
    if($group_id != "")
    {
        $query = "SELECT group_name FROM concern_group WHERE group_id='$group_id'";
        $cursor = exequery(TD::conn(), $query);
        if($row = mysql_fetch_array($cursor))
        {
            $group_name = $row['group_name'];
        }
    }
}
?>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=$HTML_PAGE_TITLE?></span>
        </td>
    </tr>
</table>

<br>

<form action="group_insert.php" name="form1">
<table class="TableBlock" width="400" align="center">
    <tr>
        <td nowrap class="TableContent" width="80"><?=_("关注组名称：")?></td>
        <td class="TableData"><input type="text" name="group_name" size="30" class="BigInput" value="<?=$group_name?>">
        	<select onchang="show_concern_user(this.value)";>
            </select>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableContent" width="80"><?=_("关注人员：")?></td>
        <td class="TableData">
            <input type="hidden" id="concern_user" name="concern_user" value="<?=$concern_user?>">
            <textarea style="width:260px;" id="concern_user_name" name="concern_user_name" rows="2" style="overflow-y:auto;" class="SmallStatic" wrap="yes" readonly><?=$concern_user_name?></textarea>
            <a href="#" class="orgAdd" onClick="SelectUser('11','2','concern_user', 'concern_user_name')"><?=_("添加")?></a>
            <a href="#" class="orgClear" onClick="ClearUser('concern_user', 'concern_user_name')"><?=_("清空")?></a>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableControl" colspan="2" align="center">
            <input type="hidden" name="concern_id" size="10" class="BigInput" value="<?=$concern_id?>">
            <input type="hidden" id="concern_arr_val" name="concern_arr_val" size="10" class="BigInput" value="<?=$concern_arr?>">
            <input type="button" value="<?=_("提交")?>" class="BigButton" title="<?=_("提交数据")?>" OnClick="CheckForm()">&nbsp&nbsp&nbsp&nbsp
            <input type="button" value="<?=_("返回")?>" class="BigButton" title="<?=_("返回")?>" OnClick="history.back();">
        </td>
    </tr>
</table>
</form>

</body>
</html>