<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$CUR_YEAR = date('Y');
$CUR_MON = date('m');
$CUR_DAY = date('d');

if($BTN_OP!="")
{
   $DATE=strtotime($BTN_OP,strtotime($YEAR."-".$MONTH."-".$DAY));
   $YEAR=date("Y",$DATE);
   $MONTH=date("m",$DATE);
   $DAY=date("d",$DATE);
}

if(!$YEAR)
   $YEAR = $CUR_YEAR;
if(!$MONTH)
   $MONTH = $CUR_MON;
if(!$DAY)
   $DAY = $CUR_DAY;

if(!checkdate($MONTH,$DAY,$YEAR))
{
   Message(_("错误"),_("日期不正确"));
   exit;
}

$DATE=strtotime($YEAR."-".$MONTH."-".$DAY);
$WEEK=date("W",$DATE);

$CUR_TIME=date("Y-m-d H:i:s",time());
$CONDITION_STR="";
if($_GET['OVER_STATUS']=="1")
{
   $CONDITION_STR.=" and OVER_STATUS='0' and CAL_TIME>'$CUR_TIME'";
   $STATUS_DESC="<font color='#0000FF'>"._("未开始")."</font>";
}
else if($_GET['OVER_STATUS']=="2")
{
   $CONDITION_STR.=" and OVER_STATUS='0' and CAL_TIME<='$CUR_TIME' and END_TIME>='$CUR_TIME'";
   $STATUS_DESC="<font color='#0000FF'>"._("进行中")."</font>";
}
else if($_GET['OVER_STATUS']=="3")
{
   $CONDITION_STR.=" and OVER_STATUS='0' and END_TIME<'$CUR_TIME'";
   $STATUS_DESC="<font color='#FF0000'>"._("已超时")."</font>";
}
else if($_GET['OVER_STATUS']=="4")
{
   $CONDITION_STR.=" and OVER_STATUS='1'";
   $STATUS_DESC="<font color='#00AA00'>"._("已完成")."</font>";
}
else
{
   $STATUS_DESC=_("全部");
}

$HTML_PAGE_TITLE = _("日程安排");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css">
<?
if(MYOA_IS_UN && find_id("zh-TW,en,", MYOA_LANG_COOKIE) && find_id(MYOA_FASHION_THEME, $_SESSION["LOGIN_THEME"]))
{
?>
   <link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/un_<?=MYOA_LANG_COOKIE?>.css" />
<?
}
?>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
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
    url='delete.php?CAL_ID='+delete_str+'&PAGE_START=<?=$PAGE_START?>&OVER_STATUS='+document.form1.OVER_STATUS.value+'&YEAR='+document.form1.YEAR.value+'&MONTH='+document.form1.MONTH.value+'&DAY='+document.form1.DAY.value;
    location=url;
  }
}
</script>
<body class="bodycolor">
<div class="PageHeader calendar_icon">
 <div class="header-left">
   <form name="form1" action="<?=$_SERVER["SCRIPT_NAME"]?>" style="margin-bottom:5px;">
   <input type="hidden" value="" name="BTN_OP">
   <input type="hidden" value="<?=$_GET['OVER_STATUS']?>" name="OVER_STATUS">
   <input type="hidden" value="<?=$YEAR?>" name="YEAR">
   <input type="hidden" value="<?=$MONTH?>" name="MONTH">
   <input type="hidden" value="<?=$DAY?>" name="DAY">
   <a id="status" href="javascript:;" class="dropdown" onClick="showMenu(this.id,'1');" hidefocus="true"><span><?=$STATUS_DESC?></span></a>&nbsp;
   <div id="status_menu" class="attach_div">
      <a href="javascript:set_status_index('');"><?=_("全部")?></a>
      <a href="javascript:set_status_index(1);" style="color:#0000FF;"><?=_("未开始")?></a>
      <a href="javascript:set_status_index(2);" style="color:#0000FF;"><?=_("进行中")?></a>
      <a href="javascript:set_status_index(3);" style="color:#FF0000;"><?=_("已超时")?></a>
      <a href="javascript:set_status_index(4);" style="color:#00AA00;"><?=_("已完成")?></a>
   </div>
   <a href="list_old.php" class="ToolBtn"><span><?=_("传统视图")?></span></a>
 </div>
 <div class="header-right">
 	<a href="query.php" class="ToolBtn"><span><?=_("查询")?></span></a>
   <a id="new" href="javascript:;" class="dropdown" onClick="showMenu(this.id,'1');" hidefocus="true"><span><?=_("新建")?></span></a>
    <a href="count.php" class="ToolBtn"><span><?=_("统计")?></span></a>&nbsp;
   <div id="new_menu" class="attach_div">
      <a href="javascript:new_cal('<?=$DATE?>','+1 days');" title="<?=_("创建新的日程，以便提醒自己")?>"><?=_("今日日程")?></a>
      <a href="javascript:new_diary('<?=date("Ymd",$DATE)?>');"><?=_("今日日志")?></a>
   </div>
   <a href="javascript:set_view('list');" class="calendar-view list-view" title="<?=_("列表视图")?>"></a>
   <a href="javascript:set_view('day');" class="calendar-view day-view" title="<?=_("日视图")?>"></a>
   <a href="javascript:set_view('index');" class="calendar-view week-view" title="<?=_("周视图")?>"></a>
   <a href="javascript:set_view('month');" class="calendar-view month-view" title="<?=_("月视图")?>"></a>
   </form>
 </div>
