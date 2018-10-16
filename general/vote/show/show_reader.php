<?
include_once("inc/conn.php");
include_once("inc/auth.inc.php");
include_once("inc/utility.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

function ParseItemName($ITEM_NAME,$ITEM_ID,$COUNT=1,$VOTE_ID)
{

   $POS=strpos($ITEM_NAME, "{");
   if($POS===false)
      return $ITEM_NAME;
   
   if(substr($ITEM_NAME, $POS, 6)=="{text}")
      return substr($ITEM_NAME, 0, $POS)."&nbsp;<a href=\"javascript:vote_data(".$VOTE_ID.",'INPUT_ITEM_".$ITEM_ID."_".$COUNT++."')\" style=\"text-decoration: underline;\">"._("详情")."</a>".ParseItemName(substr($ITEM_NAME, $POS+6),$ITEM_ID,$COUNT,$VOTE_ID);
   if(substr($ITEM_NAME, $POS, 8)=="{number}")
      return substr($ITEM_NAME, 0, $POS)."&nbsp;<a href=\"javascript:vote_data(".$VOTE_ID.",'INPUT_ITEM_".$ITEM_ID."_".$COUNT++."')\" style=\"text-decoration: underline;\">"._("详情")."</a>".ParseItemName(substr($ITEM_NAME, $POS+8),$ITEM_ID,$COUNT,$VOTE_ID);
   if(substr($ITEM_NAME, $POS, 10)=="{textarea}")
      return substr($ITEM_NAME, 0, $POS)."&nbsp;<a href=\"javascript:vote_data(".$VOTE_ID.",'INPUT_ITEM_".$ITEM_ID."_".$COUNT++."')\" style=\"text-decoration: underline;\">"._("详情")."</a>".ParseItemName(substr($ITEM_NAME, $POS+10),$ITEM_ID,$COUNT,$VOTE_ID);
   
   return substr($ITEM_NAME, 0, $POS+1).ParseItemName(substr($ITEM_NAME, $POS+1), $ITEM_ID,'',$VOTE_ID);
}

$HTML_PAGE_TITLE = _("投票结果");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>

<script>
function vote_data(vote_id, item_pos)
{
 URL="vote_data.php?VOTE_ID="+vote_id+"#"+item_pos;
 myleft=(screen.availWidth-300)/2;
 window.open(URL,"vote_data","height=300,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=250,left="+myleft+",resizable=yes");
}
</script>

<body class="bodycolor">
<?
 $query = "SELECT * from VOTE_TITLE where VOTE_ID='$VOTE_ID' and  PUBLISH='1' and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)".dept_other_sql("TO_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',VIEW_RESULT_USER_ID) or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)".priv_other_sql("PRIV_ID").")";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 if($ROW=mysql_fetch_array($cursor))
 {
     $SUBJECT=$ROW["SUBJECT"];
     $TO_ID=$ROW["TO_ID"];
     $ANONYMITY=$ROW["ANONYMITY"];
     $READERS=$ROW["READERS"];
     $CONTENT=$ROW["CONTENT"];
     $CONTENT=td_htmlspecialchars($CONTENT);
     $CONTENT=nl2br($CONTENT);
	   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
	   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
 }
 else
    exit;
 
 if($ATTACHMENT_NAME!="")
 {
     $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
     $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);
     for($I=0;$I<count($ATTACHMENT_ID_ARRAY);$I++)
     {
        if($ATTACHMENT_ID_ARRAY[$I]=="")
           continue;

        if(is_image($ATTACHMENT_NAME_ARRAY[$I]))
	       $IMAGE_COUNT++;
     }
 }
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" align="center" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vote.gif" align="absmiddle"><span class="big3"> <?=_("投票结果")?> - <?=$SUBJECT?></span><br>
    </td>
    </tr>
  <tr>
    <td class="small1" style="height:25px"><span style="padding-left:10px;"><?=$CONTENT?></span></td>
  </tr>
</table>

<table class="TableList" width="100%" align="center" style="margin-top:10px;">
<?
$query = "SELECT count(*) from VOTE_ITEM where VOTE_ID='$VOTE_ID'";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
  $ITEM_COUNT=$ROW[0];

if($ITEM_COUNT>0)
{
 $query = "SELECT * from VOTE_TITLE where VOTE_ID='$VOTE_ID'";
 $cursor2= exequery(TD::conn(),$query,$QUERY_MASTER);
 
 $ITEM_COUNT=0;
 while($ROW2=mysql_fetch_array($cursor2))
 {
     $ITEM_COUNT++;
     $VOTE_ID=$ROW2["VOTE_ID"];
     $TYPE=$ROW2["TYPE"];
     $SUBJECT=$ITEM_COUNT._("、").$ROW2["SUBJECT"];
?>
  <tr class="TableHeader">
    <td colspan="3"><?=$SUBJECT?></td>
  </tr>
<?
     if($TYPE==0 || $TYPE==1)
     {
        $query = "SELECT sum(VOTE_COUNT),max(VOTE_COUNT) from VOTE_ITEM where VOTE_ID='$VOTE_ID'";
        $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
        if($ROW=mysql_fetch_array($cursor))
        {
           $SUM_COUNT=$ROW[0];
           $MAX_COUNT=$ROW[1];
        }
        if($SUM_COUNT==0||$SUM_COUNT=="")
           $SUM_COUNT=1;
        if($MAX_COUNT==0||$MAX_COUNT=="")
           $MAX_COUNT=1;
           
        $query = "SELECT * from VOTE_ITEM where VOTE_ID='$VOTE_ID' order by ITEM_ID";
        $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
        $NO=0;
        while($ROW=mysql_fetch_array($cursor))
        {
           $ITEM_ID=$ROW["ITEM_ID"];
           if($NO>=26)
              $ITEM_NAME=chr($NO%26+65).floor($NO/26)._("、").$ROW["ITEM_NAME"];
           else
              $ITEM_NAME=chr($NO%26+65)._("、").$ROW["ITEM_NAME"];
           $VOTE_COUNT=$ROW["VOTE_COUNT"];

           $ITEM_NAME=ParseItemName($ITEM_NAME,$ITEM_ID,'',$VOTE_ID);
           $NO++;
?>
  <tr class="TableData">
    <td width="35%">&nbsp;<?=$ITEM_NAME?></td>
    <td width="239">
      <table height="10" border="0" cellspacing="0" cellpadding="0" class="small">
        <tr height="10">
          <td width="<?=$VOTE_COUNT*200/$MAX_COUNT?>" style="background:url('<?=MYOA_STATIC_SERVER?>/static/images/vote_bg.gif') repeat-x;border:none;"></td>
          <td width="30" style="border:none;"><?=round($VOTE_COUNT*100/$SUM_COUNT)?>%</td>
        </tr>
      </table>
    </td>
    <td align="right"><?=$VOTE_COUNT?><?=_("票")?></td>
  </tr>
<?
        }
     }
     else
     {
?>
  <tr class="TableData">
    <td colspan="4">
<?
      $query = "SELECT * from VOTE_DATA where ITEM_ID='$VOTE_ID' and FIELD_NAME='0'";
      $cursor= exequery(TD::conn(),$query);
      while($ROW=mysql_fetch_array($cursor))
      {
         $USER_ID=$ROW["USER_ID"];
         $FIELD_DATA=$ROW["FIELD_DATA"];
         
         $FIELD_DATA=td_htmlspecialchars($FIELD_DATA);
         $FIELD_DATA=nl2br($FIELD_DATA);
         
         echo "<li>".$FIELD_DATA."</li><br>";
      }
?>
    </td>
  </tr>
<?
    }
 }
}
 $query = "SELECT * from VOTE_TITLE where PARENT_ID='$VOTE_ID' order by VOTE_NO,SEND_TIME";
 $cursor2= exequery(TD::conn(),$query,$QUERY_MASTER);
 while($ROW2=mysql_fetch_array($cursor2))
 {
     $ITEM_COUNT++;
     $VOTE_ID=$ROW2["VOTE_ID"];
     $TYPE=$ROW2["TYPE"];
     $SUBJECT=$ITEM_COUNT._("、").$ROW2["SUBJECT"];
?>
  <tr class="TableHeader">
    <td colspan="3"><?=$SUBJECT?></td>
  </tr>
<?
     if($TYPE==0 || $TYPE==1)
     {
        $query = "SELECT sum(VOTE_COUNT),max(VOTE_COUNT) from VOTE_ITEM where VOTE_ID='$VOTE_ID'";
        $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
        if($ROW=mysql_fetch_array($cursor))
        {
           $SUM_COUNT=$ROW[0];
           $MAX_COUNT=$ROW[1];
        }
        if($SUM_COUNT==0||$SUM_COUNT=="")
           $SUM_COUNT=1;
        if($MAX_COUNT==0||$MAX_COUNT=="")
           $MAX_COUNT=1;
     
        $query = "SELECT * from VOTE_ITEM where VOTE_ID='$VOTE_ID' order by ITEM_ID";
        $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
        $NO=0;
        while($ROW=mysql_fetch_array($cursor))
        {
           $ITEM_ID=$ROW["ITEM_ID"];
           if($NO>=26)
              $ITEM_NAME=chr($NO%26+65).floor($NO/26)._("、").$ROW["ITEM_NAME"];
           else
              $ITEM_NAME=chr($NO%26+65)._("、").$ROW["ITEM_NAME"];
           $VOTE_COUNT=$ROW["VOTE_COUNT"];
         
           $ITEM_NAME=ParseItemName($ITEM_NAME,$ITEM_ID,'',$VOTE_ID);
           $NO++;
?>
  <tr class="TableData">
    <td>&nbsp;<?=$ITEM_NAME?></td>
    <td width="240">
      <table height="10" border="0" cellspacing="0" cellpadding="0" class="small">
        <tr height="10">
          <td width="<?=$VOTE_COUNT*200/$MAX_COUNT?>" style="background:url('<?=MYOA_STATIC_SERVER?>/static/images/vote_bg.gif') repeat-x;border:none;"></td>
          <td width="30" style="border:none;"><?=round($VOTE_COUNT*100/$SUM_COUNT)?>%</td>
        </tr>
      </table>
    </td>
    <td align="right"><?=$VOTE_COUNT?><?=_("票")?></td>
  </tr>
<?
        }
     }
     else
     {
?>
  <tr class="TableData">
    <td colspan="4">
<?
      $query = "SELECT * from VOTE_DATA where ITEM_ID='$VOTE_ID' and FIELD_NAME='0'";
      $cursor= exequery(TD::conn(),$query);
      while($ROW=mysql_fetch_array($cursor))
      {
         $USER_ID=$ROW["USER_ID"];
         $FIELD_DATA=$ROW["FIELD_DATA"];
         
         $FIELD_DATA=td_htmlspecialchars($FIELD_DATA);
         $FIELD_DATA=nl2br($FIELD_DATA);
         
         echo "<li>".$FIELD_DATA."</li><br>";
      }
?>
    </td>
  </tr>
<?
    }
}
?>
<?
if($ATTACHMENT_NAME!="")
{
?>
    <tr>
      <td class="TableData" colspan="4"><?=_("附件文件")?>:<br><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1)?></td>
    </tr>
<?
}	
	
