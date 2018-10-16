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
$query = "SELECT SUBJECT,PARENT_ID from VOTE_TITLE where VOTE_ID='$VOTE_ID'";
if($_SESSION["LOGIN_USER_PRIV"]!="1")
   $query.=" and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor2= exequery(TD::conn(),$query);
if($ROW2=mysql_fetch_array($cursor2))
{
    $SUBJECT=$ROW2["SUBJECT"];
    $PARENT_ID=$ROW2["PARENT_ID"];
    
    $query = "SELECT ANONYMITY from VOTE_TITLE where VOTE_ID='$PARENT_ID'";
    $cursor2= exequery(TD::conn(),$query);
    if($ROW2=mysql_fetch_array($cursor2))
       $ANONYMITY=$ROW2["ANONYMITY"];
}
else
   exit;
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big3" align="center"><?=$SUBJECT?></td>
    </td>
  </tr>
</table>
<table class="TableBlock" width="100%" align="center">
<?
 $query = "SELECT * from VOTE_ITEM where VOTE_ID='$VOTE_ID'";
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
        <a name="INPUT_ITEM_<?=$ITEM_ID?>_<?=$I+1?>"><b><?=sprintf(_("【输入项%s】："), $I+1)?></b></a><br>
<?
       if($ARRAY[$I]=="number")
       {
          $query = "SELECT FIELD_DATA,count(*) from VOTE_DATA where ITEM_ID='$ITEM_ID' and FIELD_NAME='".($I+1)."' group by FIELD_DATA";
          $cursor1= exequery(TD::conn(),$query);
          while($ROW1=mysql_fetch_array($cursor1))
             echo "<li>".$ROW1[0]."(".$ROW1[1]._("票").")"."</li><br>";
          
          echo sprintf(_("【输入项%s详细信息】："), ($I+1))."<br>";
       }
       
       $query = "SELECT * from VOTE_DATA where ITEM_ID='$ITEM_ID' and FIELD_NAME='".($I+1)."'";
       $cursor1= exequery(TD::conn(),$query);
       while($ROW1=mysql_fetch_array($cursor1))
       {
          $USER_ID=$ROW1["USER_ID"];
          $FIELD_DATA=$ROW1["FIELD_DATA"];
          
          if($USER_ID!="")
          {
             $query = "SELECT * from USER where USER_ID='$USER_ID'";
             $cursor= exequery(TD::conn(),$query);
             if($ROW=mysql_fetch_array($cursor))
                $USER_ID=_("【").$ROW["USER_NAME"]._("】");
          }
?>
       <!--<li><?=$USER_ID?><?=$FIELD_DATA?></li><br>-->
       <li><?=$FIELD_DATA?></li><br>
<?
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