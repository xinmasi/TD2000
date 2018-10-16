<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("工作日志查询");
include_once("inc/header.inc.php");
?>


<script>
function delete_diary(DIA_ID)
{
 msg='<?=_("确认要删除该日的工作日志吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?DIA_ID=" + DIA_ID;
  window.location=URL;
 }
}
function SaveFile(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  URL="/module/save_file/?ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+ATTACHMENT_NAME+"&A=1";
  loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
  loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
  window.open(URL,null,"height=180,width=400,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes");
}
</script>


<body class="bodycolor">

<?
  //----------- 合法性校验 ---------
  if($BEGIN_DATE!="")
  {
    $TIME_OK=is_date($BEGIN_DATE);

    if(!$TIME_OK)
    { Message(_("错误"),_("起始日期格式不对，应形如 1999-1-2"));
      Button_Back();
      exit;
    }
  }

  if($END_DATE!="")
  {
    $TIME_OK=is_date($END_DATE);

    if(!$TIME_OK)
    { Message(_("错误"),_("截止日期格式不对，应形如 1999-1-2"));
      Button_Back();
      exit;
    }
  }

?>

<div align="center" class="Big1">
<b>[<?=$USER_NAME?> - <?=_("工作日志查询")?>]</b>
</div>
<br>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/diary.gif" WIDTH="18" HEIGHT="18" align="absmiddle"><span class="big3"> <?=_("查询结果")?></span>
    </td>
  </tr>
</table>

<?
 $BEGIN_DATE.="00:00:00";
 $END_DATE.="23:59:59";
 //============================ 显示日志 =======================================
 $query = "SELECT * from DIARY where DIA_DATE>='$BEGIN_DATE' and DIA_DATE<='$END_DATE' and USER_ID='$USER_ID' and DIA_TYPE!='2'";

 if($SUBJECT!="0")
   $query .= " and SUBJECT like '%$SUBJECT%'";
 $query .= " order by DIA_DATE desc";

 $cursor= exequery(TD::conn(),$query);
 $DIA_COUNT=0;

 while($ROW=mysql_fetch_array($cursor))
 {
    $DIA_ID=$ROW["DIA_ID"];
    $DIA_DATE=$ROW["DIA_DATE"];
    $DIA_DATE=strtok($DIA_DATE," ");
    $SUBJECT=$ROW["SUBJECT"];
    $CONTENT=$ROW["CONTENT"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

    $FLAG=0;
    if($KEY1!="" && stristr(strip_tags($CONTENT),$KEY1))
       $FLAG++;
    if($KEY2!="" && stristr(strip_tags($CONTENT),$KEY2))
       $FLAG++;
    if($KEY3!="" && stristr(strip_tags($CONTENT),$KEY3))
       $FLAG++;

    if($FLAG==0&&($KEY1!=""||$KEY2!=""||$KEY3!=""))
       continue;

   if($SUBJECT=="")
      $SUBJECT=csubstr(strip_tags($CONTENT),0,50).(strlen($CONTENT)>50?"...":"");

    $DIA_COUNT++;

    if($DIA_COUNT==1)
    {
?>

    <table width="95%" class="TableList">

<?
    }
    if($DIA_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center" width="100"><?=$DIA_DATE?></td>
      <td><a href="read.php?DIA_ID=<?=$DIA_ID?>&USER_NAME=<?=$USER_NAME?>"><?=$SUBJECT?></a></td>
      <td>
<?
      if($ATTACHMENT_NAME!="")
      {
         $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
         $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);

         $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
         for($I=0;$I<$ARRAY_COUNT-1;$I++)
         {
         	  $ATTACH_SIZE=attach_size($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]);
            $ATTACH_SIZE=number_format($ATTACH_SIZE,0, ".",",");
?>
       <img src="<?=MYOA_STATIC_SERVER?>/static/images/email_atta.gif" align="absmiddle"><a href="/inc/attach.php?ATTACHMENT_ID=<?=$ATTACHMENT_ID_ARRAY[$I]*3+2?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>"><?=$ATTACHMENT_NAME_ARRAY[$I]?></a>
       (<?=$ATTACH_SIZE?><?=_("字节")?>)
     	 <input type="button" value="<?=_("转存")?>" class="SmallButton" onClick="SaveFile('<?=$ATTACHMENT_ID_ARRAY[$I]*3+2?>','<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>');">&nbsp;

<?
           if(stristr($ATTACHMENT_NAME_ARRAY[$I],".doc")||stristr($ATTACHMENT_NAME_ARRAY[$I],".ppt")||stristr($ATTACHMENT_NAME_ARRAY[$I],".xls"))
           {
               $OFFICE_OP_CODE = urlencode(td_authcode("5:1", "ENCODE", md5($ATTACHMENT_NAME_ARRAY[$I])));
?>
	<input type="button" value="<?=_("阅读")?>" class="SmallButton" onClick="window.open('/module/OC/?ATTACHMENT_ID=<?=$ATTACHMENT_ID_ARRAY[$I]*3+2?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>&OP_CODE=<?=$OFFICE_OP_CODE?>&PRINT=1','<?=$ATTACHMENT_ID_ARRAY[$I]?>','menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1');">
<?
           }
           else if(is_media($ATTACHMENT_NAME_ARRAY[$I]))
           {
?>
	       <input type="button" value="<?=_("播放")?>" class="SmallButton" onClick="window.open('/module/mediaplayer/index.php?MEDIA_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>&MEDIA_URL=<?=urlencode("/inc/attach.php?ATTACHMENT_ID=".($ATTACHMENT_ID_ARRAY[$I]*3+2)."&ATTACHMENT_NAME=".urlencode($ATTACHMENT_NAME_ARRAY[$I]))?>','media<?=$ATTACHMENT_ID_ARRAY[$I]?>','menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1');">&nbsp;
<?
           }
           echo "<br>";
         }//for
      }//else
?>
      </td>
      <td nowrap align="center" width="60"><a href="comment.php?DIA_ID=<?=$DIA_ID?>&USER_ID=<?=$USER_ID?>"><?=_("点评")?></a></td>
    </tr>
<?
 }

 if($DIA_COUNT==0)
 {
   Message("",_("无符合条件的日志记录"));
 }
 else
 {
?>
   <thead class="TableHeader">
      <td nowrap align="center"><?=_("日期")?> <img border=0 src="<?=MYOA_STATIC_SERVER?>/static/images/arrow_down.gif" width="11" height="10"></td>
      <td nowrap align="center"><?=_("日志标题")?></td>
      <td nowrap align="center"><?=_("附件")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
   </thead>
   </table>
<?
 }
?>

<br>
<?
if($DIA_COUNT>0)
{
   $MSG_DIA_COUNT = sprintf(_("共 %d 篇日志"),$DIA_COUNT);
   Message("",$MSG_DIA_COUNT);
}
?>
<div align="center">
 <input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='user_diary.php?USER_ID=<?=$USER_ID?>&USER_NAME=<?=$USER_NAME?>';">
</div>

</body>
</html>
