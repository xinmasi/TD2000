<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("进度日志");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script>
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function delete_diary(DETAIL_ID,PLAN_ID,ATTACHMENT_ID,ATTACHMENT_NAME)
{
   msg='<?=_("确认要删除该进度日志吗？")?>';
   if(window.confirm(msg))
   {
      URL="delete_diary.php?DETAIL_ID=" + DETAIL_ID+"&PLAN_ID=" + PLAN_ID +"&ATTACHMENT_ID=" + ATTACHMENT_ID+"&ATTACHMENT_NAME=" + URLSpecialChars(ATTACHMENT_NAME)+"&STATUS="+2;
      window.location=URL;
   }
}

function CheckForm()
{
  if(document.form1.PERCENT.value=="")
  {
   	 alert("<?=_("请估计你的工作进度")?>");
     return (false);
  }
  if(parseInt(document.form1.PERCENT.value) < 0 || parseInt(document.form1.PERCENT.value) > 100)
  {
   	 alert("<?=_("完成百分比值在0～100之间。")?>");
     return (false);
  }
  if(parseFloat(document.form1.PERCENT.value) < parseFloat(document.form1.PERCENT_MAX.value))
  {
   	 alert("<?=_("进度百分比数值不能小于上一次的数值")?>");
     return (false);
  }
  document.form1.OP.value="1";
  return (true);
}

function sendForm()
{
  document.form1.FLAG.value="1";
  if(CheckForm())
     document.form1.submit();
}

function upload_attach()
{
  if(CheckForm())
  {
     document.form1.OP.value="0";
     document.form1.FLAG.value="0";
     document.form1.submit();
  }
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
     URL="delete_attach.php?DETAIL_ID1=<?=$DETAIL_ID1?>&PLAN_ID=<?=$PLAN_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
     window.location=URL;
  }
}

function order_by(field,asc_desc)
{
  window.location="add_resource.php?AUTO_PERSON=<?=$AUTO_PERSON?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}

</script>



<body class="bodycolor">
<?
$CUR_DATE=date("Y-m-d",time());

$query = "SELECT * FROM WORK_PERSON WHERE AUTO_PERSON='$AUTO_PERSON'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{

	$PLAN_ID=$ROW['PLAN_ID'];
	$PUSER_ID=$ROW['PUSER_ID'];
	$PBEGEI_DATE=$ROW['PBEGEI_DATE'];
	$PEND_DATE=$ROW['PEND_DATE'];
	$PPLAN_CONTENT=$ROW['PPLAN_CONTENT'];
	$PUSE_RESOURCE=$ROW['PUSE_RESOURCE'];
	$ATTACHMENT_ID=$ROW['ATTACHMENT_ID'];
	$ATTACHMENT_NAME=$ROW['ATTACHMENT_NAME'];
 }
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
 <tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/work_plan.gif" width="22" height="18" align="absMiddle"><span class="big3"> <?=_("进度日志详情")?>(<?=$NAME?> <?=format_date($BEGIN_DATE1)?> - <? if($END_DATE1!="0000-00-00") echo format_date($END_DATE1);?>)</span>
  </td>
 </tr>
</table>
<?
if($DETAIL_ID1=="")
{
   $query1 = "SELECT MAX(PERCENT) AS PERCENT_M from WRESOURCE_DETAIL where   RPERSON_ID='$AUTO_PERSON'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $PERCENT_MAX=$ROW1["PERCENT_M"];
}
else
{
   $query1 = "SELECT MAX(PERCENT) AS PERCENT_M from WRESOURCE_DETAIL where  RPERSON_ID='$AUTO_PERSON'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $PERCENT_M=$ROW1["PERCENT_M"];

   $query1 = "SELECT MAX(PERCENT) AS PERCENT_CM from WRESOURCE_DETAIL where  RPERSON_ID='$AUTO_PERSON' and PERCENT < '$PERCENT_M'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $PERCENT_MAX=$ROW1["PERCENT_CM"];
}

$query = "SELECT AUTO_DETAIL,WRITE_TIME,WRITER, PROGRESS,PERCENT,ATTACHMENT_ID,ATTACHMENT_NAME FROM WRESOURCE_DETAIL WHERE RPERSON_ID='$AUTO_PERSON'"; 
//$query = "SELECT DETAIL_ID,WRITE_TIME,PROGRESS,PERCENT,WRITER,ATTACHMENT_ID,ATTACHMENT_NAME from WORK_DETAIL where TYPE_FLAG='0'and PLAN_ID='$PLAN_ID'";
if($FIELD=="")
   $query .= " order by WRITER,WRITE_TIME asc";
else
{
   $query .= " order by ".$FIELD;
   if($ASC_DESC=="1")
      $query .= " desc";
   else
      $query .= " asc";
}

$cursor=exequery(TD::conn(),$query);
$DETAIL_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $DETAIL_COUNT++;
  $DETAIL_ID=$ROW["AUTO_DETAIL"];
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
		
     if($ASC_DESC=="")
        $ASC_DESC="1";
     if($ASC_DESC=="0")
        $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
     else
        $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";
