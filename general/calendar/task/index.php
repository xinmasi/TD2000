<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$CUR_DATE_T=date("Y-m-d");
//2013-4-11 主服务查询
if($IS_MAIN==1)
	$QUERY_MASTER=true;
else
   $QUERY_MASTER="";
$RATESTR="";

$TASK_STATUS_GET = $_GET['TASK_STATUS'];
if($TASK_STATUS_GET == "1")
{
   $RATESTR.=" and TASK_STATUS='1' ";
   $STATUS_DESC=_("未开始");
}
else if($TASK_STATUS_GET == "2")
{
   $RATESTR.=" and TASK_STATUS='2' ";
   $STATUS_DESC=_("进行中");
}
else if($TASK_STATUS_GET == "3")
{
   $RATESTR.=" and TASK_STATUS='3' ";
   $STATUS_DESC=_("已完成");
}
else if($TASK_STATUS_GET == "4")
{
   $RATESTR.=" and TASK_STATUS='4' ";
   $STATUS_DESC=_("等待其他人");
}
else if($TASK_STATUS_GET == "5")
{
   $RATESTR.=" and TASK_STATUS='5' ";
   $STATUS_DESC=_("已推迟");
}
else
{
   $RATESTR="";
   $STATUS_DESC=_("全部");
}

$HTML_PAGE_TITLE = _("我的任务");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/calendar/css/calendar_person.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/sort_table.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function delete_task(TASK_ID)
{
 msg='<?=_("确认要删除该任务吗？")?>';
 if(window.confirm(msg))
 {
  URL="delete.php?TASK_ID=" + TASK_ID+"&PAGE_START=<?=$PAGE_START?>";
  window.location=URL;
 }
}

function my_note(TASK_ID)
{
  my_left=document.body.scrollLeft+400;
  my_top=document.body.scrollTop+300;

  window.open("note.php?TASK_ID="+TASK_ID,"task_win"+TASK_ID,"height=170,width=180,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+ my_top +",left="+ my_left +",resizable=yes");
}
function check_all()
{
 for (i=0;i<document.getElementsByName("email_select").length;i++)
 {
   if(document.getElementsByName("allbox")[0].checked)
      document.getElementsByName("email_select").item(i).checked=true;
   else
      document.getElementsByName("email_select").item(i).checked=false;
 }

 if(i==0)
 {
   if(document.getElementsByName("allbox")[0].checked)
      document.getElementsByName("email_select").checked=true;
   else
      document.getElementsByName("email_select").checked=false;
 }
}

function check_one(el)
{
   if(!el.checked)
      document.getElementsByName("allbox")[0].checked=false;
}
function get_checked()
{
  checked_str="";
  for(i=0;i<document.getElementsByName("email_select").length;i++)
  {

      el=document.getElementsByName("email_select").item(i);
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName("email_select");
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
  }
  return checked_str;
}
function delete_mail()
{
  delete_str=get_checked();
  if(delete_str=="")
  {
     alert("<?=_("要删除任务，请至少选择其中一条。")?>");
     return;
  }

  msg='<?=_("确认要删除所选任务吗？")?>';
  if(window.confirm(msg))
  {
    url="delete.php?TASK_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
    location=url;
  }
}
function ShowDialog(id,vTopOffset)
{
   if(typeof arguments[1] == "undefined")
     vTopOffset = 90;
     
   var bb=(document.compatMode && document.compatMode!="BackCompat") ? document.documentElement : document.body;
   $("overlay").style.width = Math.max(parseInt(bb.scrollWidth),parseInt(bb.offsetWidth))+"px";
   $("overlay").style.height = Math.max(parseInt(bb.scrollHeight),parseInt(bb.offsetHeight))+"px";

   $("overlay").style.display = 'block';
   $(id).style.display = 'block';

   $(id).style.left = ((bb.offsetWidth - $(id).offsetWidth)/2)+"px";
   $(id).style.top  = (vTopOffset)+"px";//(vTopOffset + bb.scrollTop)+
}
function set_task_status(status)
{
  document.form1.TASK_STATUS.value=status;
  document.form1.submit();
}
</script>
<style>
.mytaskstatus{
   min-width: 115px;
}
.mytaskstatus li a{
   text-align: left;
}
#taskstatusicon{
   margin-left: 5px;
}
</style>