</div>
<?
 $CODE_NAME=array();
 $MANAGER=array();
 
?>
<table  width="90%" class="TableBlock" align="center">
 <tr onClick="if(option1.style.display=='none') option1.style.display=''; else option1.style.display='none';" title="<?=_("点击展开/收缩选项")?>" align="left">
    <td nowrap class="TableHeader" colspan="4" style="cursor:pointer;"><img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif" align="absMiddle" ><?=_("今日")?>(<?=date("Y-m-d",$DATE)?> <?=get_week(date("Y-m-d",$DATE))?>)</td>
 </tr>
   <tbody id="option1" style="display:'';">
 <?
 $CAL_COUNT=0;
 $DATE_NOW=date("Y-m-d");
 $query = "SELECT * from CALENDAR where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'".$CONDITION_STR."  and from_unixtime(CAL_TIME,'%Y-%m-%d') <= '$DATE_NOW' and from_unixtime(END_TIME,'%Y-%m-%d')>='$DATE_NOW' order by CAL_ID desc ";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
 	  $CAL_COUNT++;
 	  $CAL_ID=$ROW["CAL_ID"];
    $CAL_TIME=$ROW["CAL_TIME"];
    $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
    $END_TIME=$ROW["END_TIME"];
    $END_TIME=date("Y-m-d H:i:s",$END_TIME);
    $CAL_TYPE=$ROW["CAL_TYPE"];
    $CAL_LEVEL=$ROW["CAL_LEVEL"];
    $CONTENT=$ROW["CONTENT"];
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $OVER_STATUS=$ROW["OVER_STATUS"];  
    
    if(substr($CAL_TIME,0,10)==$DATE_NOW && substr($END_TIME,0,10)==$DATE_NOW)
    {
      $DATE_NAME=substr($CAL_TIME,11,5)."-".substr($END_TIME,11,5);
    }
    else
    {
    	
    	$DATE_NAME=_("跨天事务");
    }
    $CONTENT=csubstr(strip_tags($CONTENT),0,80);
     if(!array_key_exists($CAL_TYPE, $MANAGER))
       $CODE_NAME[$CAL_TYPE]=get_code_name($CAL_TYPE,"CAL_TYPE");
    $CAL_TYPE_DESC=$CODE_NAME[$CAL_TYPE];
    
    if($OVER_STATUS=="0")
    {
       if(compare_time($CUR_TIME,$END_TIME)>0)
       {
          $STATUS_COLOR="#FF0000";
          $CAL_TITLE=_("状态：已超时");
       }
       else if(compare_time($CUR_TIME,$CAL_TIME)<0)
       {
          $STATUS_COLOR="#0000FF";
          $CAL_TITLE=_("状态：未开始");
       }
       else
       {
          $STATUS_COLOR="#0000FF";
          $CAL_TITLE=_("状态：进行中");
       }
    }
    else
    {
       $STATUS_COLOR="#00AA00";
       $CAL_TITLE=_("状态：已完成");
    }

    $MANAGER_NAME="";
    if($MANAGER_ID!="")
    {
       if(!array_key_exists($CAL_TYPE, $MANAGER))
       {
          $query = "SELECT USER_NAME from USER where USER_ID='$MANAGER_ID'";
          $cursor1= exequery(TD::conn(),$query);
          if($ROW1=mysql_fetch_array($cursor1))
             $MANAGER[$MANAGER_ID]=$ROW1["USER_NAME"];
       }
       $MANAGER_NAME="("._("安排人:").$MANAGER[$MANAGER_ID].")";
    }
 	

 ?>
 
   <tr id="list_tr_<?=$CAL_ID?>" align="center" class="TableLine<?=2-$CAL_COUNT%2?>" title="<?=$CAL_TITLE?>">
   	<td title='<?=substr($CAL_TIME,0,16)?>-<?=substr($END_TIME,0,16)?>' width="20%"><?=$DATE_NAME?></td>
   	<td align="left" width="60%"><span class="CalLevel<?=$CAL_LEVEL?>" title="<?=cal_level_desc($CAL_LEVEL)?>">&nbsp</span><a id="cal_<?=$CAL_ID?>" href="javascript:my_note(<?=$CAL_ID?>);" status="<?=$OVER_STATUS?>" style="color:<?=$STATUS_COLOR?>;"><?=$CONTENT?></a></td>
     <td><?=$CAL_TYPE_DESC?></td>
    <td>
