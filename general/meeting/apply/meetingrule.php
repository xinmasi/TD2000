<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("会议室管理制度");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/mouse_mon.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" HEIGHT="20" width="20" align="absmiddle"><span class="big3"> <?=_("会议室管理制度")?></span>
    </td>
  </tr>
</table>
<?
$query="select * from meeting_rule";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$RULE_ID=$ROW['RULE_ID'];
	$MR_RULE=$ROW['MEETING_ROOM_RULE'];
	$MEETING_OPERATOR=$ROW['MEETING_OPERATOR'];
	$IS_APPROVE=$ROW['MEETING_IS_APPROVE'];
	$ATTACHMENT_ID=$ROW['ATTACHMENT_ID'];
	$ATTACHMENT_NAME=$ROW['ATTACHMENT_NAME'];
}

$ATTACH_ARRAY = trim_inserted_image($MR_RULE, $ATTACHMENT_ID, $ATTACHMENT_NAME);

if($MR_RULE!="")
{
?>
<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#000000" class="small" align="center">
<tr>
	<td class=TableData nowrap><?=$MR_RULE?></td>
	<?
if($ATTACH_ARRAY["IMAGE_COUNT"]>0)
{
?>
  <table class="Tablenoborder" width="96%">
    <tr>
      <td colspan="2">
        <img src="<?=MYOA_STATIC_SERVER?>/static/images/image.gif" align="absmiddle" border="0">&nbsp;<?=_("附件图片")?>: <br><br>

<?
   $MODULE=attach_sub_dir();
   $ATTACHMENT_ID_ARRAY=explode(",",$ATTACH_ARRAY["ID"]);
   $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACH_ARRAY["NAME"]);
   $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
   for($I=0;$I<$ARRAY_COUNT;$I++)
   {
      if($ATTACHMENT_ID_ARRAY[$I]=="")
         continue;

      $IMG_ATTR=td_getimagesize(attach_real_path($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]));
      if(is_array($IMG_ATTR) && $IMG_ATTR[0]>0 && $IMG_ATTR[1]>0)
         $WIDTH=floor($IMG_ATTR[0]*100/$IMG_ATTR[1]);
      else
         $WIDTH=100;

      $ATTACHMENT_ID=$ATTACHMENT_ID_ARRAY[$I];
      $YM=substr($ATTACHMENT_ID,0,strpos($ATTACHMENT_ID,"_"));
      if($YM)
         $ATTACHMENT_ID=substr($ATTACHMENT_ID,strpos($ATTACHMENT_ID,"_")+1);
      $ATTACHMENT_ID_ENCODED=attach_id_encode($ATTACHMENT_ID,$ATTACHMENT_NAME_ARRAY[$I]);
      $URL_ARRAY = attach_url($ATTACHMENT_ID_ARRAY[$I], $ATTACHMENT_NAME_ARRAY[$I], $MODULE, $OTHER);
      if(is_image($ATTACHMENT_NAME_ARRAY[$I]))
      {
      	
?>
          <!--<a href="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>"><img src="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>" border="1"  width="<?=$WIDTH?>" height="100" alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME_ARRAY[$I]?>"></a><?=_("　")?>-->
          <a href="javascript:;" onClick="window.open('/module/mediaplayer/index.php?MEDIA_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>&MEDIA_URL=<?=urlencode($URL_ARRAY['down'])?>','media<?=abs($ATTACHMENT_ID_ENCODED)?>','menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width=800,height=600');"><img src="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>" border="1"  width="<?=$WIDTH?>" height="100" alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME_ARRAY[$I]?>"></a>
<?
      }
   }
?>
      </td>
    </tr>
  </table>
<?
}
?>
</tr>
<?
if($ATTACHMENT_ID!="" && $ATTACHMENT_NAME!="")
{
?>
    <tr>
      <td nowrap class="TableData"><?=_("附件：")?>
      	<?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,0,1,0)?></td>
    </tr>
<?
}
?>
</table>
<?
}
else
{
   Message("",_("无会议室管理制度！"));
}
?>
<br>
<center><input type="button" value="<?=_("关闭")?>" class="SmallButton" onclick="window.close()"></center>


</body>
</HTML>
