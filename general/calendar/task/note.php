<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
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
    $WORK_PLAN_ID=$ROW["WORK_PLAN_ID"];
    if($RATE=="")
        $RATE = 0;
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
    $SUBJECT=$ROW["SUBJECT"];
    $SUBJECT=str_replace("<","&lt",$SUBJECT);
    $SUBJECT=str_replace(">","&gt",$SUBJECT);
    $SUBJECT=stripslashes($SUBJECT);
    $CONTENT=$ROW["CONTENT"];
    if($COLOR==0 || $COLOR=="")
    {
        $COLOR = "";
    }
    if($WORK_PLAN_ID==0)
    {
        $CONTENT=nl2br($CONTENT);
        $BUT="";
    }
    else
    {
        $CONTENT="<p>".$CONTENT."</p>";
        $BUT="";
    }
    $TITLE=csubstr($SUBJECT,0,10);
}
if(mysql_num_rows($cursor)==0)
{
    Message("",_("该任务已被删除"));
    exit;
}

//修改事务提醒状态--yc
update_sms_status('5',$TASK_ID);
  
$HTML = '<div class="small" style="text-align:left;height:250px;overflow:auto;white-space:normal;word-break:break-all;">';
$HTML.='<div style=float:right;margin-right:30px;><img src="'.MYOA_STATIC_SERVER.'/static/images/cal.png" style="width:64px;height:64px;"></div>';
$HTML.='<b>'.$SUBJECT.'</b><br>';
$HTML.= _("开始日期:").$BEGIN_DATE.'';
$HTML.= _("结束日期:").$END_DATE.'<br>'._("完成:").$RATE.'%';
$HTML.='<hr style="margin: 0px;"><span class="calendar-detail-content">';
$HTML.= $CONTENT.'</span>';
$HTML.= '</div>';
if($FROM==1 &&($MANAGER_ID==$_SESSION["LOGIN_USER_ID"]||($MANAGER_ID==""&&$USER_ID==$_SESSION["LOGIN_USER_ID"]) || $_SESSION["LOGIN_USER_PRIV"]==1))
{
   $FLAG=1;
   $FALG_STATU='<input type="button" value="'._("修改").'" class="btn" onclick=window.open("../info/task_edit.php?TASK_ID='.$TASK_ID.'&FROM='.$FROM.'&IS_MAIN='.$IS_MAIN.'","oa_sub_window","height=480,width=670,status=0,toolbar=no,menubar=no,location=no,left=300,top=100,scrollbars=yes,resizable=yes");>&nbsp;&nbsp;<input type="button" value="'._("删除").'" class="btn btn-danger" onclick="del_task_new(\''.$TASK_ID.'\',1,\''.$FLAGS.'\',\''.$IS_MAIN.'\')">&nbsp;&nbsp;';
}
else
{
   $FLAG=0;
   $FALG_STATU='';
}

if($AJAX == "1")
{
   ob_end_clean();
   echo $HTML;
   echo $BUT.'<div  class="footer" align="center" style="float:bottom;margin-bottom:0px;"><input type="button" value="'._("详情").'" title="'._("生成便签窗口").'" class="btn btn-info" onclick="task_note(\''.$TASK_ID.'\',\''.$IS_MAIN.'\')">&nbsp;&nbsp;'."$FALG_STATU".'<input type="button" value="'._("关闭").'" class="btn" onclick="HideDialog(\'form_div\');"></div>';
   exit;
}
if($FROM==1)
{
	 ob_end_clean();
   echo $HTML;
   echo '</div>';
   exit;
	
}
  
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/calendar/css/calendar_note.css">

<script>
function del_task_new_old(TASK_ID,from)
{
   var url='../task/delete.php?TASK_ID='+TASK_ID+'&FROM='+from;
   if(window.confirm("<?=_("删除后将不可恢复，确定删除吗？")?>"))
      location=url;
}
</script>

<body>
<div id="main">
    <div class="calendar-note-border-color<?=$COLOR?>" style="height:4px;"></div>
    <div class="calendar-note-head">
    <h1><?=$SUBJECT?><b style="font-size: 12px; margin-left: 20px; padding: 4px; border-radius: 4px;"><?=$OVER_STATUS1?></b></h1> 
    </div>
	<div class="calendar-note-content">

		<div class="calendar-note"><label class="calendar-note-font"><?=_("开始时间：")?></label><span style="color: #427297;font-family: arial; "><?=$BEGIN_DATE?></span></div>
		<div class="calendar-note"><label class="calendar-note-font"><?=_("结束时间：")?></label><span style="color: #427297;font-family: arial; "><?=$END_DATE?></span></div>
	
        <div class="calendar-note"><label class="calendar-note-font"><?=_("完成：")?></label><?=$RATE?>%</div>      
        <div class="calendar-note" style="min-height:150px;">
            <label class="calendar-note-font"><?=_("任务详情：")?></label>
            <span class="calendar-note-desc calendar-detail-content">
                <?=$CONTENT?>
            </span>
        </div>
		
	</div>
    <?
    if($FROM==1 &&($MANAGER_ID==$_SESSION["LOGIN_USER_ID"]||($MANAGER_ID==""&&$USER_ID==$_SESSION["LOGIN_USER_ID"]) || $_SESSION["LOGIN_USER_PRIV"]==1))
    {
    ?>
    <center>
    <button type="button" class="btn" onClick="window.open('../info/task_edit.php?TASK_ID=<?=$TASK_ID?>&FROM=<?=$FROM?>','oa_sub_window','height=420,width=510,status=0,toolbar=no,menubar=no,location=no,left=300,top=200,scrollbars=yes,resizable=yes');"><?=_("修改")?></button>	<button type="button" class="btn" onclick="del_task_new(<?=$TASK_ID?>,1,<?=$FLAGS?>)" class="btn"><?=_("删除")?></button>
    </center>	
    <?	
    }
    ?>
</div>

</body>
</html>
