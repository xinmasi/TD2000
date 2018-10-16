<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("被考核人自评信息");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
 $query = "SELECT * from USER where USER_ID='$USER_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
 {
    $USER_NAME=$ROW["USER_NAME"];
 }
?>
<table border="0"  cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/salary.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3">
    	 <?=_("自评信息查看")?>(<?=$USER_NAME?>)</span>
    </td>

  </tr>
</table>

<div align="center">
<?
   $FLOW_ID =intval($FLOW_ID );
   //-- 首先查询是否已录入过数据--
   $query="select * from SCORE_SELF_DATA where FLOW_ID='$FLOW_ID' and PARTICIPANT='$USER_ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
     $SCORE=$ROW["SCORE"];//--- 取出分数---
     $MEMO=$ROW["MEMO"];//--- 取出备注---
     $MY_SCORE=explode(",",$SCORE);
     $MY_MEMO=explode(",",$MEMO);
     $OPERATION=2; //-- 将执行数据更新 --

   }
   else
     $OPERATION=1; //-- 将执行数据插入 --

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
    if($ITEM_COUNT==1)
    {
?>
    		<table width="90%" class="TableBlock">
<?
    }
?>
		<tr class="TableData">
		  <td nowrap align="left" width="30%"><?=$ITEM_NAME?>(<?=$MIN?><?=_("～")?><?=$MAX?>)</td>
		  <td nowrap align="center" width="20%"><?=$MY_SCORE[$ITEM_COUNT-1]?></td>
    	<td nowrap align="center" width="40%"><?=$MY_MEMO[$ITEM_COUNT-1]?></td>
    </tr>
<?
 }
 if($ITEM_COUNT>0)
 {
?>
    <tfoot align="center" class="TableFooter">
      <td nowrap colspan="3">
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="javascript:window.close()">
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
?>
</div>

</body>
</html>
