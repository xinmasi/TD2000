<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("修改批注");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script>
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
     URL="delete_op_attach.php?DETAIL_ID1=<?=$DETAIL_ID?>&PLAN_ID=<?=$PLAN_ID?>&FLAG="+1+"&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
     window.location=URL;
  }
}   
</script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"><?=_("修改批注")?></span>
    </td>
  </tr>
</table>
<br>

<form action="update_opinion.php"  method="post" name="form1" enctype="multipart/form-data">  
<table class="TableBlock" width="95%" align="center" >
   <tr>
    <td nowrap class="TableContent" width="90"><?=_("批注时间：")?></td>
<?
$CUR_TIME=date("Y-m-d H:i:s",time());

$query = "SELECT * from WORK_DETAIL where DETAIL_ID='$DETAIL_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))		
{
 	 $PROGRESS=$ROW["PROGRESS"];
 	 $WRITE_TIME=$ROW["WRITE_TIME"]; 	 
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"]; 	 	
}	
?>
    <td class="TableData">
      <input type="text" name="WRITE_TIME" size="19" readonly maxlength="100" class="BigStatic" value="<? if($WRITE_TIME=="0000-00-00 00:00:00")echo $CUR_TIME;else echo $WRITE_TIME;?>">       
    </td>
   </tr>
   <tr>
     <td nowrap class="TableContent"> <?=_("批注内容：")?></td>
     <td class="TableData" colspan="1">
       <textarea name="PROGRESS" class="BigInput" cols="80" rows="16"><?=$PROGRESS?></textarea>
     </td>
   </tr> 
   <tr>
      <td nowrap class="TableContent"><?=_("附件文档：")?></td>
      <td nowrap class="TableData">
<?
      if($ATTACHMENT_ID=="")
         echo _("无附件");
      else
         echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,0,1,1,1,1,1,0,0);
?>
      </td>
    </tr>
    <tr height="25">
      <td nowrap class="TableContent"><?=_("附件选择：")?></td>
      <td class="TableData">
         <script>ShowAddFile();</script>
      </td>
    </tr>
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
      <input type="hidden" value="<?=$DETAIL_ID?>" name="DETAIL_ID">
      <input type="hidden" value="<?=$PLAN_ID?>" name="PLAN_ID">
      <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
      <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">      
      <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
      <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="history.back();">
    </td>
</table>
</form>

</body>
</html>