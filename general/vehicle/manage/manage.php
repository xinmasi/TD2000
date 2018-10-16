<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("车辆信息管理");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script>

jQuery(function(){
    jQuery("#allbox_for").click(function(){
        if(jQuery("#allbox_for").is(":checked"))
        {
            jQuery("[name='source_select']").attr("checked",'true');
        }
        else
        {
            jQuery("[name='source_select']").removeAttr("checked");
        }
    })
    
    jQuery("[name='source_select']").click(function(){
        jQuery("#allbox_for").removeAttr("checked");
    })
});

function delete_vehicle(V_ID)
{
    msg='<?=_("确认要删除该车辆吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete.php?V_ID=" + V_ID;
        window.location=URL;
    }
}

function my_manage_open(V_URL, V_ID, V_WINDTH, V_HEIGHT)
{
    myleft=(screen.availWidth-V_WINDTH)/2;
    mytop=(screen.availHeight-V_HEIGHT)/2;
    window.open(V_URL+"?V_ID="+V_ID,"note_win"+V_ID,"height="+V_HEIGHT+",width="+V_WINDTH+",status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,top="+mytop+",left="+myleft);
}

function get_checked()
{
    checked_str="";
    
    jQuery("input[name='source_select']:checkbox").each(function(){ 
        if(jQuery(this).attr("checked")){
            checked_str += jQuery(this).val()+","
        }
    })
    
    return checked_str;
}
function delete_item()
{
	delete_str=get_checked();
	if(delete_str=="")
	{
		alert("<?=_("要删除车辆，请至少选择其中一条。")?>");
		return;
	}
	msg='<?=_("确认要删除所选车辆吗？")?>';
	if(window.confirm(msg))
	{
		url="delete.php?V_ID="+ delete_str + '&delete_all=1';
    	location=url;
  	}
}
function export_item()
{
    export_str=get_checked();
    if(export_str=="")
    {
        alert("<?=_("要导出车辆信息，请至少选择其中一条。")?>");
        return;
    }
    msg='<?=_("确认要导出所选车辆的信息吗？")?>';
    if(window.confirm(msg))
    {
        url="export1.php?V_ID="+ export_str + '&export_all=1';
        location=url;
    }
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" HEIGHT="20"><span class="big3"> <?=_("车辆信息管理")?></span>
        </td>
    </tr>
</table>

<?
$s_curtime = date('Y-m-d',time());
//============================ 显示已发布公告 =======================================
$query = "SELECT * from VEHICLE order by V_STATUS,V_NUM";
$cursor= exequery(TD::conn(),$query);
$V_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $V_COUNT++;
    
    $V_ID           = $ROW["V_ID"];
    $V_MODEL        = $ROW["V_MODEL"];
    $V_NUM          = $ROW["V_NUM"];
    $V_DRIVER       = $ROW["V_DRIVER"];
    $V_TYPE         = $ROW["V_TYPE"];
    $V_DATE         = $ROW["V_DATE"];
    $V_PRICE        = $ROW["V_PRICE"];
    $V_ENGINE_NUM   = $ROW["V_ENGINE_NUM"];
    $V_STATUS       = $ROW["V_STATUS"];
    $V_MOT          = $ROW["V_MOT"];
    $V_INSURE       = $ROW["V_INSURE"];
    
    if($V_STATUS==0){
        $V_STATUS_DESC=_("可用");
    }
    elseif($V_STATUS==1)
    {
        $V_STATUS_DESC=_("损坏");
    }
    elseif($V_STATUS==2)
    {
        $V_STATUS_DESC=_("维修中");
    }
    elseif($V_STATUS==3)
    {
        $V_STATUS_DESC=_("报废");
    }

    if(td_trim($V_DRIVER) == ""){
        $V_DRIVER = '<font color=red>'._("暂无").'</font>';
    }else{
        $V_DRIVER = td_trim($V_DRIVER);
    }

    if($V_DATE=='0000-00-00' || $V_MOT=='')
    {
        $V_DATE='-';
    }

    if($V_MOT=='0000-00-00' || $V_MOT=='')
    {
        $V_MOT='-';
    }
    else if($s_curtime >= $V_MOT)
    {
        $V_MOT='<font color=red>'.$V_MOT.'</font>';
    }

    if($V_INSURE=='0000-00-00' || $V_INSURE=='')
    {
        $V_INSURE='-';
    }
    else if($s_curtime >= $V_INSURE)
    {
        $V_INSURE='<font color=red>'.$V_INSURE.'</font>';
    }
    
    $V_TYPE_DESC = get_code_name($V_TYPE,"VEHICLE_TYPE");
    
    if($V_COUNT%2 == 1)
    {
        $TableLine="TableLine1";
    }
    else
    {
        $TableLine="TableLine2";
    }
    
    if($V_COUNT==1)
    {
?>
<table class="TableList" width="95%" align="center">
    <tr class="TableHeader">
        <td nowrap align="center"><?=_("选择")?></td>
        <td nowrap align="center"><?=_("厂牌型号")?></td>
        <td nowrap align="center"><?=_("车牌号")?></td>
        <td nowrap align="center"><?=_("司机")?></td>
        <td nowrap align="center"><?=_("类型")?></td>
        <td nowrap align="center"><?=_("购置日期")?></td>
        <td nowrap align="center"><?=_("年检日期")?></td>
        <td nowrap align="center"><?=_("保险日期")?></td>
        <td nowrap align="center"><?=_("状态")?></td>
        <td nowrap align="center"><?=_("操作")?></td>
    </tr>
<?
    }
?>
    <tr class="<?=$TableLine?>">
        <td nowrap  width=40><input type="checkbox" name="source_select" id="source_select" value="<?=$V_ID?>"></td>
        <td nowrap align="center"><?=$V_MODEL?></td>
        <td nowrap align="center"><a href="javascript:;" onClick="my_manage_open('../vehicle_detail.php', '<?=$V_ID?>', '700', '500');"><?=$V_NUM?></a></td>
        <td nowrap align="center"><?=$V_DRIVER?></td>
        <td nowrap align="center"><?=$V_TYPE_DESC?></td>
        <td nowrap align="center"><?=$V_DATE?></td>
        <td nowrap align="center"><?=$V_MOT?></td>
        <td nowrap align="center"><?=$V_INSURE?></td>
<?
    if($V_STATUS==0)
    {
?>
        <td nowrap align="center"><a href="../new.php?V_NUM=<?=$V_ID?>"><?=$V_STATUS_DESC?></a></td>
<?
    }
    else
    {
?>
        <td nowrap align="center"><?=$V_STATUS_DESC?></td>
<?
    }
?>
        <td nowrap align="center">
            <a href="javascript:;" onClick="my_manage_open('../order_detail.php', '<?=$V_ID?>', '700', '400');"><?=_("预定情况")?></a>&nbsp;&nbsp;&nbsp;
            <a href="javascript:;" onClick="my_manage_open('maintenance.php', '<?=$V_ID?>', '700', '400');"><?=_("维护记录")?></a>&nbsp;&nbsp;&nbsp;
            <a href="javascript:;" onClick="my_manage_open('archives.php', '<?=$V_ID?>', '700', '250');"><?=_("车辆档案")?></a>&nbsp;&nbsp;&nbsp;
            <a href="new.php?V_ID=<?=$V_ID?>"><?=_("修改")?></a>&nbsp;&nbsp;&nbsp;
            <a href=javascript:delete_vehicle('<?=$V_ID?>');><?=_("删除")?></a>
        </td>
    </tr>

<?
}
if($V_COUNT>0)
{
    echo '<tr class="TableControl">
        <td colspan = "10"><input type="checkbox" name="allbox" id="allbox_for"><label for="allbox_for">全选</label> &nbsp;
        <a href="javascript:delete_item();" title=_("删除所选车辆")><img src="'.MYOA_STATIC_SERVER.'/static/images/delete.gif" align="absMiddle">删除所选车辆</a>&nbsp;<a href="javascript:export_item();">导出所选车辆信息</a></td>
        </tr></table>';
}
else
{
    Message("",_("暂无车辆信息"));
}
?>

</body>
</html>
