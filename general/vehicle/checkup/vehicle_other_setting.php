<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("其他设置");
include_once("inc/header.inc.php");

$is_hook = get_sys_para("VEHICLE_HOOKED");
if($is_hook == NULL)
{
    add_sys_para(array('VEHICLE_HOOKED' => '0'));
}

$is_hook = intval($is_hook['VEHICLE_HOOKED']);
?>
<body class="bodycolor">
	<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
		<tr>
			<td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" HEIGHT="20"><span class="big3"> <?=_("其他设置")?></span></td>
		</tr>
	</table>
	
	<form  action="vechicle_other_setting_submit.php" method="post" name="form1">
		<table class="TableBlock" width="60%" align="center" style="wideth:70%; margin-top:20px;">
			<tr >
				<td nowrap class="TableData" width="20%"><?=_("车辆业务流程平台引擎：")?></td>
				<td class="TableData">
					<input type="radio" name="VEHICLE_HOOKED" value="1" <?if($is_hook!='0') echo 'checked';?> > 启动
					<input type="radio" name="VEHICLE_HOOKED" value="0" <?if($is_hook=='0') echo 'checked';?> > 停用
				</td>
			</tr>
			<tr>
				<td nowrap class="TableData" colspan="2" style="text-align: center;"><input type="submit" value="保存" class="BigButtonA"></td>				
	    	</tr>
		</table>
	</form>

</body>
</html>