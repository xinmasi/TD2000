<?
$HTML_PAGE_TITLE = _("记录共享");
include_once("general/crm/inc/header.php");
include_once('inc/utility_org.php');
include_once("general/crm/utils/edview/edview.interface.php");
include_once(CRM_CONTEXT_PATH_REL."/utils/priv/op.priv.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script>
	function validate(){
		if(document.getElementById('TO_ID').value=="" && document.getElementById('COPY_TO_ID').value=="" && document.getElementById('PRIV_ID').value==""){
			alert('<?=_("请至少填写一个值")?>');
			return false;
		}
		document.form1.submit();
	}
</script>
<iframe name="FORMSUBMIT" width="0" height="0" ></iframe>
<form target="FORMSUBMIT" name="form1" method="post" action="update.php">

<?
	checkOpPriv($MODULE, "010");

	printShareField($to_id, $to_name, $copy_to_id, $user_name, $priv_id, $priv_name);
?>
<table width="100%">
<tr>
<td align='center'>
<input type="hidden" name="ids" value="<?=$ids?>">
<input type="hidden" name="MODULE" value="<?=$MODULE?>">
<input type="button" value=" <?=_("保存")?>" class="crm_SmallButton" onclick='validate()'/>
<input type="button" value=" <?=_("关闭")?>" class="crm_SmallButton" onClick="window.close()"/>
</td>
</tr>
</table>
</form>