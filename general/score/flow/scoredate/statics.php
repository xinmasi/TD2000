<?
include_once("inc/auth.inc.php");
 $query = "SELECT * from SCORE_ITEM where GROUP_ID='$GROUP_ID'";
 $cursor= exequery(TD::conn(),$query);
 $VOTE_COUNT=0;
 $TOTAL_SAM=0;
 while($ROW=mysql_fetch_array($cursor))
 {
     $ITEM_NAME[$VOTE_COUNT]=$ROW["ITEM_NAME"];
     $TOTAL_SAM=$TOTAL_SAM+$ROW["MAX"];
     $VOTE_COUNT++;
 }



$HTML_PAGE_TITLE = _("��ֵͳ��");
include_once("inc/header.inc.php");
?>



<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.STATICS.value=="")
   { alert("<?=_("������Ҫͳ�Ƶķ�ֵ�Σ�����")?>");
     return (false);
   }
   return (true);
}
</script>


 <table border="0" width="450" cellpadding="2" cellspacing="1" align="center" bgcolor="#000000" class="small">
  <form action="result.php?GROUP_ID=<?=$GROUP_ID?>&FLOW_ID=<?=$FLOW_ID?>"  method="post" name="form1" onsubmit="return CheckForm();">
    <tr>
      <td nowrap class="TableData"><?=_("������Ҫͳ�Ƶķ�ֵ��")?>:</td>
    </tr>
    <tr><td class="TableData">
        <textarea name="STATICS" cols="45" rows="5" class="BigInput"></textarea>
        </td>
    </tr>
    <tr>
    	<td nowrap class="TableData"><?=_("�ÿ���ָ�꼯���ܷ�")?>:<?=$TOTAL_SAM?></td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("ȷ��")?>" class="SmallButton">&nbsp;&nbsp;
        <input type="button"  value="<?=_("�ر�")?>" class="SmallButton" onclick="window.close();">
      </td>
    </tr>
  </table>
</form>

<div align=left>

 <table border="0" width="450" cellpadding="2" cellspacing="0"  bgcolor="#000000" class="small">
    <tr>
      <td nowrap class="TableData"><?=_("˵��")?>:<?=_("ͳ���ض������ε���������������(100,90,89,80,79,70,69,60,59)ͳ�Ƶķ�����Ϊ(100-90,89-80,79-70,69-60,59-0)")?></td>
    </tr>
</div>

</body>
</html>