<body class="bodycolor" onLoad="SortTable('bSortTable');">
<table border="0" width="100%" height="40" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big" style="width:185px;"><span class="big3" style="margin-right:10px"> <?=_("任务管理")?></span>
    	 <button type="button" onClick="javascript:window.location.href='index_list.php'" class="btn btn-info"><?=_("传统视图")?></button>
    </td>
    <td align="right">
        <div class="btn-group">
            <button class="btn dropdown-toggle" data-toggle="dropdown"><?=$STATUS_DESC?><span class="caret" id="taskstatusicon"></span></button>
            <ul class="dropdown-menu mytaskstatus" role="menu">
                <li><a href="javascript:set_task_status('0');"><?=_("全部")?></a></li>
                <li><a href="javascript:set_task_status('1');"><?=_("未开始")?></a></li>
                <li><a href="javascript:set_task_status('2');"><?=_("进行中")?></a></li>
                <li><a href="javascript:set_task_status('3');"><?=_("已完成")?></a></li>
                <li><a href="javascript:set_task_status('4');"><?=_("等待其他人")?></a></li>
                <li><a href="javascript:set_task_status('5');"><?=_("已推迟")?></a></li>
            </ul>
        </div>
        <button type="button" class="btn" onClick="location='task_edit.php?PAGE_START=<?=$PAGE_START?>';" title="<?=_("新建任务")?>"><?=_("新建任务")?></button>
    </td>
  </tr>
</table>
<form name="form1" action="<?=$_SERVER["SCRIPT_NAME"]?>">
    <input type="hidden" value="" name="TASK_STATUS">
<table  width="100%" id="taskContent" class="table table-bordered" align="center">
 <tr onClick="if(option1.style.display=='none') option1.style.display=''; else option1.style.display='none';" title="<?=_("点击展开/收缩选项")?>" align="left">
    <td nowrap class="taskTitle" colspan="7" style="cursor:pointer;"><img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif" align="absMiddle" ><?=_("今日")?>(<?=$CUR_DATE_T ?> <?=get_week($CUR_DATE_T)?>)</td>
 </tr>
   <tbody id="option1" style="display:'';">
