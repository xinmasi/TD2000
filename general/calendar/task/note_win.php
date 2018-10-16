<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

  $query="select * from TASK where TASK_ID='$TASK_ID'";
  $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
  if($ROW=mysql_fetch_array($cursor))
  {
    $TASK_ID=$ROW["TASK_ID"];
    $TASK_NO=$ROW["TASK_NO"];
    $USER_ID=$ROW["USER_ID"];
    $MANAGER_ID=$ROW["MANAGER_ID"]; 
    $RATE=$ROW["RATE"];  
    if($RATE=="")  
       $RATE = 0;
    //if($USER_ID!=$_SESSION["LOGIN_USER_ID"])
    //   exit;

    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];

    if($BEGIN_DATE=="0000-00-00")
       $BEGIN_DATE=_("无");

    if($END_DATE=="0000-00-00")
       $END_DATE=_("无");

    $TASK_TYPE=$ROW["TASK_TYPE"];
    $TASK_STATUS=$ROW["TASK_STATUS"];
    $COLOR=$ROW["COLOR"];
    $IMPORTANT=$ROW["IMPORTANT"];
    $RATE=$ROW["RATE"];
    if($COLOR==0 || $COLOR=="")
    {
        $COLOR = "";
    }

    $SUBJECT=$ROW["SUBJECT"];
    $SUBJECT=str_replace("<","&lt",$SUBJECT);
    $SUBJECT=str_replace(">","&gt",$SUBJECT);
    $SUBJECT=stripslashes($SUBJECT);

    $CONTENT=$ROW["CONTENT"];
    $CONTENT=str_replace("<","&lt",$CONTENT);
    $CONTENT=str_replace(">","&gt",$CONTENT);
    $CONTENT=stripslashes($CONTENT);

    $TITLE=csubstr($SUBJECT,0,10);
  }
  
$HTML_PAGE_TITLE = $TITLE;
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/calendar/css/calendar_note.css">
<script>
function del_task_new(TASK_ID,from)
{
   var url='../task/delete.php?TASK_ID='+TASK_ID+'&FROM='+from;
   if(window.confirm("<?=_("删除后将不可恢复，确定删除吗？")?>"))
      location=url;
}
</script>

<body bgcolor="#FFFFCC" topmargin="5" style="background:none;">
<div id="main">
    <div class="calendar-note-border-color<?=$COLOR?>" style="height:4px;"></div>
    <div class="calendar-note-head">
    <h1><?=$TITLE?></h1> 
    </div>
	<div class="calendar-note-content">	
	    <!--<div class="calendar-note"><label class="calendar-note-font"><?=_("任务标题：")?></label><div style="line-height:30px"><?=$SUBJECT?></div></div>-->
	    
		<div class="calendar-note"><label class="calendar-note-font"><?=_("开始时间：")?></label><span style="color: #427297;font-family: arial; "><?=$BEGIN_DATE?></span></div>
		<div class="calendar-note"><label class="calendar-note-font"><?=_("结束时间：")?></label><span style="color: #427297;font-family: arial; "><?=$END_DATE?></span></div>
		<? if($RATE!="")
		{
		?>
		<div class="calendar-note"><label class="calendar-note-font"><?=_("完成：")?></label><?=$RATE ?>%</div>
		<!--<div class="calendar-note"><label class="calendar-note-font"><?=_("下次开始时间：")?></label><?=$M_TOPIC?></div>-->		 
	    <? }?>
	    <div class="calendar-note" style="min-height:150px;"><label class="calendar-note-font"><?=_("详细内容：")?></label><span class="calendar-detail-content"><?=$CONTENT?></span></div>
	</div>
</div>

</body>
</html>
