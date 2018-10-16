<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
if($OP=="1")
{
   $MSG_FILE=MYOA_ATTACH_PATH."netmeeting/".$MEET_ID.".msg";
   $STOP_FILE=MYOA_ATTACH_PATH."netmeeting/".$MEET_ID.".stp";
   $USR_FILE=MYOA_ATTACH_PATH."netmeeting/".$MEET_ID.".usr";
   
   @unlink($MSG_FILE);
   @unlink($STOP_FILE);
   @unlink($USR_FILE);
}

$HTML_PAGE_TITLE = _("网络会议发言内容");
include_once("inc/header.inc.php");
?>


<script>
function delete_record(MEET_ID)
{
   msg='<?=_("确认要删除该会议室的聊天记录吗？")?>';
   if(window.confirm(msg))
   {
     url="record.php?OP=1&MEET_ID="+ MEET_ID;
     location=url;
   }
}

function say_to(id,name)
{
  return;
}
</script>


<body bgcolor="#F1FAF5" topmargin="5">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/netmeeting.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><b> <?=_("网络会议记录")?></b><br>
    </td>
    <td><br><center><input type="button" class="BigButton" value="<?=_("清空聊天记录")?>" onclick="delete_record('<?=$MEET_ID?>');">&nbsp;
    <input type="button" class="BigButton" value="<?=_("返回")?>" onclick="location='index.php';"></center></td>
  </tr>
</table>

<hr width="95%" height="1" align="left">

<?
$CUR_TIME=date("Y-m-d H:i:s",time());
$MSG_FILE=MYOA_ATTACH_PATH."netmeeting/".$MEET_ID.".msg";

if(!file_exists($MSG_FILE))
{
   $fp=td_fopen($MSG_FILE,"a+");
   fclose($fp);
}


$LINES=file($MSG_FILE);
$LINES_COUNT=count($LINES);

$LINES_START=0;

for($I=$LINES_START;$I<$LINES_COUNT;$I++)
{
    $STR=substr($LINES[$I],0,strlen($LINES[$I])-2);
    
    $TO_STR=substr($STR,0,strpos($STR,"@+#"));

    if($TO_STR=="")
    {
    
      $STR=str_replace("parent.chat_input.","",$STR);
      $OUT_PUT="<span>".$STR."</span><br>\n";
      echo $OUT_PUT;
    }
}
?>


</body>
</html>