<?

 //============================ 显示任务 =======================================
 $query = "SELECT * from TASK where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and BEGIN_DATE<='$CUR_DATE_T' and (END_DATE>='$CUR_DATE_T' or END_DATE='0000-00-00') ".$RATESTR." order by TASK_ID desc ";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 $TASK_COUNT=0;

 while($ROW=mysql_fetch_array($cursor))
 {
    $TASK_COUNT++;

    $TASK_ID=$ROW["TASK_ID"];
    $TASK_NO=$ROW["TASK_NO"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];

    if($BEGIN_DATE=="0000-00-00")
       $BEGIN_DATE="";

    if($END_DATE=="0000-00-00")
       $END_DATE="";
     
     if($BEGIN_DATE==$CUR_DATE_T && $END_DATE==$CUR_DATE_T)
    {
      $DATE_NAME=$CUR_DATE_T;
    }
    else
    {
    	$DATE_NAME=_("跨天任务");
    }
    $TASK_TYPE=$ROW["TASK_TYPE"];
    $TASK_STATUS=$ROW["TASK_STATUS"];
    $COLOR=$ROW["COLOR"];
    $IMPORTANT=$ROW["IMPORTANT"];
    $RATE=intval($ROW["RATE"]);
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $SUBJECT=$ROW["SUBJECT"];
    $SUBJECT=td_htmlspecialchars($SUBJECT);

    $CONTENT=$ROW["CONTENT"];
    $CONTENT=td_htmlspecialchars($CONTENT);
    if($COLOR==0)
    {
        $COLOR="";
    }
    
    switch($TASK_STATUS)
    {
       case "1": $STATUS_DESC=_("未开始");break;
       case "2": $STATUS_DESC=_("进行中");break;
       case "3": $STATUS_DESC=_("已完成");break;
       case "4": $STATUS_DESC=_("等待其他人");break;
       case "5": $STATUS_DESC=_("已推迟");break;
    }

    switch($TASK_TYPE)
    {
       case "1": $TYPE_DESC=_("工作");break;
       case "2": $TYPE_DESC=_("个人");break;
       case "3": $TYPE_DESC=_("指派");break;
    }

    if($TASK_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";
    
?>
    <tr class="">
    <td title="<?=$BEGIN_DATE?>-<?=$END_DATE?>"><?=$DATE_NAME?></td>
      <td>
      <a href="javascript:my_task_note(<?=$TASK_ID?>,1,2);" class="CalLevel<?=$IMPORTANT?>" title="<?=cal_level_desc($IMPORTANT)?>"><?=csubstr(strip_tags($SUBJECT),0,100);?> <? if(strlen($SUBJECT)>100)echo "...";?></a>
      </td>
    	<td nowrap align="center"><?=$STATUS_DESC?></td>
      <td nowrap align="right"><div style="background:#00AA00;width:<?=$RATE?>px;border:1px solid;margin-top:2px;float:left;"></div><div style="float:right;">&nbsp;<?=$RATE?>%</div></td>
      <td nowrap align="center"><?=$TYPE_DESC?></td>
      <td nowrap><span class="CalColor<?=$COLOR?> smallColor"></span></td>
      <td nowrap align="center">
      	<? if($MANAGER_ID==""|| $MANAGER_ID==$_SESSION["LOGIN_USER_ID"] ||$_SESSION["LOGIN_USER_PRIV"]==1){?>
        <a href="task_edit.php?TASK_ID=<?=$TASK_ID?>&PAGE_START=<?=$PAGE_START?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("修改")?></a>
        <a href="javascript:delete_task(<?=$TASK_ID?>);"> <?=_("删除")?></a>
        <? }
        if($MANAGER_ID!="" && $MANAGER_ID!=$_SESSION["LOGIN_USER_ID"]){?>
          <a href="edit_other.php?TASK_ID=<?=$TASK_ID?>&PAGE_START=<?=$PAGE_START?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("修改")?></a>
          <?}?>
      </td>
    </tr>
<?
 }

//已延迟的任务   所有不是已完成的的任务并且结束时间小于当前日期的
if($TASK_STATUS_GET != 3)
{   
    $query = "SELECT * from TASK where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and BEGIN_DATE<='$CUR_DATE_T' and (END_DATE<'$CUR_DATE_T' and END_DATE!='0000-00-00') and TASK_STATUS!='3' ".$RATESTR." order by TASK_ID desc ";
    $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
    $TASK_COUNT2=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $TASK_COUNT2++;
        
        $TASK_ID        = $ROW["TASK_ID"];
        $TASK_NO        = $ROW["TASK_NO"];
        $BEGIN_DATE     = $ROW["BEGIN_DATE"];
        $END_DATE       = $ROW["END_DATE"];
        
        if($BEGIN_DATE=="0000-00-00")
        {
            $BEGIN_DATE="";
        }
        
        if($END_DATE=="0000-00-00")
        {
            $END_DATE="";
        }
        
        if($BEGIN_DATE==$CUR_DATE_T && $END_DATE==$CUR_DATE_T)
        {
            $DATE_NAME=$CUR_DATE_T;
        }
        else
        {
            $DATE_NAME=_("跨天任务");
        }
        
        $TASK_TYPE      = $ROW["TASK_TYPE"];
        $TASK_STATUS    = $ROW["TASK_STATUS"];
        $COLOR          = $ROW["COLOR"];
        $IMPORTANT      = $ROW["IMPORTANT"];
        $RATE           = intval($ROW["RATE"]);
        $MANAGER_ID     = $ROW["MANAGER_ID"];
        $SUBJECT        = $ROW["SUBJECT"];
        $SUBJECT        = td_htmlspecialchars($SUBJECT);
        
        if($COLOR==0)
        {
            $COLOR="";
        }
        
        $CONTENT=$ROW["CONTENT"];
        $CONTENT=td_htmlspecialchars($CONTENT);
        
        switch($TASK_STATUS)
        {
            case "1": $STATUS_DESC=_("未开始");break;
            case "2": $STATUS_DESC=_("进行中");break;
            case "3": $STATUS_DESC=_("已完成");break;
            case "4": $STATUS_DESC=_("等待其他人");break;
            case "5": $STATUS_DESC=_("已推迟");break;
        }
        
        switch($TASK_TYPE)
        {
            case "1": $TYPE_DESC=_("工作");break;
            case "2": $TYPE_DESC=_("个人");break;
            case "3": $TYPE_DESC=_("指派");break;
        }
        
        if($TASK_COUNT2%2==1)
        {
            $TableLine="TableLine1";
        }
        else
        {
            $TableLine="TableLine2";
        }

    
?>
    <tr class="">
        <td title="<?=$BEGIN_DATE?>-<?=$END_DATE?>"><?=$DATE_NAME?></td>
        <td>
            <img src='<?=MYOA_STATIC_SERVER?>/static/images/sync_error.png' title="<?=_('已延迟')?>"><a href="javascript:my_task_note(<?=$TASK_ID?>,1,2);" class="CalLevel<?=$IMPORTANT?>" title="<?=cal_level_desc($IMPORTANT)?>"><?=csubstr(strip_tags($SUBJECT),0,100);?> <? if(strlen($SUBJECT)>100)echo "...";?></a>
        </td>
        <td nowrap align="center"><?=$STATUS_DESC?></td>
        <td nowrap align="right"><div style="background:#00AA00;width:<?=$RATE?>px;border:1px solid;margin-top:2px;float:left;"></div><div style="float:right;">&nbsp;<?=$RATE?>%</div></td>
        <td nowrap align="center"><?=$TYPE_DESC?></td>
        <td nowrap><span class="CalColor<?=$COLOR?> smallColor"></span></td>
        <td nowrap align="center">
        <?
        if($MANAGER_ID==""|| $MANAGER_ID==$_SESSION["LOGIN_USER_ID"] ||$_SESSION["LOGIN_USER_PRIV"]==1)
        {
        ?>
            <a href="task_edit.php?TASK_ID=<?=$TASK_ID?>&PAGE_START=<?=$PAGE_START?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("修改")?></a>
            <a href="javascript:delete_task(<?=$TASK_ID?>);"> <?=_("删除")?></a>
        <?
        }
        
        if($MANAGER_ID!="" && $MANAGER_ID!=$_SESSION["LOGIN_USER_ID"])
        {
        ?>
            <a href="edit_other.php?TASK_ID=<?=$TASK_ID?>&PAGE_START=<?=$PAGE_START?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("修改")?></a>
        <?
        }
        ?>
        </td>
    </tr>
<?
    }
}

