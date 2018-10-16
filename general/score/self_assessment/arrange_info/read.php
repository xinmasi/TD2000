<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("查看日志");
include_once("inc/header.inc.php");
?>





<?
 $query = "SELECT * from DIARY where DIA_ID='$DIA_ID'";
 $cursor= exequery(TD::conn(),$query,true);
 if($ROW=mysql_fetch_array($cursor))
 {
    $DIA_TIME=$ROW["DIA_TIME"];
    $DIA_DATE=$ROW["DIA_DATE"];
    $DIA_DATE=strtok($DIA_DATE," ");
    $DIA_TYPE=$ROW["DIA_TYPE"];

   $NOTAGS_CONTENT=$ROW["CONTENT"];
   if($ROW["COMPRESS_CONTENT"] == "")
   {
      $CONTENT=$NOTAGS_CONTENT;
   }
   else
   {
      $CONTENT=@gzuncompress($ROW["COMPRESS_CONTENT"]);
      if($CONTENT===FALSE)
         $CONTENT=$NOTAGS_CONTENT;
   }


    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

    if($DIA_TIME=="0000-00-00 00:00:00")
       $DIA_TIME="";
 }
?>

<body class="bodycolor">

<div align="center" class="Big1">
<b>[<?=$USER_NAME?> - <?=_("工作日志查询")?>]</b>
</div>
<br>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
<?
   $MSG = sprintf(_("查看日志（%s）"),$DIA_DATE);
?>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/diary.gif" WIDTH="18" HEIGHT="18" align="absmiddle"><span class="big3"> <?=$MSG?></span>
    </td>
  </tr>
</table>

<br>
  <table width="90%" align="center" class="TableBlock">
    <tr class="TableHeader">
      <td><?=_("日志类型：")?><?=get_code_name($DIA_TYPE,"DIARY_TYPE");?></td>
    </tr><?
if($ATTACHMENT_NAME!="")
{
?>
    <tr>
      <td class="TableData"><?=_("附件文件")?>:<br>
<?
   $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
   $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);
   $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
   for($I=0;$I<$ARRAY_COUNT;$I++)
   {
      if($ATTACHMENT_ID_ARRAY[$I]=="")
         continue;
     $ATTACH_SIZE=attach_size($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]);
     $ATTACH_SIZE=number_format($ATTACH_SIZE,0, ".",",");
?>
       <img src="<?=MYOA_STATIC_SERVER?>/static/images/email_atta.gif" align="absmiddle"><a href="/inc/attach.php?ATTACHMENT_ID=<?=$ATTACHMENT_ID_ARRAY[$I]*3+2?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>"><?=$ATTACHMENT_NAME_ARRAY[$I]?></a>
     	 <input type="button" value="<?=_("转存")?>" class="SmallButton" onClick="SaveFile('<?=$ATTACHMENT_ID_ARRAY[$I]*3+2?>','<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>');">&nbsp;
<?
     if(stristr($ATTACHMENT_NAME_ARRAY[$I],".doc")||stristr($ATTACHMENT_NAME_ARRAY[$I],".ppt")||stristr($ATTACHMENT_NAME_ARRAY[$I],".xls"))
     {
     	$OFFICE_OP_CODE = urlencode(td_authcode("5:1", "ENCODE", md5($ATTACHMENT_NAME_ARRAY[$I])));
?>
	     <input type="button" value="<?=_("阅读")?>" class="SmallButton" onClick="window.open('/module/OC?ATTACHMENT_ID=<?=$ATTACHMENT_ID_ARRAY[$I]*3+2?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>&OP_CODE=<?=$OFFICE_OP_CODE?>&PRINT=<?=$PRINT?>','<?=$ATTACHMENT_ID_ARRAY[$I]?>','menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1');">&nbsp;
<?
     }
     else if(is_media($ATTACHMENT_NAME_ARRAY[$I]))
     {
?>
	       <input type="button" value="<?=_("播放")?>" class="SmallButton" onClick="window.open('/module/mediaplayer/index.php?MEDIA_NAME=<?=urlencode($ATTACHMENT_NAME_ARRAY[$I])?>&MEDIA_URL=<?=urlencode("/inc/attach.php?ATTACHMENT_ID=".($ATTACHMENT_ID_ARRAY[$I]*3+2)."&ATTACHMENT_NAME=".urlencode($ATTACHMENT_NAME_ARRAY[$I]))?>','media<?=$ATTACHMENT_ID_ARRAY[$I]?>','menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1');">&nbsp;
<?
     }
?>
       (<?=$ATTACH_SIZE?><?=_("字节")?>)<br>
<?
  }
?>
      </td>
    </tr>
<?
}
?>
    <tr>
      <td class="TableData" height="100" valign="top">
      <font color="#0000FF">[<?=_("写日志时间：")?><?=$DIA_TIME?>]</font><br>
      <?=$CONTENT?>
<?
 $COUNT=0;
 $query = "SELECT * from DIARY_COMMENT where DIA_ID='$DIA_ID' order by SEND_TIME desc";
 $cursor= exequery(TD::conn(),$query,true);
 while($ROW=mysql_fetch_array($cursor))
 {
    $COUNT++;
    $COMMENT_ID=$ROW["COMMENT_ID"];
    $USER_ID=$ROW["USER_ID"];
    $SEND_TIME=$ROW["SEND_TIME"];
    $CONTENT=$ROW["CONTENT"];
    $CONTENT=str_replace("\"","'",$CONTENT);

    $query = "SELECT * from USER where USER_ID='$USER_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW1=mysql_fetch_array($cursor1))
       $USER_NAME1=$ROW1["USER_NAME"];

    if($COUNT==1)
    {
?>
      <br><br>
      <b>[<?=_("以下是点评内容")?>]</b><br>
<?
    }
?>
      <font color="#0000FF"><?=$USER_NAME1?>&nbsp;&nbsp;<?=$SEND_TIME?></font>&nbsp;&nbsp;
<?
if($USER_ID == $_SESSION["LOGIN_USER_ID"])
{
?>
      <a href="delete.php?COMMENT_ID=<?=$COMMENT_ID?>&DIA_ID=<?=$DIA_ID?>&USER_NAME=<?=$USER_NAME?>"><?=_("删除")?></a>
<?
}
?>
      <br>
      <?=$CONTENT?><br><br>
<?
}
?>
      </td>
    </tr>
    <tfoot align="center" class="TableFooter">
      <td>
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="history.back();">
      </td>
    </tfoot>
  </table>

</body>
</html>