<?
    echo "<a href=\"javascript:;\" onclick=\"set_status1(this, '$CAL_ID');\"> ".($OVER_STATUS=="0" ? _("完成") : _("未完成"))."</a>\n";

    if($MANAGER_ID=="" || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"])
    {
       echo "<a href=\"javascript:edit_cal($CAL_ID);\">"._("修改")."</a>\n";
       echo "<a href=\"javascript:del_cal($CAL_ID);\"> "._("删除")."</a>\n";
    }
?>
    </td>
   </tr>

<?
}
if(mysql_num_rows($cursor)==0)
{
	
	echo "<tr><td colspan=4 align=center><font size=3><B>"._("无日程")."</B></font></td></tr>";
	
}

?>
</tbody>
<tr onClick="if(option2.style.display=='none') option2.style.display=''; else option2.style.display='none';" title="<?=_("点击展开/收缩选项")?>" align="left">
    <td nowrap class="TableHeader" colspan="4" style="cursor:pointer;"><img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif" align="absMiddle" ><?=_("明日")?>(<?=date("Y-m-d",strtotime("+1 day"))?> <?=get_week(date("Y-m-d",strtotime("+1 day")))?>)</td>
 </tr>
 <tbody id="option2" style="display:'';">
 <?
 $CAL_COUNT=0;
 $DATE_NOW=date("Y-m-d",strtotime("+1 day")); 
 $query = "SELECT * from CALENDAR where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'".$CONDITION_STR."  and from_unixtime(CAL_TIME,'%Y-%m-%d') <= '$DATE_NOW' and from_unixtime(END_TIME,'%Y-%m-%d')>='$DATE_NOW' order by CAL_ID desc ";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
 	  $CAL_COUNT++;
 	  $CAL_ID=$ROW["CAL_ID"];
    $CAL_TIME=$ROW["CAL_TIME"];
    $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
    $END_TIME=$ROW["END_TIME"];
    $END_TIME=date("Y-m-d H:i:s",$END_TIME);
    $CAL_TYPE=$ROW["CAL_TYPE"];
    $CAL_LEVEL=$ROW["CAL_LEVEL"];
    $CONTENT=$ROW["CONTENT"];
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $OVER_STATUS=$ROW["OVER_STATUS"];  
    
    if(substr($CAL_TIME,0,10)==$DATE_NOW && substr($END_TIME,0,10)==$DATE_NOW)
    {
      $DATE_NAME=substr($CAL_TIME,11,5)."-".substr($END_TIME,11,5);
    }
    else
    {
    	
    	$DATE_NAME=_("跨天事务");
    }
    $CONTENT=csubstr(strip_tags($CONTENT),0,80);
     if(!array_key_exists($CAL_TYPE, $MANAGER))
       $CODE_NAME[$CAL_TYPE]=get_code_name($CAL_TYPE,"CAL_TYPE");
    $CAL_TYPE_DESC=$CODE_NAME[$CAL_TYPE];
    
    if($OVER_STATUS=="0")
    {
       if(compare_time($CUR_TIME,$END_TIME)>0)
       {
          $STATUS_COLOR="#FF0000";
          $CAL_TITLE=_("状态：已超时");
       }
       else if(compare_time($CUR_TIME,$CAL_TIME)<0)
       {
          $STATUS_COLOR="#0000FF";
          $CAL_TITLE=_("状态：未开始");
       }
       else
       {
          $STATUS_COLOR="#0000FF";
          $CAL_TITLE=_("状态：进行中");
       }
    }
    else
    {
       $STATUS_COLOR="#00AA00";
       $CAL_TITLE=_("状态：已完成");
    }

    $MANAGER_NAME="";
    if($MANAGER_ID!="")
    {
       if(!array_key_exists($CAL_TYPE, $MANAGER))
       {
          $query = "SELECT USER_NAME from USER where USER_ID='$MANAGER_ID'";
          $cursor1= exequery(TD::conn(),$query);
          if($ROW1=mysql_fetch_array($cursor1))
             $MANAGER[$MANAGER_ID]=$ROW1["USER_NAME"];
       }
       $MANAGER_NAME="("._("安排人:").$MANAGER[$MANAGER_ID].")";
    }
 	

 ?>
   
   <tr id="list_tr_<?=$CAL_ID?>" align="center" class="TableLine<?=2-$CAL_COUNT%2?>" title="<?=$CAL_TITLE?>">
   	<td title='<?=substr($CAL_TIME,0,16)?>-<?=substr($END_TIME,0,16)?>' width="20%"><?=$DATE_NAME?></td>
   	<td align="left" width="60%"><span class="CalLevel<?=$CAL_LEVEL?>" title="<?=cal_level_desc($CAL_LEVEL)?>">&nbsp</span><a id="cal_<?=$CAL_ID?>" href="javascript:my_note(<?=$CAL_ID?>);" status="<?=$OVER_STATUS?>" style="color:<?=$STATUS_COLOR?>;"><?=$CONTENT?></a></td>
     <td><?=$CAL_TYPE_DESC?></td>
    <td>
