<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("被考核人自评");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<center>
<?
	$query="select * from SCORE_SELF_DATA where FLOW_ID='$FLOW_ID' and PARTICIPANT='".$_SESSION["LOGIN_USER_ID"]."'";
	$cursor=exequery(TD::conn(),$query);
	if($ROW=mysql_fetch_array($cursor))
	{
		 $SCORE=$ROW["SCORE"];//--- 取出自评分数---
     $MEMO=$ROW["MEMO"];//--- 取出自评说明---
     $MY_SCORE=explode(",",$SCORE);
     $MY_MEMO=explode(",",$MEMO);
	}	
?>
<table width="70%" class="TableBlock">
<?
//-- 生成考核项目--
 $query="select * from SCORE_ITEM where GROUP_ID='$GROUP_ID' ";
 $cursor= exequery(TD::conn(),$query);
 $ITEM_COUNT=0;

 while($ROW=mysql_fetch_array($cursor))
 {
    $ITEM_COUNT++;

    $ITEM_ID=$ROW["ITEM_ID"];
    $ITEM_NAME=$ROW["ITEM_NAME"];
    $MAX=$ROW["MAX"];
    $MIN=$ROW["MIN"];
?>
		<tr class="TableData">
		  <td nowrap align="left" width="30%"><?=$ITEM_NAME?>(<?=$MIN?><?=_("～")?><?=$MAX?>)</td>
		  <td nowrap align="center" width="20%"><?=$MY_SCORE[$ITEM_COUNT-1]?></td>
    	<td nowrap align="center" width="40%"><?=$MY_MEMO[$ITEM_COUNT-1]?></td>
<?
}
?>
<? 
	if($ITEM_COUNT > 0)
	{
?>
	  <tfoot align="center" class="TableFooter">
      <td nowrap colspan="3">
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='javascript:history.go(-1);'">
      </td>
    </tfoot>

    <thead class="TableHeader">
      <td nowrap align="center"><?=_("自评项目")?></td>
      <td nowrap align="center"><?=_("分数")?></td>
      <td nowrap align="center"><?=_("自评说明")?></td>
    </thead>
</table>
<?
	}
	else
	{	
    Message("",_("暂无已经自评的考核任务！"));	
    Button_Back();
	} 
?>
</center>
</body>
</html>