?>
<table class="TableList" width="95%"  align="center">
   <tr class="TableHeader">
     <td nowrap align="center" onclick="order_by('WRITER','<?if($FIELD=="WRITER") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("作者")?></u><?if($FIELD=="WRITER") echo $ORDER_IMG;?></td>
     <td nowrap align="center"><?=_("内容")?></td>
     <td nowrap align="center"><?=_("附件")?></td>
     <td nowrap align="center" onclick="order_by('WRITE_TIME','<?if($FIELD=="WRITE_TIME") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("日志时间")?></u><?if($FIELD=="WRITER") echo $ORDER_IMG;?></td>
     <td nowrap align="center"><?=_("进度百分比")?></td>
     <td nowrap align="center"><?=_("操作")?></td>
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
  	 <td align="left" style="word-break: break-all;word-wrap:break-word;"><?=$PROGRESS1?></td>
     <td nowrap align="left"><?=attach_link($ATTACHMENT_ID1,$ATTACHMENT_NAME1,0,1,1)?></td>
     <td nowrap align="center"><?=$WRITE_TIME1?></td>
     <td nowrap align="center"><?=$PERCENT1?>%</td>
     <td nowrap align="center">
<?
if($_SESSION["LOGIN_USER_ID"]==$WRITER1 or $_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
     	 <a href="edit_resource.php?DETAIL_ID=<?=$DETAIL_ID?>&PLAN_ID=<?=$PLAN_ID?>"> <?=_("修改")?></a>
       <a href="javascript:delete_diary('<?=$DETAIL_ID?>','<?=$AUTO_PERSON?>','<?=$ATTACHMENT_ID1?>','<?=$ATTACHMENT_NAME1?>');"> <?=_("删除")?></a>
<?
}
?>
     </td>
  </tr>

<?
} //while

if($DETAIL_COUNT==0)
{
   Message("",_("无进度日志"));
}
else
{
?>
</table>
<?
}

if($MY_FLAG!=1  && $HINT_FLAG==0)
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("添加进度日志")?></span>
    </td>
  </tr>
</table>

<?
$CUR_TIME=date("Y-m-d H:i:s",time());

if($DETAIL_ID1!="")
{
   $query = "SELECT * from WORK_DETAIL where DETAIL_ID='$DETAIL_ID1'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
	    $PROGRESS=$ROW["PROGRESS"];
	    $PERCENT =$ROW["PERCENT"];
	    $WRITER=$ROW["WRITER"];
   	  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
      $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   }
}

?>
<form action="resource_submit.php"  method="post" name="form1" enctype="multipart/form-data">
<table class="TableBlock"  width="95%" align="center" >
   <tr>
    <td nowrap class="TableContent" width="90"><?=_("当前时间：")?></td>
    <td class="TableData">
      <input type="text" name="WRITE_TIME" size="19" readonly maxlength="100" class="BigStatic" value="<?=$CUR_TIME?>">
    </td>
   </tr>
   <tr>
     <td nowrap class="TableContent"> <?=_("完成百分比：")?></td>
     <td class="TableData" colspan="1">
       <input type="text" name="PERCENT" size="2" class="BigInput" value="<?=$PERCENT?>"><font size="3"> %</font>  <?=_("上次进度值：")?><?if(is_null($PERCENT_MAX))echo "0";else echo $PERCENT_MAX;?>   <?=_("（注：估计完成量与总量的百分比）")?>
     </td>
   </tr>
   <tr>
     <td nowrap class="TableContent"> <?=_("进度详情：")?></td>
     <td class="TableData" colspan="1">
       <textarea name="PROGRESS" class="BigInput" cols="55" rows="5"><?=$PROGRESS?></textarea>
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
      <td nowrap class="TableContent"> <?=_("提醒：")?></td>
      <td class="TableData">
<?=sms_remind(12);?>
      </td>
    </tr>
    <!--  <tr>
      <td nowrap class="TableContent"><?=_("是否写入工作日志：")?></td>
      <td class="TableData">
      	<input type="checkbox" name="WRITE_IN_WORK" id="WRITE_IN_WORK">
      	(<?=_("注意：勾选会将进度详情写入工作日志中")?>)
      	</td>
    </tr>-->
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
      <input type="hidden" value="<?=$AUTO_PERSON?>" name="RPERSON_ID">
      <input type="hidden" value="<?=$PLAN_ID?>" name="PLAN_ID">
      <input type="hidden" name="OP" value="">
        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
      <input type="hidden" name="FLAG" value="">
      <input type="hidden" name="PERCENT_MAX" value="<?=$PERCENT_MAX?>">
      <input type="hidden" value="<?=$DETAIL_ID1?>" name="DETAIL_ID1">
      <input type="button" value="<?=_("确定")?>" class="BigButton" onclick="sendForm();">&nbsp;&nbsp;
<?
if($BACK_FLAG==1)
{
?>
      <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="history.back();">&nbsp;&nbsp;
<?
}
?>
      <input type="button" class="BigButton" value="<?=_("关闭")?>" onClick="window.close();" title="<?=_("关闭窗口")?>">&nbsp;&nbsp;
      <!--  <input type="button" value="<?=_("进度图")?>" class="BigButton" onClick="window.open('progress_map.php?PLAN_ID=<?=$PLAN_ID?>','','status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=0,top=0,resizable=yes');">-->
    </td>
</table>
</form>
<?
}
else
{
?>
<br>
<br>
<center>
      <input type="button" class="BigButton" value="<?=_("关闭")?>" onClick="window.close();" title="<?=_("关闭窗口")?>">&nbsp;&nbsp;
      <input type="button" value="<?=_("进度图")?>" class="BigButton" onClick="window.open('progress_map.php?PLAN_ID=<?=$PLAN_ID?>','','status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=0,top=0,resizable=yes');">
</center>
<?
}
?>
</body>
</html>