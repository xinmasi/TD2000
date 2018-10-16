<?
include_once("inc/auth.inc.php");

function ParseItemName($ITEM_NAME,$ITEM_ID,$COUNT=1)
{
   $POS=strpos($ITEM_NAME, "{");
   if($POS===false)
      return $ITEM_NAME;
   
   if(substr($ITEM_NAME, $POS, 6)=="{text}")
      return substr($ITEM_NAME, 0, $POS)."&nbsp;<a href=\"#INPUT_ITEM_".$ITEM_ID."_".$COUNT++."\" style=\"text-decoration: underline;\">"._("详情")."</a>".ParseItemName(substr($ITEM_NAME, $POS+6),$ITEM_ID,$COUNT);
   if(substr($ITEM_NAME, $POS, 8)=="{number}")
      return substr($ITEM_NAME, 0, $POS)."&nbsp;<a href=\"#INPUT_ITEM_".$ITEM_ID."_".$COUNT++."\" style=\"text-decoration: underline;\">"._("详情")."</a>".ParseItemName(substr($ITEM_NAME, $POS+8),$ITEM_ID,$COUNT);
   if(substr($ITEM_NAME, $POS, 10)=="{textarea}")
      return substr($ITEM_NAME, 0, $POS)."&nbsp;<a href=\"#INPUT_ITEM_".$ITEM_ID."_".$COUNT++."\" style=\"text-decoration: underline;\">"._("详情")."</a>".ParseItemName(substr($ITEM_NAME, $POS+10),$ITEM_ID,$COUNT);
   
   return substr($ITEM_NAME, 0, $POS+1).ParseItemName(substr($ITEM_NAME, $POS+1), $ITEM_ID);
}
function ParseItemName2($ITEM_NAME,&$ARRAY)
{
   $POS=strpos($ITEM_NAME, "{");
   if($POS===false)
      return;
   
   if(substr($ITEM_NAME, $POS, 6)=="{text}")
   {
      $ARRAY[count($ARRAY)]="text";
      ParseItemName2(substr($ITEM_NAME, $POS+6),$ARRAY);
   }
   else if(substr($ITEM_NAME, $POS, 8)=="{number}")
   {
      $ARRAY[count($ARRAY)]="number";
      ParseItemName2(substr($ITEM_NAME, $POS+8),$ARRAY);
   }
   else if(substr($ITEM_NAME, $POS, 10)=="{textarea}")
   {
      $ARRAY[count($ARRAY)]="textarea";
      ParseItemName2(substr($ITEM_NAME, $POS+10),$ARRAY);
   }
   else
      ParseItemName2(substr($ITEM_NAME, $POS+1), $ARRAY);
}

$HTML_PAGE_TITLE = _("投票数据");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
 $query = "SELECT SUBJECT from VOTE_TITLE where VOTE_ID='$VOTE_ID'";
 $cursor2= exequery(TD::conn(),$query);
 if($ROW2=mysql_fetch_array($cursor2))
    $SUBJECT=$ROW2["SUBJECT"];
 else
    exit;
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big3" align="center"><?=$SUBJECT?></td>
    </td>
  </tr>
</table>
<table class="TableList" width="100%" align="center">
<?
 $query = "SELECT ITEM_ID,ITEM_NAME from VOTE_ITEM where VOTE_ID='$VOTE_ID'";
 $cursor2= exequery(TD::conn(),$query);
 while($ROW2=mysql_fetch_array($cursor2))
 {
     $ITEM_ID=$ROW2["ITEM_ID"];
     $ITEM_NAME=$ROW2["ITEM_NAME"];
     
     if(!strstr($ITEM_NAME,"{text}") && !strstr($ITEM_NAME,"{number}") && !strstr($ITEM_NAME,"{textarea}"))
        continue;
     
     $ARRAY=array();
     ParseItemName2($ITEM_NAME,$ARRAY);
     $ITEM_NAME=ParseItemName($ITEM_NAME,$ITEM_ID);
?>
    <tr class="TableHeader">
      <td colspan="2">
        <?=$ITEM_NAME?>
      </td>
    </tr>
    <tr class="TableData">
      <td>
<?
    for($I=0;$I<count($ARRAY);$I++)
    {
?>
        <a name="INPUT_ITEM_<?=$ITEM_ID?>_<?=$I+1?>"><b><?=_("【")?><?=_("输入项")?><?=$I+1?><?=_("】：")?></b></a><br>
<?
       if($ARRAY[$I]=="number")
       {
          $query = "SELECT FIELD_DATA,count(*) from VOTE_DATA where ITEM_ID='$ITEM_ID' and FIELD_NAME='".($I+1)."' group by FIELD_DATA";
          $cursor1= exequery(TD::conn(),$query);
          while($ROW1=mysql_fetch_array($cursor1))
             echo "<li>".$ROW1[0]."(".$ROW1[1]._("票").")"."</li><br>";
       }
       else
       {
          $query = "SELECT FIELD_DATA from VOTE_DATA where ITEM_ID='$ITEM_ID' and FIELD_NAME='".($I+1)."'";
          $cursor1= exequery(TD::conn(),$query);
          while($ROW1=mysql_fetch_array($cursor1))
          {
             $FIELD_DATA=$ROW1["FIELD_DATA"];
?>        
             <li><?=$FIELD_DATA?></li><br>
<?        
          }
       }
    }
?>
      </td>
    </tr>
<?
}
?>
    <tr align="center" class="TableControl">
      <td colspan="2">
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="javascript:window.close();">
      </td>
    </tr>
  </table>
</body>
</html>