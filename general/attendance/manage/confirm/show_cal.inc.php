<table id="cal_table" class="TableBlock" width="100%" align="center">
  <tr align="center" class="TableHeader">
    <td width="6%" nowrap><b><?=_("����")?></b></td>
    <td width="14%" nowrap><b><?=_("����һ")?></b></td>
    <td width="14%" nowrap><b><?=_("���ڶ�")?></b></td>
    <td width="14%" nowrap><b><?=_("������")?></b></td>
    <td width="14%" nowrap><b><?=_("������")?></b></td>
    <td width="14%" nowrap><b><?=_("������")?></b></td>
    <td width="12%" nowrap><b><?=_("������")?></b></td>
    <td width="12%" nowrap><b><?=_("������")?></b></td>
  </tr>
<?
for($I=1;$I<=date("t",$DATE);$I++)
{
  $WEEK=date("w",strtotime($YEAR."-".$MONTH."-".$I));
  $WEEK= $WEEK==0 ? 6: $WEEK-1;

  if($WEEK==0 || $I==1)
  {
     $WEEKS=date("W", $MONTH_BEGIN+($I-1)*24*3600);
     $WEEK_BEGIN=date("Ymd", strtotime("-".$WEEK."days",strtotime($YEAR."-".$MONTH."-".$I)));
     $MSG = sprintf(_("��%d��"), $WEEKS);
     echo "  <tr height=\"80\" class=\"TableData\">\n";
     echo "    <td id=\"tw_".$WEEK_BEGIN."\" class=\"TableContent\" align=\"center\">".($MSG)."</td>\n";
  }

  for($J=0;$J<$WEEK&&$I==1;$J++)
  {
?>
    <td class="TableData" valign="top">&nbsp</td>
<?
  }
?>
    <td id="td_<?=$YEAR.$MONTH.($I<10 ? "0".$I : $I)?>" class="<?if($I==$DAY) echo "TableRed";?>" valign="top">
      <div id="div_<?=$YEAR.$MONTH.($I<10 ? "0".$I : $I)?>" align="right" class="<?=$I==$DAY ? "TableRed" : "TableContent";?>" style="cursor:hand;width: 100%;">
        <font color="blue"><b><?=$I?></b></font>
      </div>
      <div>
<?
if($ATTEND_TYPE!="all")
{
?>
   <ul><?=$CAL_ARRAY[$I]?></ul>
<?
}
else if($ATTEND_TYPE=="all")
   echo $CAL_ARRAY[$I];
?>
      </div>
    </td>
<?
  if ($WEEK==6)
     echo "  </tr>\n";
}//while

//------------- ����β�ո� -------------
if($WEEK!=6)
{
  for($I=$WEEK;$I<6;$I++)
  {
?>
    <td class="TableData">&nbsp</td>
<?
  }
?>
  </tr>
<?
}
?>
</table>