<?
    echo "<a href=\"javascript:;\" onclick=\"set_status1(this, '$CAL_ID');\"> ".($OVER_STATUS=="0" ? _("完成") : _("未完成"))."</a>\n";

    if($MANAGER_ID=="" || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"])
    {
       echo "<a href=\"javascript:edit_cal($CAL_ID);\">"._("修改")."</a>\n";
       echo "<a href=\"javascript:del_cal($CAL_ID);\"> "._("删除")."</a>\n";
    }
?>
    </td>
   </tr>

<?
}
?>
 
<?
if(mysql_num_rows($cursor)==0)
{
	
	echo "<tr><td colspan=4 align=center><font size=3><B>"._("无日程")."</B></font></td></tr>";
	
}

?>
  </tbody>
 <?
  $CAL_COUNT=0;
 $DATE_NOW=date("Y-m-d",strtotime("+2 day")); 
 $query = "SELECT * from CALENDAR where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'".$CONDITION_STR."  and from_unixtime(CAL_TIME,'%Y-%m-%d') >= '$DATE_NOW'  order by CAL_ID desc ";
 $cursor= exequery(TD::conn(),$query);

 ?>
<tr onClick="if(option3.style.display=='none') option3.style.display=''; else option3.style.display='none';" title="<?=_("点击展开/收缩选项")?>" align="left">
    <td nowrap class="TableHeader" colspan="4" style="cursor:pointer;"><img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif" align="absMiddle" ><?=_("更晚")?></td>
 </tr>
 <tbody id="option3" style="display:'none';">
 <?

 while($ROW=mysql_fetch_array($cursor))
 {
 	  $CAL_COUNT++;
 	  $CAL_ID=$ROW["CAL_ID"];
    $CAL_TIME=$ROW["CAL_TIME"];
    $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
    $END_TIME=$ROW["END_TIME"];
    $END_TIME=date("Y-m-d H:i:s",$END_TIME);
    $CAL_TYPE=$ROW["CAL_TYPE"];
    $CAL_LEVEL=$ROW["CAL_LEVEL"];
    $CONTENT=$ROW["CONTENT"];
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $OVER_STATUS=$ROW["OVER_STATUS"];  
    
    if(substr($CAL_TIME,0,10)== substr($END_TIME,0,10))
    {
      $DATE_NAME=substr($CAL_TIME,0,10)."(".substr($CAL_TIME,11,5)."-".substr($END_TIME,11,5).")";
    }
    else
    {
    	
    	$DATE_NAME=_("跨天事务");
    }
    $CONTENT=csubstr(strip_tags($CONTENT),0,80);
     if(!array_key_exists($CAL_TYPE, $MANAGER))
       $CODE_NAME[$CAL_TYPE]=get_code_name($CAL_TYPE,"CAL_TYPE");
    $CAL_TYPE_DESC=$CODE_NAME[$CAL_TYPE];
    
    if($OVER_STATUS=="0")
    {
       if(compare_time($CUR_TIME,$END_TIME)>0)
       {
          $STATUS_COLOR="#FF0000";
          $CAL_TITLE=_("状态：已超时");
       }
       else if(compare_time($CUR_TIME,$CAL_TIME)<0)
       {
          $STATUS_COLOR="#0000FF";
          $CAL_TITLE=_("状态：未开始");
       }
       else
       {
          $STATUS_COLOR="#0000FF";
          $CAL_TITLE=_("状态：进行中");
       }
    }
    else
    {
       $STATUS_COLOR="#00AA00";
       $CAL_TITLE=_("状态：已完成");
    }

    $MANAGER_NAME="";
    if($MANAGER_ID!="")
    {
       if(!array_key_exists($CAL_TYPE, $MANAGER))
       {
          $query = "SELECT USER_NAME from USER where USER_ID='$MANAGER_ID'";
          $cursor1= exequery(TD::conn(),$query);
          if($ROW1=mysql_fetch_array($cursor1))
             $MANAGER[$MANAGER_ID]=$ROW1["USER_NAME"];
       }
       $MANAGER_NAME="("._("安排人:").$MANAGER[$MANAGER_ID].")";
    }
 	

 ?>
   <tr id="list_tr_<?=$CAL_ID?>" align="center" class="TableLine<?=2-$CAL_COUNT%2?>" title="<?=$CAL_TITLE?>">
   	<td title='<?=substr($CAL_TIME,0,16)?>-<?=substr($END_TIME,0,16)?>' width="20%"><?=$DATE_NAME?></td>
   	<td align="left" width="60%"><span class="CalLevel<?=$CAL_LEVEL?>" title="<?=cal_level_desc($CAL_LEVEL)?>">&nbsp</span><a id="cal_<?=$CAL_ID?>" href="javascript:my_note(<?=$CAL_ID?>);" status="<?=$OVER_STATUS?>" style="color:<?=$STATUS_COLOR?>;"><?=$CONTENT?></a></td>
     <td><?=$CAL_TYPE_DESC?></td>
    <td>
<?
    echo "<a href=\"javascript:;\" onclick=\"set_status1(this, '$CAL_ID');\"> ".($OVER_STATUS=="0" ? _("完成") : _("未完成"))."</a>\n";

    if($MANAGER_ID=="" || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"])
    {
       echo "<a href=\"javascript:edit_cal($CAL_ID);\">"._("修改")."</a>\n";
       echo "<a href=\"javascript:del_cal($CAL_ID);\">"._("删除")."</a>\n";
    }
?>
    </td>
   </tr>

<?
}
?>

<?
if(mysql_num_rows($cursor)==0)
{
	
	echo "<tr><td colspan=4 align=center class=TableLine><font size=3><B>"._("无日程")."</B></font></td></tr>";
	
}
?>
   </tbody>

 <tr onClick="if(option5.style.display=='none') option5.style.display=''; else option5.style.display='none';" title="<?=_("点击展开/收缩选项")?>" align="left">
    <td nowrap class="TableHeader" colspan="4" style="cursor:pointer;"><img src="<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif" align="absMiddle" ><?=_("更早")?></td>
 </tr>
 <tbody id="option5" style="display:none;">
 <?
 $CAL_COUNT=0;
 $DATE_NOW=date("Y-m-d",strtotime("-1 day")); 
 $query = "SELECT * from CALENDAR where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'".$CONDITION_STR."  and  from_unixtime(END_TIME,'%Y-%m-%d')<='$DATE_NOW' order by CAL_ID desc limit 0,10 ";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
 	  $CAL_COUNT++;
 	  $CAL_ID=$ROW["CAL_ID"];
    $CAL_TIME=$ROW["CAL_TIME"];
    $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
    $END_TIME=$ROW["END_TIME"];
    $END_TIME=date("Y-m-d H:i:s",$END_TIME);
    $CAL_TYPE=$ROW["CAL_TYPE"];
    $CAL_LEVEL=$ROW["CAL_LEVEL"];
    $CONTENT=$ROW["CONTENT"];
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $OVER_STATUS=$ROW["OVER_STATUS"];  
    
    if(substr($CAL_TIME,0,10)==substr($END_TIME,0,10))
    {
      $DATE_NAME=substr($CAL_TIME,0,10)."(".substr($CAL_TIME,11,5)."-".substr($END_TIME,11,5).")";
    }
    else
    {
    	
    	$DATE_NAME=_("跨天事务");
    }
    $CONTENT=csubstr(strip_tags($CONTENT),0,80);
     if(!array_key_exists($CAL_TYPE, $MANAGER))
       $CODE_NAME[$CAL_TYPE]=get_code_name($CAL_TYPE,"CAL_TYPE");
    $CAL_TYPE_DESC=$CODE_NAME[$CAL_TYPE];
    
    if($OVER_STATUS=="0")
    {
       if(compare_time($CUR_TIME,$END_TIME)>0)
       {
          $STATUS_COLOR="#FF0000";
          $CAL_TITLE=_("状态：已超时");
       }
       else if(compare_time($CUR_TIME,$CAL_TIME)<0)
       {
          $STATUS_COLOR="#0000FF";
          $CAL_TITLE=_("状态：未开始");
       }
       else
       {
          $STATUS_COLOR="#0000FF";
          $CAL_TITLE=_("状态：进行中");
       }
    }
    else
    {
       $STATUS_COLOR="#00AA00";
       $CAL_TITLE=_("状态：已完成");
    }

    $MANAGER_NAME="";
    if($MANAGER_ID!="")
    {
       if(!array_key_exists($CAL_TYPE, $MANAGER))
       {
          $query = "SELECT USER_NAME from USER where USER_ID='$MANAGER_ID'";
          $cursor1= exequery(TD::conn(),$query);
          if($ROW1=mysql_fetch_array($cursor1))
             $MANAGER[$MANAGER_ID]=$ROW1["USER_NAME"];
       }
       $MANAGER_NAME="("._("安排人:").$MANAGER[$MANAGER_ID].")";
    }
 	

 ?>
   
   <tr id="list_tr_<?=$CAL_ID?>" align="center" class="TableLine<?=2-$CAL_COUNT%2?>" title="<?=$CAL_TITLE?>">
   	<td title='<?=substr($CAL_TIME,0,16)?>-<?=substr($END_TIME,0,16)?>' width="20%"><?=$DATE_NAME?></td>
   	<td align="left" width="60%"><span class="CalLevel<?=$CAL_LEVEL?>" title="<?=cal_level_desc($CAL_LEVEL)?>">&nbsp</span><a id="cal_<?=$CAL_ID?>" href="javascript:my_note(<?=$CAL_ID?>);" status="<?=$OVER_STATUS?>" style="color:<?=$STATUS_COLOR?>;"><?=$CONTENT?></a></td>
     <td><?=$CAL_TYPE_DESC?></td>
    <td>
<?
    echo "<a href=\"javascript:;\" onclick=\"set_status1(this, '$CAL_ID');\"> ".($OVER_STATUS=="0" ? _("完成") : _("未完成"))."</a>\n";

    if($MANAGER_ID=="" || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"])
    {
       echo "<a href=\"javascript:edit_cal($CAL_ID);\">"._("修改")."</a>\n";
       echo "<a href=\"javascript:del_cal($CAL_ID);\"> "._("删除")."</a>\n";
    }
?>
    </td>
   </tr>

<?
}
?>
<tr><td colspan=4 align=center class=TableLine><a href="list_more.php" target="_blank"><?=_("查看所有更早日程")?></a></td></tr>
<?
if(mysql_num_rows($cursor)==0)
{
	
	echo "<tr><td colspan=4 align=center class=TableLine><font size=3><B>"._("无日程")."</B></font></td></tr>";
	
}
?>
   </tbody>
 </table>


<div id="overlay"></div>
<div id="form_div" class="ModalDialog" style="width:500px;">
  <div class="header"><span id="title" class="title"><?=_("新建日程")?></span><a class="operation" href="javascript:HideDialog('form_div');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/close.png"/></a></div>
  <div id="form_body" class="body" style="text-align:center;height:310px;overflow-y:auto;">
     
  </div>
</div>
<iframe name="form_iframe" id="form_iframe" style="display:none;"></iframe>
</body>
</html>
