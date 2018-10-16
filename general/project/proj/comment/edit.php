<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("修改批注");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"><?=_("修改批注")?></span>
    </td>
  </tr>
</table>
<br>

<table class="TableBlock" width="95%" align="center" >
  <form action="update.php"  method="post" name="form1">  
   <tr>
    <td nowrap class="TableContent" width="90"><?=_("批注时间：")?></td>
<?
$CUR_TIME=date("Y-m-d H:i:s",time());

$query = "SELECT * from PROJ_COMMENT where COMMENT_ID='$COMMENT_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))		
{
 	 $CONTENT=$ROW["CONTENT"];
 	 $WRITE_TIME=$ROW["WRITE_TIME"]; 	 	
}	
?>
    <td class="TableData">
      <input type="text" name="WRITE_TIME" size="19" readonly maxlength="100" class="BigStatic" value="<? if($WRITE_TIME=="0000-00-00 00:00:00")echo $CUR_TIME;else echo $WRITE_TIME;?>">       
    </td>
   </tr>
   <tr>
     <td nowrap class="TableContent"> <?=_("批注内容：")?></td>
     <td class="TableData" colspan="1">
       <textarea name="CONTENT" class="BigInput" cols="80" rows="10"><?=$CONTENT?></textarea>
     </td>
   </tr> 
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
      <input type="hidden" value="<?=$COMMENT_ID?>" name="COMMENT_ID">
      <input type="hidden" value="<?=$PROJ_ID?>" name="PROJ_ID">
      <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
      <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="history.back();">
    </td>
  </form>
</table>

</body>
</html>