if($TASK_COUNT==0 && $TASK_COUNT2==0)
{
    echo "<tr><td colspan=4 align=center><font size=3><B>"._("无任务")."</B></font> </td></tr>";
}

?>
</tbody>
 <tr onClick="if(option2.style.display=='none') option2.style.display=''; else option2.style.display='none';" title="<?=_("点击展开/收缩选项")?>" align="left">
    <td nowrap class="taskTitle" colspan="7" style="cursor:pointer;"><img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif" align="absMiddle" ><?=_("明日")?>(<?=date("Y-m-d",strtotime("+1 day"))?> <?=get_week(date("Y-m-d",strtotime("+1 day")))?>)</td>
 </tr>
   <tbody id="option2" style="display:'';">
<?
 $CUR_DATE=date("Y-m-d",strtotime("+1 day"));
 //============================ 显示任务 =======================================
 $query = "SELECT * from TASK where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and BEGIN_DATE<='$CUR_DATE' and (END_DATE>='$CUR_DATE' or END_DATE='0000-00-00') ".$RATESTR." order by TASK_ID desc ";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 $TASK_COUNT=0;

 while($ROW=mysql_fetch_array($cursor))
 {
    $TASK_COUNT++;

    $TASK_ID=$ROW["TASK_ID"];
    $TASK_NO=$ROW["TASK_NO"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];

    if($BEGIN_DATE=="0000-00-00")
       $BEGIN_DATE="";

    if($END_DATE=="0000-00-00")
       $END_DATE="";
     
     if($BEGIN_DATE==$CUR_DATE && $END_DATE==$CUR_DATE)
    {
      $DATE_NAME=$CUR_DATE;
    }
    else
    {
    	$DATE_NAME=_("跨天任务");
    }
    $TASK_TYPE=$ROW["TASK_TYPE"];
    $TASK_STATUS=$ROW["TASK_STATUS"];
    $COLOR=$ROW["COLOR"];
    $IMPORTANT=$ROW["IMPORTANT"];
    $RATE=intval($ROW["RATE"]);
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $SUBJECT=$ROW["SUBJECT"];
    $SUBJECT=td_htmlspecialchars($SUBJECT);

    $CONTENT=$ROW["CONTENT"];
    $CONTENT=td_htmlspecialchars($CONTENT);
    if($COLOR==0)
    {
        $COLOR="";
    }
    switch($TASK_STATUS)
    {
       case "1": $STATUS_DESC=_("未开始");break;
       case "2": $STATUS_DESC=_("进行中");break;
       case "3": $STATUS_DESC=_("已完成");break;
       case "4": $STATUS_DESC=_("等待其他人");break;
       case "5": $STATUS_DESC=_("已推迟");break;
    }

    switch($TASK_TYPE)
    {
       case "1": $TYPE_DESC=_("工作");break;
       case "2": $TYPE_DESC=_("个人");break;
       case "3": $TYPE_DESC=_("指派");break;
    }

    if($TASK_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";

    
?>
    <tr class="">
    <td title="<?=$BEGIN_DATE?>-<?=$END_DATE?>"><?=$DATE_NAME?></td>
      <td>
         <a href="javascript:my_task_note(<?=$TASK_ID?>,1,2);" class="CalLevel<?=$IMPORTANT?>" title="<?=cal_level_desc($IMPORTANT)?>"><?=csubstr(strip_tags($SUBJECT),0,100);?> <? if(strlen($SUBJECT)>100)echo "...";?></a>
      </td>
    	<td nowrap align="center"><?=$STATUS_DESC?></td>
      <td nowrap align="right"><div style="background:#00AA00;width:<?=$RATE?>px;border:1px solid;margin-top:2px;float:left;"></div><div style="float:right;">&nbsp;<?=$RATE?>%</div></td>
      <td nowrap align="center"><?=$TYPE_DESC?></td>
      <td nowrap><span class="CalColor<?=$COLOR?> smallColor"></span></td>
      <td nowrap align="center">
      	<? if($MANAGER_ID==""|| $MANAGER_ID==$_SESSION["LOGIN_USER_ID"]){?>
        <a href="task_edit.php?TASK_ID=<?=$TASK_ID?>&PAGE_START=<?=$PAGE_START?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("修改")?></a>
        <a href="javascript:delete_task(<?=$TASK_ID?>);"> <?=_("删除")?></a>
        <? }
        if($MANAGER_ID!="" && $MANAGER_ID!=$_SESSION["LOGIN_USER_ID"]){?>
          <a href="edit_other.php?TASK_ID=<?=$TASK_ID?>&PAGE_START=<?=$PAGE_START?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("修改")?></a>
          <?}?>
      </td>
    </tr>
<?
 }
 if(mysql_num_rows($cursor)==0)
{
	
	echo "<tr><td colspan=4 align=center><font size=3><B>"._("无任务")."</B></font> </td></tr>";
	
}
?>
</tbody>
<tr onClick="if(option3.style.display=='none') option3.style.display=''; else option3.style.display='none';" title="<?=_("点击展开/收缩选项")?>" align="left">
    <td nowrap class="taskTitle" colspan="7" style="cursor:pointer;"><img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif" align="absMiddle" ><?=_("更晚任务")?></td>
 </tr>
   <tbody id="option3" style="display:'none';">
<?
 $CUR_DATE=date("Y-m-d",strtotime("+2 day"));
 //============================ 显示任务 =======================================
 $query = "SELECT * from TASK where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'  and BEGIN_DATE<='$CUR_DATE' and (END_DATE>='$CUR_DATE' or END_DATE='0000-00-00') ".$RATESTR." order by TASK_ID desc ";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 $TASK_COUNT=0;

 while($ROW=mysql_fetch_array($cursor))
 {
    $TASK_COUNT++;

    $TASK_ID=$ROW["TASK_ID"];
    $TASK_NO=$ROW["TASK_NO"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];

    if($BEGIN_DATE=="0000-00-00")
       $BEGIN_DATE="";

    if($END_DATE=="0000-00-00")
       $END_DATE="";
     
     if($BEGIN_DATE==$CUR_DATE && $END_DATE==$CUR_DATE)
    {
      $DATE_NAME=$CUR_DATE;
    }
    else
    {
    	$DATE_NAME=_("跨天任务");
    }
    $TASK_TYPE=$ROW["TASK_TYPE"];
    $TASK_STATUS=$ROW["TASK_STATUS"];
    $COLOR=$ROW["COLOR"];
    $IMPORTANT=$ROW["IMPORTANT"];
    $RATE=intval($ROW["RATE"]);
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $SUBJECT=$ROW["SUBJECT"];
    $SUBJECT=td_htmlspecialchars($SUBJECT);

    $CONTENT=$ROW["CONTENT"];
    $CONTENT=td_htmlspecialchars($CONTENT);
    if($COLOR==0)
    {
        $COLOR="";
    }
    switch($TASK_STATUS)
    {
       case "1": $STATUS_DESC=_("未开始");break;
       case "2": $STATUS_DESC=_("进行中");break;
       case "3": $STATUS_DESC=_("已完成");break;
       case "4": $STATUS_DESC=_("等待其他人");break;
       case "5": $STATUS_DESC=_("已推迟");break;
    }

    switch($TASK_TYPE)
    {
       case "1": $TYPE_DESC=_("工作");break;
       case "2": $TYPE_DESC=_("个人");break;
       case "3": $TYPE_DESC=_("指派");break;
    }

    if($TASK_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";

    
?>
    <tr class="">
    <td title="<?=$BEGIN_DATE?>-<?=$END_DATE?>"><?=$DATE_NAME?></td>
      <td>
         <a href="javascript:my_task_note(<?=$TASK_ID?>,1,2);" class="CalLevel<?=$IMPORTANT?>" title="<?=cal_level_desc($IMPORTANT)?>"><?=csubstr(strip_tags($SUBJECT),0,100);?> <? if(strlen($SUBJECT)>100)echo "...";?></a>
      </td>
    	<td nowrap align="center"><?=$STATUS_DESC?></td>
      <td nowrap align="right"><div style="background:#00AA00;width:<?=$RATE?>px;border:1px solid;margin-top:2px;float:left;"></div><div style="float:right;">&nbsp;<?=$RATE?>%</div></td>
      <td nowrap align="center"><?=$TYPE_DESC?></td>
      <td nowrap><span class="CalColor<?=$COLOR?> smallColor"></span></td>
      <td nowrap align="center">
      	<? if($MANAGER_ID==""|| $MANAGER_ID==$_SESSION["LOGIN_USER_ID"]){?>
        <a href="task_edit.php?TASK_ID=<?=$TASK_ID?>&PAGE_START=<?=$PAGE_START?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("修改")?></a>
        <a href="javascript:delete_task(<?=$TASK_ID?>);"> <?=_("删除")?></a>
        <? }
        if($MANAGER_ID!="" && $MANAGER_ID!=$_SESSION["LOGIN_USER_ID"]){?>
          <a href="edit_other.php?TASK_ID=<?=$TASK_ID?>&PAGE_START=<?=$PAGE_START?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("修改")?></a>
          <?}?>
      </td>
    </tr>
<?
 }
 if(mysql_num_rows($cursor)==0)
{
	
	echo "<tr><td colspan=4 align=center><font size=3><B>"._("无任务")."</B></font> </td></tr>";
	
}
?>
</tbody>
<tr onClick="if(option5.style.display=='none') option5.style.display=''; else option5.style.display='none';" title="<?=_("点击展开/收缩选项")?>" align="left">
    <td nowrap class="taskTitle" colspan="7" style="cursor:pointer;"><img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif" align="absMiddle" ><?=_("更早任务")?></td>
 </tr>
   <tbody id="option5" style="display:none;">
<?
 $CUR_DATE=date("Y-m-d",strtotime("-1 day"));
 //============================ 显示任务 =======================================
 $query = "SELECT * from TASK where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and BEGIN_DATE<='$CUR_DATE' and (END_DATE<='$CUR_DATE' or END_DATE='0000-00-00') ".$RATESTR." order by TASK_ID desc limit 0,10";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 $TASK_COUNT=0;

 while($ROW=mysql_fetch_array($cursor))
 {
    $TASK_COUNT++;

    $TASK_ID=$ROW["TASK_ID"];
    $TASK_NO=$ROW["TASK_NO"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];

    if($BEGIN_DATE=="0000-00-00")
       $BEGIN_DATE="";

    if($END_DATE=="0000-00-00")
       $END_DATE="";
     
     if($BEGIN_DATE==$CUR_DATE && $END_DATE==$CUR_DATE)
    {
      $DATE_NAME=$CUR_DATE;
    }
    else
    {
    	$DATE_NAME=_("跨天任务");
    }
    $TASK_TYPE=$ROW["TASK_TYPE"];
    $TASK_STATUS=$ROW["TASK_STATUS"];
    $COLOR=$ROW["COLOR"];
    $IMPORTANT=$ROW["IMPORTANT"];
    $RATE=intval($ROW["RATE"]);
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $SUBJECT=$ROW["SUBJECT"];
    $SUBJECT=td_htmlspecialchars($SUBJECT);

    $CONTENT=$ROW["CONTENT"];
    $CONTENT=td_htmlspecialchars($CONTENT);
    if($COLOR==0)
    {
        $COLOR="";
    }
    if($TASK_STATUS!=3 &&$END_DATE< $CUR_DATE_T)
       $TASK_STATUS_DELAY=1;
    switch($TASK_STATUS)
    {
       case "1": $STATUS_DESC=_("未开始");break;
       case "2": $STATUS_DESC=_("进行中");break;
       case "3": $STATUS_DESC=_("已完成");break;
       case "4": $STATUS_DESC=_("等待其他人");break;
       case "5": $STATUS_DESC=_("已推迟");break;
    }

    switch($TASK_TYPE)
    {
       case "1": $TYPE_DESC=_("工作");break;
       case "2": $TYPE_DESC=_("个人");break;
       case "3": $TYPE_DESC=_("指派");break;
    }

    if($TASK_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableLine2";

    
?>
    <tr class="">
    <td title="<?=$BEGIN_DATE?>-<?=$END_DATE?>"><?=$DATE_NAME?></td>
      <td>
        <? if($TASK_STATUS_DELAY==1){?><img src='<?=MYOA_STATIC_SERVER?>/static/images/sync_error.png' title="<?=_('已延迟')?>"><?}?> <a href="javascript:my_task_note(<?=$TASK_ID?>,1,2);" class="CalLevel<?=$IMPORTANT?>" title="<?=cal_level_desc($IMPORTANT)?>"><?=csubstr(strip_tags($SUBJECT),0,100);?> <? if(strlen($SUBJECT)>100)echo "...";?></a>
      </td>
    	<td nowrap align="center"><?=$STATUS_DESC?></td>
      <td nowrap align="right"><div style="background:#00AA00;width:<?=$RATE?>px;border:1px solid;margin-top:2px;float:left;"></div><div style="float:right;">&nbsp;<?=$RATE?>%</div></td>
      <td nowrap align="center"><?=$TYPE_DESC?></td>
      <td nowrap><span class="CalColor<?=$COLOR?> smallColor"></span></td>
      <td nowrap align="center">
      	<? if($MANAGER_ID==""|| $MANAGER_ID==$_SESSION["LOGIN_USER_ID"]){?>
        <a href="task_edit.php?TASK_ID=<?=$TASK_ID?>&PAGE_START=<?=$PAGE_START?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("修改")?></a>
        <a href="javascript:delete_task(<?=$TASK_ID?>);"> <?=_("删除")?></a>
        <? }
        if($MANAGER_ID!="" && $MANAGER_ID!=$_SESSION["LOGIN_USER_ID"]){?>
          <a href="edit_other.php?TASK_ID=<?=$TASK_ID?>&PAGE_START=<?=$PAGE_START?>&IS_MAIN=<?=$IS_MAIN?>"> <?=_("修改")?></a>
          <?}?>
      </td>
    </tr>
   
<?
 }
 ?>
  <tr><td colspan=7 align=center class=TableLine><a href="list_more.php" target="_blank"><?=_("查看所有更早任务")?></a></td></tr>
 <?
 if(mysql_num_rows($cursor)==0)
{
	
	echo "<tr><td colspan=4 align=center><font size=3><B>"._("无任务")."</B></font> </td></tr>";
	
}
?>
</tbody>
</table>
</form>
<div id="overlay" ></div>
<div id="form_div" class="ModalDialog1">
  <div class="modal-header"><a class="operation" href="javascript:;"><button type="button" class="close" data-dismiss="modal" aria-hidden="true" onClick="HideDialog('form_div');">&times;</button></a>
  <h3><span id="title" class="title"><?=_("新建日程")?></span></h3>
  </div>
  <div id="form_body" class="modal-body">
     
  </div>
</div>
<iframe name="form_iframe" id="form_iframe" style="display:none;"></iframe>

</body>
</html>