if($IMAGE_COUNT>0)
{
?>
    <tr class="TableData">
      <td colspan="4">
        <img src="<?=MYOA_STATIC_SERVER?>/static/images/image.gif" align="absmiddle" border="0">&nbsp;<?=_("附件图片")?>: <br><br>

<?
   $MODULE=attach_sub_dir();
   for($I=0;$I<count($ATTACHMENT_ID_ARRAY);$I++)
   {
      if($ATTACHMENT_ID_ARRAY[$I]=="" || stristr($CONTENT, $ATTACHMENT_ID_ARRAY[$I]) || stristr($CONTENT, $ATTACHMENT_NAME_ARRAY[$I]))
         continue;

      $IMG_ATTR=td_getimagesize(attach_real_path($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]));
	  if(is_array($IMG_ATTR) && $IMG_ATTR[0]>0 && $IMG_ATTR[1]>0)
	  {
         //$WIDTH=floor($IMG_ATTR[0]*100/$IMG_ATTR[1]);
		 $WIDTH=$IMG_ATTR[0];
		 $HEIGHT=$IMG_ATTR[1];
	  }
      else
	  {
         $WIDTH=100;
		 $HEIGHT=100;
      }
      $ATTACHMENT_ID=$ATTACHMENT_ID_ARRAY[$I];
      $YM=substr($ATTACHMENT_ID,0,strpos($ATTACHMENT_ID,"_"));
      if($YM)
         $ATTACHMENT_ID=substr($ATTACHMENT_ID,strpos($ATTACHMENT_ID,"_")+1);
      $ATTACHMENT_ID_ENCODED=attach_id_encode($ATTACHMENT_ID,$ATTACHMENT_NAME_ARRAY[$I]);

      if(is_image($ATTACHMENT_NAME_ARRAY[$I]))
      {
?>
          <a href="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>"><img src="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>" border="1"  width="<?=$WIDTH?>" height="<?=$HEIGHT?>" alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME_ARRAY[$I]?>"></a><?=_("　")?>
<?
      }
   }
?>
      </td>
    </tr>
<?
}
?>
</table>
<div align="center">
   <br>
   <input type="button" value="<?=_("打印")?>" class="BigButton" onClick="document.execCommand('Print');" title="<?=_("打印文件内容")?>">&nbsp;&nbsp;
   <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();">
</div>
</body>
</html>