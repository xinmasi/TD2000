<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("进度日志");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>


<body class="bodycolor">
<?
$CUR_DATE=date("Y-m-d",time());

$query = "SELECT * from PROJ_TASK where TASK_ID='$TASK_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{

   $TASK_NAME=$ROW['TASK_NAME'];

   $TASK_START_TIME =$ROW['TASK_START_TIME'];
   $TASK_END_TIME=$ROW['TASK_END_TIME'];
   $MANAGER=$ROW["MANAGER"];
   $PARTICIPATOR=$ROW["PARTICIPATOR"];
   $SUSPEND_FLAG=$ROW["SUSPEND_FLAG"];
   $BEGIN_DATE=$ROW["BEGIN_DATE"];
   $END_DATE=$ROW["END_DATE"];
   $TASK_STATUS=$ROW["TASK_STATUS"];
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
 <tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/project.gif" align="absmiddle"><span class="big3"> <?=_("进度日志详情")?>(<?=$TASK_NAME?> [<?=$TASK_START_TIME?><?=_("至")?><?=$TASK_END_TIME?>])</span>
  </td>
 </tr>
</table>
<?

$query1 = "SELECT TASK_PERCENT_COMPLETE from PROJ_TASK where TASK_ID='$TASK_ID'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW1=mysql_fetch_array($cursor1))
   $PERCENT_MAX=$ROW1["TASK_PERCENT_COMPLETE"];

$query = "SELECT LOG_ID,LOG_TIME,PERCENT,LOG_USER,LOG_CONTENT,ATTACHMENT_ID,ATTACHMENT_NAME from PROJ_TASK_LOG where TASK_ID='$TASK_ID' order by LOG_USER,LOG_TIME asc";
$cursor=exequery(TD::conn(),$query);
$LOG_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $LOG_COUNT++;
  $LOG_ID1=$ROW["LOG_ID"];
	$LOG_TIME1=$ROW["LOG_TIME"];
	$LOG_CONTENT1=$ROW["LOG_CONTENT"];
	$PERCENT1 =$ROW["PERCENT"];
	$LOG_USER1=$ROW["LOG_USER"];

  $ATTACHMENT_ID1=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME1=$ROW["ATTACHMENT_NAME"];

  $query1 = "SELECT * from USER where USER_ID='$LOG_USER1'";
  $cursor1= exequery(TD::conn(),$query1);
  if($ROW1=mysql_fetch_array($cursor1))
     $USER_NAME=$ROW1["USER_NAME"];

  if($LOG_COUNT==1)
	{
?>
<table class="TableList" width="95%"  align="center">
   <tr class="TableHeader">
     <td nowrap align="center"><?=_("任务负责人")?></td>
     <td nowrap align="center"><?=_("内容")?></td>
     <td nowrap align="center"><?=_("附件")?></td>
     <td nowrap align="center"><?=_("日志时间")?></td>
     <td nowrap align="center"><?=_("进度百分比")?></td>
     <td nowrap align="center"><?=_("操作")?></td>
   </tr>
<?
  }

  if($LOG_COUNT%2==1)
     $TableLine="TableLine1";
  else
     $TableLine="TableLine2";

?>
  <tr class="<?=$TableLine?>">
     <td nowrap align="center"><?=$USER_NAME?></td>
  	 <td align="left" style="table-layout:fixed;word-break:break-all;"><?=$LOG_CONTENT1?></td>
     <td nowrap align="left"><?=attach_link($ATTACHMENT_ID1,$ATTACHMENT_NAME1,0,1,0)?></td>
     <td nowrap align="center"><?=$LOG_TIME1?></td>
     <td nowrap align="center"><?=$PERCENT1?>%</td>
     <td nowrap align="center">-</td>
  </tr>

<?
} //while

if($LOG_COUNT==0)
{
   Message("",_("无进度日志"));
}
else
{
?>
</table>
<?
}

?>
</form>
</body>
</html>