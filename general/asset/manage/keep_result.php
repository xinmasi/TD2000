<?
include_once("inc/auth.inc.php");
if(isset($SAVE))
{
	$query="update CP_ASSET_KEEP set KEEP_RESULT='$KEEP_RESULTS' where KEEP_ID='$KEEP_ID'";	
	exequery(TD::conn(),$query);
	Message("",_("ά�޽���޸ĳɹ�"));
?>
	<DIV align=center>
		<input type="button" value="<?=_("�ر�")?>" class="BigButton" onclick="window.close()">
	</DIV>
		<script>opener.location.reload();</script>
<?
	exit;
}

$HTML_PAGE_TITLE = _("�̶��ʲ�ά���������");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
	<form enctype="multipart/form-data" action="keep_result.php?SAVE=1&KEEP_ID=<?=$KEEP_ID?>&CPTL_ID=<?=$CPTL_ID?>"  method="post" name="form1">
		<table width="100%" align="center" class="TableBlock">
    		<tr>
      		<td nowrap class="TableData"><?=_("ά�����")?>:</td>
      		<td class="TableData"> <textarea name="KEEP_RESULTS" cols="35" rows="4" class="BigInput"><?=$KEEP_RESULT?></textarea></td>
    		</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" name="SAVE" value="<?=_("ȷ��")?>" class="BigButton">&nbsp;<input type="button" onclick="location='keep.php?CPTL_ID=<?=$CPTL_ID?>'"  value="<?=_("����")?>" class="BigButton"> </td>
			</tr>
		</table>
	</form>
</body>
</html>
