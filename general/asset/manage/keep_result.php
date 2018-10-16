<?
include_once("inc/auth.inc.php");
if(isset($SAVE))
{
	$query="update CP_ASSET_KEEP set KEEP_RESULT='$KEEP_RESULTS' where KEEP_ID='$KEEP_ID'";	
	exequery(TD::conn(),$query);
	Message("",_("维修结果修改成功"));
?>
	<DIV align=center>
		<input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="window.close()">
	</DIV>
		<script>opener.location.reload();</script>
<?
	exit;
}

$HTML_PAGE_TITLE = _("固定资产维护结果保存");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
	<form enctype="multipart/form-data" action="keep_result.php?SAVE=1&KEEP_ID=<?=$KEEP_ID?>&CPTL_ID=<?=$CPTL_ID?>"  method="post" name="form1">
		<table width="100%" align="center" class="TableBlock">
    		<tr>
      		<td nowrap class="TableData"><?=_("维护结果")?>:</td>
      		<td class="TableData"> <textarea name="KEEP_RESULTS" cols="35" rows="4" class="BigInput"><?=$KEEP_RESULT?></textarea></td>
    		</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" name="SAVE" value="<?=_("确认")?>" class="BigButton">&nbsp;<input type="button" onclick="location='keep.php?CPTL_ID=<?=$CPTL_ID?>'"  value="<?=_("返回")?>" class="BigButton"> </td>
			</tr>
		</table>
	</form>
</body>
</html>
