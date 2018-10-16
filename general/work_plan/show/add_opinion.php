<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("添加批注");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script>
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function delete_opinion(DETAIL_ID,PLAN_ID,ATTACHMENT_ID,ATTACHMENT_NAME)
{
   msg='<?=_("确认要删除该批注吗？")?>';
   if(window.confirm(msg))
   {
      URL="delete_opinion.php?DETAIL_ID=" + DETAIL_ID+"&PLAN_ID=" + PLAN_ID + "&ATTACHMENT_ID=" + ATTACHMENT_ID+"&ATTACHMENT_NAME=" + URLSpecialChars(ATTACHMENT_NAME);
      window.location=URL;
   }
}

</script>

<body class="bodycolor">
<?
$query = "SELECT * from WORK_PLAN where PLAN_ID='$PLAN_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{

   $NAME=$ROW['NAME'];

   $BEGIN_DATE1=$ROW['BEGIN_DATE'];
   $END_DATE1=$ROW['END_DATE'];
   $MANAGER=$ROW["MANAGER"];
   $CREATOR=$ROW["CREATOR"];
   $OPINION_LEADER=$ROW["OPINION_LEADER"];
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
 <tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/work_plan.gif" width="22" height="18" align="absMiddle"><span class="big3"> <?=_("领导批注记录")?>(<?=$NAME?> <?=format_date($BEGIN_DATE1)?> - <? if($END_DATE1!="0000-00-00") echo format_date($END_DATE1);?>)</span>
  </td>
 </tr>
</table>
<?
$query = "SELECT * from WORK_DETAIL where TYPE_FLAG='1'and PLAN_ID='$PLAN_ID' order by WRITER,WRITE_TIME asc";
$cursor=exequery(TD::conn(),$query);
$DETAIL_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{ 
  $DETAIL_COUNT++;		
  $DETAIL_ID=$ROW["DETAIL_ID"];  
	$WRITE_TIME1=$ROW["WRITE_TIME"];     			
	$PROGRESS1=$ROW["PROGRESS"];
	$PERCENT1 =$ROW["PERCENT"];
	$WRITER1=$ROW["WRITER"];
	
  $ATTACHMENT_ID1=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME1=$ROW["ATTACHMENT_NAME"];

  $PROGRESS1=str_replace("\n","<br>",$PROGRESS1);	
  $query1 = "SELECT * from USER where USER_ID='$WRITER1'";
  $cursor1= exequery(TD::conn(),$query1);	
  if($ROW1=mysql_fetch_array($cursor1)) 
     $USER_NAME=$ROW1["USER_NAME"]; 
     	
  if($DETAIL_COUNT==1)
	{
?>
<table class="TableList" width="95%" align="center">
   <tr class="TableHeader"> 	 
     <td nowrap align="center"><?=_("批注领导")?></td>
     <td nowrap align="center"><?=_("批注")?><?=_("内容")?></td>
     <td nowrap align="center"><?=_("附件")?></td>     
     <td nowrap align="center"><?=_("批注时间")?></td>
<?
if(find_id($OPINION_LEADER.$MANAGER.$CREATOR,$_SESSION["LOGIN_USER_ID"]))
{  
?>   
     <td nowrap align="center"><?=_("操作")?></td>
<?
}
?>
   </tr>
<?
  }
  
  if($DETAIL_COUNT%2==1)
     $TableLine="TableLine1";
  else
     $TableLine="TableLine2";   

?>
  <tr class="<?=$TableLine?>">
     <td nowrap align="center"><?=$USER_NAME?></td>
  	 <td style="word-break:break-all;" align="left"><?=$PROGRESS1?></td>
     <td nowrap align="center"><?=attach_link($ATTACHMENT_ID1,$ATTACHMENT_NAME1,0,1,1)?></td>  	 
     <td nowrap align="center" width="160"><?=$WRITE_TIME1?></td>
<?
if(find_id($OPINION_LEADER.$MANAGER.$CREATOR,$_SESSION["LOGIN_USER_ID"]))
{  
?>        
     <td nowrap align="center">
<?
if($_SESSION["LOGIN_USER_ID"]==$WRITER1 or $_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
     	 <a href="../show/edit_opinion.php?DETAIL_ID=<?=$DETAIL_ID?>&PLAN_ID=<?=$PLAN_ID?>"> <?=_("修改")?></a>
       <a href="javascript:delete_opinion('<?=$DETAIL_ID?>','<?=$PLAN_ID?>','<?=$ATTACHMENT_ID1?>','<?=$ATTACHMENT_NAME1?>');"> <?=_("删除")?></a>
<?
}
?>
     </td> 
<?
}
?>
  </tr>
  
<?
} //while

if($DETAIL_COUNT==0)
{
   Message("",_("无批注"));
}
else
{
?>
</table>
<?
}

if(find_id($OPINION_LEADER.$MANAGER.$CREATOR,$_SESSION["LOGIN_USER_ID"]))
{
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("领导批注")?></span>
    </td>
  </tr>
</table>

<form action="add_op_sub.php"  method="post" name="form1" enctype="multipart/form-data">  
<table class="TableBlock" width="95%"  align="center" >
   <tr>
    <td nowrap class="TableContent" width="90"><?=_("批注时间：")?></td>
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
?>
    <td class="TableData">
      <input type="text" name="WRITE_TIME" size="19" readonly maxlength="100" class="BigStatic" value="<?=$CUR_TIME?>">       
    </td>
   </tr>
   <tr>
     <td nowrap class="TableContent"> <?=_("批注内容：")?></td>
     <td class="TableData" colspan="1">
       <textarea name="PROGRESS" class="BigInput" cols="80" rows="8"></textarea>
     </td>
   </tr>
    <tr height="25">
      <td nowrap class="TableContent"><?=_("附件选择：")?></td>
      <td class="TableData">
        <script>ShowAddFile();</script>
      </td>
    </tr>   
    <tr>
      <td nowrap class="TableContent"> <?=_("提醒：")?></td>
      <td class="TableData">
<?=sms_remind(12);?>
      </td>
    </tr>       
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
      <input type="hidden" value="<?=$PLAN_ID?>" name="PLAN_ID">
      <input type="hidden" value="<?=$CUR_TIME?>" name="CUR_TIME">
      <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
      <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">      
      <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
      <input type="button" class="BigButton" value="<?=_("关闭")?>" onClick="window.close();" title="<?=_("关闭窗口")?>">
    </td>
</table>
</form>
<?
}
?>
</body>
</html>