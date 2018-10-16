<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("领用物品清单");
include_once("inc/header.inc.php");
include_once("inc/utility_field.php");

 $query = "SELECT * from OFFICE_TRANSHISTORY,OFFICE_PRODUCTS where BORROWER='$LEAVE_PERSON' and  OFFICE_TRANSHISTORY.PRO_ID=OFFICE_PRODUCTS.PRO_ID AND(TRANS_FLAG=1 OR TRANS_FLAG=2 OR TRANS_FLAG=3) order by TRANS_DATE";
 $cursor= exequery(TD::conn(),$query);
 $ITEM_COUNT=mysql_num_rows($cursor);
 if($ITEM_COUNT>0)
 {
?>
<table width="100%" class="TableList">
<tr class="TableHeader"><td><?=_("物品名称")?></td><td><?=_("数量")?></td><td><?=_("单位")?></td><td><?=_("状态")?></td><td><?=_("日期")?></td></tr><?
	 while($ROW=mysql_fetch_array($cursor))
	 {
		$PRO_UNIT=$ROW["PRO_UNIT"];
		$PRO_NAME=$ROW["PRO_NAME"];
		$TRANS_FLAG=$ROW["TRANS_FLAG"];
		$TRANS_QTY=$ROW["TRANS_QTY"];
		$TRANS_DATE=$ROW["TRANS_DATE"];
	
		if ($TRANS_FLAG=="1")
		{
		 $TRANS_NAME="<font color=green>"._("领用")."</font>";
		 $TRANS_QTY="<font color=green>".-$TRANS_QTY."</font>";
		}
		if ($TRANS_FLAG=="2")
		{
		 $TRANS_NAME="<font color=green>"._("借用")."</font>";
		 $TRANS_QTY="<font color=green>".-$TRANS_QTY."</font>";
		}
		if ($TRANS_FLAG=="3")
		{
		 $TRANS_NAME="<font color=red>"._("归还")."</font>";
		 $TRANS_QTY="<font color=red>".$TRANS_QTY."</font>";
		}
?>
<tr class="TableData"><td nowrap align=center><?=$PRO_NAME?></td><td nowrap align=center><?=$TRANS_QTY?></td><td nowrap align=center><?=$PRO_UNIT?></td><td nowrap align=center><?=$TRANS_NAME?></td><td nowrap align=center><?=$TRANS_DATE?></td></tr>
<?
	  }//end while
?>
</table>
<?
  }
  else  //ITEM_COUNT=0
  {
    Message("",_("无领用物品记录"));
  }
//use_items('admin');
?>