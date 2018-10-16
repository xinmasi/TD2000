<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("被考核人自评");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<center>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/score.gif" WIDTH="22" HEIGHT="22" align="absmiddle"><span class="big3">&nbsp;<?=_("未自评考核任务列表")?></span>
    </td>
  </tr>
</table><br>
<?
	$CUR_DATE=date("Y-m-d",time());
	$query="select * from SCORE_FLOW where find_in_set('".$_SESSION["LOGIN_USER_ID"]."',PARTICIPANT) and IS_SELF_ASSESSMENT='1' and (END_DATE>='$CUR_DATE' or END_DATE is null)";
	$cursor=exequery(TD::conn(),$query);
	$TOTAL_COUNT=0;
	while($ROW=mysql_fetch_array($cursor))
	{
		 $FLOW_ID=$ROW["FLOW_ID"];
		 $GROUP_ID=$ROW["GROUP_ID"];
		 $FLOW_TITLE=$ROW["FLOW_TITLE"];
		 $RANKMAN=td_trim(GetUserNameById($ROW["RANKMAN"]));
		 $COUNT=0;
		 $query1="select count(*) from SCORE_SELF_DATA where PARTICIPANT='".$_SESSION["LOGIN_USER_ID"]."' and FLOW_ID='$FLOW_ID'";
		 $cursor1=exequery(TD::conn(),$query1);
		 if($ROW1=mysql_fetch_array($cursor1))
		 {
		 			$COUNT=$ROW1[0];
		 }
		 if($COUNT==0)
		 {
		 		$TOTAL_COUNT++;	
			  if($TOTAL_COUNT%2==1)
			     $TableLine="TableLine1";
			  else
			     $TableLine="TableLine2";
		 		if($TOTAL_COUNT==1)
		 		{
?>
<table border="0" width="80%" cellspacing="0" cellpadding="3" class="TableList">
	<tr class="TableHeader">
		<td align="center" style="width:150px"><?=("考核项目名称")?></td>
		<td align="center" ><?=("考核人")?></td>
		<td align="center" style="width:90px"><?=("状态")?></td>
		<td align="center" style="width:90px"><?=("操作")?></td>
	</tr>
	<tr class="<?=$TableLine?>">
		<td align="center" ><?=$FLOW_TITLE ?></td>
		<td align="center" ><?=$RANKMAN?></td>
		<td align="center"><?=("未自评")?></td>
		<td align="center"><a href="self_assessment.php?FLOW_ID=<?=$FLOW_ID ?>&GROUP_ID=<?=$GROUP_ID?>"> <?=("自评")?></a></td>
	</tr>
<?		 				
		 		}
		 		else
		 		{
?>
	<tr class="<?=$TableLine?>">
		<td align="center" class="TableData"><?=$FLOW_TITLE ?></td>
		<td align="center"><?=$RANKMAN?></td>
		<td align="center"><?=("未自评")?></td>
		<td align="center"><a href="self_assessment.php?FLOW_ID=<?=$FLOW_ID ?>&GROUP_ID=<?=$GROUP_ID?>"> <?=("自评")?></a></td>
	</tr>
<?
		 		}
		 }
		 
	}
	if($TOTAL_COUNT==0)
	{
			Message(_("提示"),_("暂无未自评的考核项目！"));				
	}
?>
</center>
</body>
</html>
