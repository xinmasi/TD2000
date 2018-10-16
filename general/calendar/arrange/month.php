<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("../date_change.class.php");
include_once("inc/utility_org.php");

$CUR_YEAR = date('Y');
$CUR_MON = date('m');
$CUR_DAY = date('d');
$lunar = new Lunar();
//2013-4-11 主服务查询
if($IS_MAIN!="" && $IS_MAIN=="1?IS_MAIN=1")
	$IS_MAIN=1;
if($IS_MAIN==1)
	$QUERY_MASTER=true;
else
   $QUERY_MASTER="";   

if($BTN_OP!="")
{
   $DATE=strtotime($BTN_OP,strtotime($YEAR."-".$MONTH."-".$DAY));
   if(stristr($BTN_OP, "month"))
      $DATE=strtotime($BTN_OP,strtotime($YEAR."-".$MONTH."-01"));
   
   $YEAR=date("Y",$DATE);
   $MONTH=date("m",$DATE);
   
   if(!stristr($BTN_OP, "month"))
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
   $DAY=date("t", strtotime($YEAR."-".$MONTH."-01"));
}
$DATE=strtotime($YEAR."-".$MONTH."-".$DAY);
$WEEK_BEGIN=strtotime("-".(date("w",$DATE)==0 ? 6 : date("w",$DATE)-1)."days",$DATE);
$MONTH_BEGIN=strtotime($YEAR."-".$MONTH."-01");
$MONTH_END=strtotime($YEAR."-".$MONTH."-".date("t",$DATE));
$CUR_TIME=date("Y-m-d H:i:s",time());
$CUR_TIME_U=time();
$CONDITION_STR="";
if($_GET['OVER_STATUS']=="1")
{
   $CONDITION_STR.=" and OVER_STATUS='0' and CAL_TIME>'$CUR_TIME_U'";
   $STATUS_DESC="<font color='#0000FF'>"._("未开始")."</font>";
}
else if($_GET['OVER_STATUS']=="2")
{
   $CONDITION_STR.=" and OVER_STATUS='0' and CAL_TIME<='$CUR_TIME_U' and END_TIME>='$CUR_TIME_U'";
   $STATUS_DESC="<font color='#0000FF'>"._("进行中")."</font>";
}
else if($_GET['OVER_STATUS']=="3")
{
   $CONDITION_STR.=" and OVER_STATUS='0' and END_TIME<'$CUR_TIME_U'";
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
<link rel="stylesheet" type="text/css" href="activityHelper.css">
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
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<style>
.togle_bg_color{
	background-color:#ccffcc;	
}
</style>
<script>
     var move = false;
     var td_id_first = "";
     var td_val_first = "";
	   var td_id_first_val = "";
	   var td_id_last = "";
	   var td_val_last="";
	   var td_id_last_val = "";
	   var td_val_last_end="";
	   var td_val_first_end="";
    	jQuery(document).ready(function()
    	{
    				jQuery(".TableData td").bind("mousedown",function(){
    						jQuery("td").css("background-color","");
	    					if(jQuery(this).attr("id") != "" && jQuery(this).attr("id").substr(0,3)!="tw_"){
	    						  td_id_first = jQuery(this).attr("id");
	    						  td_val_first = jQuery(this).attr("val");
	    							jQuery(this).css("background-color","#ccffcc");
	    							move = true;
	    							td_val_first_end=	td_val_first;
	    							td_val_last_end=td_val_first;
	    						
	    					}
    				});
    				jQuery(".TableData td").bind("mousemove",function(){
    					 
    		
	    					if(jQuery(this).attr("id") != "" && move === true && jQuery(this).attr("id").substr(0,3)!="tw_"){
	    						 jQuery("td").css("background-color","");
	    						  td_id_last = jQuery(this).attr("id");
	    						  td_val_last = jQuery(this).attr("val");
	    							if(td_id_last_val == td_id_first_val){
	    								td_val_first_end=	td_val_first;
	    								td_val_last_end=td_val_last;
	    							}
	    							if(parseInt(td_id_last) > parseInt(td_id_first)){
	    								  td_val_first_end=td_val_first;
	    								  td_val_last_end=td_val_last;
	    								 var n = td_id_last - td_id_first;
	    								 for(i=parseInt(td_id_first);i <= parseInt(td_id_last);i++){
	    								 		jQuery("#"+i).css("background-color","#ccffcc");
	    								 }
	    							}
	    							if(parseInt(td_id_last) < parseInt(td_id_first)){
	    								  td_val_last_end=td_val_first;
	    								  td_val_first_end=td_val_last;
	    								 var n = td_id_last - td_id_first;
	    								 for(i=parseInt(td_id_last);i <= parseInt(td_id_first);i++){
	    								 		jQuery("#"+i).css("background-color","#ccffcc");
	    								 }
	    							}
	    							jQuery(this).addClass("togle_bg_color");
	    					}
    				});
    				jQuery(".TableData td").bind("mouseup",function(){  
    					if(jQuery(this).attr("id") != "" && jQuery(this).attr("id").substr(0,3)!="tw_"){
	    					move = false;
	    					new_cal(td_val_first_end,td_val_last_end,2);
	    				}
    				});
    				init();
    	});
function set_date(id)
{
   var td_cur =$("td_"+document.form1.YEAR.value+document.form1.MONTH.value+document.form1.DAY.value);  
   var div_cur=$("div_"+document.form1.YEAR.value+document.form1.MONTH.value+document.form1.DAY.value);
   var td=$(id);
   var div=$("div_"+id.substr(3));
   if(!td || !td_cur || !div || !div_cur) return;
   td_cur.className="";
   div_cur.className="Date";
   td.className="TableRed";
   div.className="TableRed";
   document.form1.YEAR.value=id.substr(3,4);
   document.form1.MONTH.value=id.substr(7,2);
   document.form1.DAY.value=id.substr(9,2);
}

function init()
{
   var tbl = $("cal_table");
   if(!tbl) return;
   for(i=0;i<tbl.rows.length;i++)
   {
      for(j=0;j<tbl.rows[i].cells.length;j++)
      {
         var td=tbl.rows[i].cells[j];
         /*
         if(td.id.substr(0,3)=="td_")
         {
            td.onclick =function() {set_date(this.id);};
	          
	          td.onmouseover=function ()
	          {
	         
	           var td=$(this.id);
               var div=$("div_"+this.id.substr(3));	
               td.className="TableRed";
               this.style.cursor='crosshair';
          	   td.title='<?=_("单击建立日事务")?>';
          	 
	          };
	          td.onmouseout=function ()
	          {
	           var td=$(this.id);
               var div=$("div_"+this.id.substr(3));	
               td.className="";
               div.className="Date";
               td.style.backgroundImage = '';
	          };
            td.onclick =function() {new_cal(this.id.substr(3),'+1 days');};
         }
         */
         //else 
         if(td.id.substr(0,3)=="tw_")
         {
            td.onclick =function() {new_cal(this.id.substr(3),'+1 weeks');};
            td.title="<?=_("单击建立周事务")?>";
         }
      }
   }
}
function date_types(date_type)
{
  _get("get_date.php","DATE_TYPE="+date_type+"&CAL_TIME=<?=$DATE?>&WEEK_BEGIN=<?=$WEEK_BEGIN?>&MONTH_BEGIN=<?=$MONTH_BEGIN?>", show_msg);
}
function show_msg(req)
{
   if(req.status==200)
   {	
   	  var TIME_STR_ARRAY= new Array();
   	  var TIME_STR=req.responseText;
   	  TIME_STR_ARRAY=TIME_STR.split(",");
   	  document.new_cal_form.CAL_TIME.value=TIME_STR_ARRAY[0];
   	  document.new_cal_form.END_TIME.value=TIME_STR_ARRAY[1];
   }
  
}
function sms_back(MANAGER_ID,MANAGER_NAME)
{
   var top = (screen.availHeight-265)/2;
   var left= (screen.availWidth-420)/2;  
   window.open("../../status_bar/sms_back.php?TO_ID="+escape(MANAGER_ID)+"&CONTENT=<?=urlencode('您好，已收到您的日程安排。')?>&TO_NAME="+escape(MANAGER_NAME),"","height=265,width=420,status=0,toolbar=no,menubar=no,location=no,scrollbars=no,top="+top+",left="+left+",resizable=yes");
}
</script>
<body class="bodycolor" onselectstart="return false"  style="-moz-user-select: none;">
<div class="PageHeader calendar_icon">
 <div class="header-left">
   <form name="form1" action="<?=$_SERVER["SCRIPT_NAME"]?>" style="margin-bottom:5px;">
   <input type="hidden" value="" name="BTN_OP">
   <input type="hidden" value="<?=$_GET['OVER_STATUS']?>" name="OVER_STATUS">
   <input type="hidden" value="<?=$DAY?>" name="DAY">
   <a href="<?=$_SERVER["SCRIPT_NAME"]?>?YEAR=<?=$CUR_YEAR?>&MONTH=<?=$CUR_MON?>&DAY=<?=$CUR_DAY?>" class="ToolBtn ToolBtn-active"><span><?=_("今天")?></span></a>
<!-------------- 年 ------------>
   <a href="javascript:set_year(-1);" class="ArrowButtonLL" title="<?=_("上一年")?>"></a>
   <a href="javascript:set_mon(-1);" class="ArrowButtonL" title="<?=_("上一月")?>"></a>
   <select name="YEAR" class="SmallSelect" onChange="My_Submit();">
<?
   for($I=2000;$I<=2030;$I++)
   {
?>
      <option value="<?=$I?>" <? if($I==$YEAR) echo "selected";?>><?=$I?><?=_("年")?></option>
<?
   }
?>
   </select>
<!-------------- 月 ------------>
   <select name="MONTH" class="SmallSelect" onChange="My_Submit();">
<?
   for($I=1;$I<=12;$I++)
   {
     if($I<10)
        $I="0".$I;
?>
     <option value="<?=$I?>" <? if($I==$MONTH) echo "selected";?>><?=$I?><?=_("月")?></option>
<?
   }
?>
   </select>
   <a href="javascript:set_mon(1);" class="ArrowButtonR" title="<?=_("下一月")?>"></a>
   <a href="javascript:set_year(1);" class="ArrowButtonRR" title="<?=_("下一年")?>"></a>&nbsp;
   <a id="status" href="javascript:;" class="dropdown" onClick="showMenu(this.id,'1');" hidefocus="true"><span><?=$STATUS_DESC?></span></a>&nbsp;
   <div id="status_menu" class="attach_div">
      <a href="javascript:set_status_index('');"><?=_("全部")?></a>
      <a href="javascript:set_status_index(1);" style="color:#0000FF;"><?=_("未开始")?></a>
      <a href="javascript:set_status_index(2);" style="color:#0000FF;"><?=_("进行中")?></a>
      <a href="javascript:set_status_index(3);" style="color:#FF0000;"><?=_("已超时")?></a>
      <a href="javascript:set_status_index(4);" style="color:#00AA00;"><?=_("已完成")?></a>
   </div>
 </div>
 <div class="header-right">
 	<a href="query.php" class="ToolBtn"><span><?=_("查询")?></span></a>
   <a id="new" href="javascript:;" class="dropdown" onClick="showMenu(this.id,'1');" hidefocus="true"><span><?=_("新建")?></span></a>
    <a href="count.php" class="ToolBtn"><span><?=_("统计")?></span></a>&nbsp;
    <div id="new_menu" class="attach_div">
   	 <a href="javascript:new_cal(<?=$DATE?>,'+1 days');" title="<?=_("建立事务")?>"><?=_("事务")?></a>
      <a href="javascript:new_diary();"><?=_("工作日志")?> </a>
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
 //时间戳转换
$CAL_TIME_CHANGE_B=date("Y-m-d",$MONTH_BEGIN)." 00:00:00";
$CAL_TIME_CHANGE_B=strtotime($CAL_TIME_CHANGE_B);
$CAL_TIME_CHANGE_E=date("Y-m-d",$MONTH_END)." 23:59:59";
$CAL_TIME_CHANGE_E=strtotime($CAL_TIME_CHANGE_E);

 //....
 //============================ 显示日程安排 =======================================
 $query = "SELECT * from CALENDAR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OWNER))".$CONDITION_STR." and (CAL_TIME>='$CAL_TIME_CHANGE_B' and CAL_TIME<='$CAL_TIME_CHANGE_E' || END_TIME>='$CAL_TIME_CHANGE_B' and END_TIME<='$CAL_TIME_CHANGE_E' || CAL_TIME<='$CAL_TIME_CHANGE_B' and END_TIME>='$CAL_TIME_CHANGE_E') order by CAL_TIME";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 while($ROW=mysql_fetch_array($cursor))
 {
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

    if(!array_key_exists($CAL_TYPE, $CODE_NAME))
       $CODE_NAME[$CAL_TYPE]=get_code_name($CAL_TYPE,"CAL_TYPE");
    $CAL_TITLE=_("类型：").$CODE_NAME[$CAL_TYPE]."\n";
    
    if($OVER_STATUS=="0")
    {
       if(compare_time($CUR_TIME,$END_TIME)>0)
       {
          $STATUS_COLOR="#FF0000";
          $CAL_TITLE.=_("状态：已超时")."\n";
          $STATUS_FLAG_BLUE="<img src='".MYOA_STATIC_SERVER."/static/images/calendar/status_delay_blue.png' />";
          $STATUS_FLAG="<img src='".MYOA_STATIC_SERVER."/static/images/calendar/status_delay.png' />";
       }
       else if(compare_time($CUR_TIME,$CAL_TIME)<0)
       {
          $STATUS_COLOR="#0000FF";
          $CAL_TITLE.=_("状态：未开始")."\n";
          $STATUS_FLAG_BLUE="<img src='".MYOA_STATIC_SERVER."/static/images/calendar/status_undo_blue.png'/>";
          $STATUS_FLAG="<img src='".MYOA_STATIC_SERVER."/static/images/calendar/status_undo.png'/>";
       }
       else
       {
          $STATUS_COLOR="#0000FF";
          $CAL_TITLE.=_("状态：进行中")."\n";
          $STATUS_FLAG_BLUE="<img src='".MYOA_STATIC_SERVER."/static/images/calendar/status_ing_blue.png'/>";
          $STATUS_FLAG="<img src='".MYOA_STATIC_SERVER."/static/images/calendar/status_ing.png'/>";
       }
    }
    else
    {
       $STATUS_COLOR="#00AA00";
       $CAL_TITLE.=_("状态：已完成")."\n";
       $STATUS_FLAG_BLUE="<img src='".MYOA_STATIC_SERVER."/static/images/calendar/status_over_blue.png'/>";
       $STATUS_FLAG="<img src='".MYOA_STATIC_SERVER."/static/images/calendar/status_over.png'/>";
    }
    $CAL_TITLE.=_("优先级：").cal_level_desc($CAL_LEVEL)."\n";
    if($MANAGER_ID!="")
    {
       if(!array_key_exists($MANAGER_ID, $MANAGER))
       {
          $query = "SELECT USER_NAME from USER where USER_ID='$MANAGER_ID'";
          $cursor1= exequery(TD::conn(),$query);
          if($ROW1=mysql_fetch_array($cursor1))
             $MANAGER[$MANAGER_ID]=$ROW1["USER_NAME"];
       }
       $CAL_TITLE.=_("安排人：").$MANAGER[$MANAGER_ID];
    }
    
   if($MANAGER_ID!=""&&$MANAGER_ID!=$_SESSION["LOGIN_USER_ID"])
      $CONTENT=csubstr(strip_tags($CONTENT),0,80)."</b>";
   else
      $CONTENT=csubstr(strip_tags($CONTENT),0,80);
    
    if(substr($CAL_TIME,0,10) != substr($END_TIME,0,10))
    {
       $ALL_DAY="<div id=\"div_".$CAL_ID."\" title='".$CAL_TITLE."'>";
       if(substr($CAL_TIME,0,10) < date("Y-m-d",$MONTH_BEGIN))
          $ALL_DAY.="<a href=\"javascript:set_mon(-1);\" title=\""._("上一月")."\">".menu_arrow("LEFT")."</a> ";
       $ALL_DAY.="<span class=\"CalLevel".$CAL_LEVEL."\" >".substr($CAL_TIME,0,16)." - ".substr($END_TIME,0,16)."</span> <a id=\"cal_".$CAL_ID."\" href='javascript:my_note($CAL_ID,\"".$IS_MAIN."\");' onclick='CancelBuble(event);' status=\"$OVER_STATUS\" onmouseover=\"showMenu(this.id);\" style='color:".$STATUS_COLOR.";'>".$STATUS_FLAG_BLUE.$CONTENT."</a> ";
       if(substr($END_TIME,0,10) > date("Y-m-d",$MONTH_END))
          $ALL_DAY.="<a href=\"javascript:set_mon(1);\" title=\""._("下一月")."\">".menu_arrow("RIGHT")."</a>";
       $ALL_DAY.="</div>\n";
       $CAL_ALL_DAY[]=$ALL_DAY;
    }
    else
    {
    	 if(strlen($CONTENT)>38)
   	  {
   	     $CONTENT=csubstr(strip_tags($CONTENT),0,38)."...";
   	  }
   	  else
   	  {
   	  	$CONTENT=csubstr(strip_tags($CONTENT),0,38);
   	  }
       $CAL_ARRAY[date("j",strtotime($CAL_TIME))].="<div id=\"div_".$CAL_ID."\" title='".$CAL_TITLE."'><span class=\"CalLevel".$CAL_LEVEL."\" >".substr($CAL_TIME,11,5)."-".substr($END_TIME,11,5)."</span>&nbsp;<a id=\"cal_".$CAL_ID."\" href='javascript:my_note($CAL_ID,\"".$IS_MAIN."\");' onclick='CancelBuble(event);'  onmouseover=\"showMenu(this.id);\" style='color:".$STATUS_COLOR.";'>".$STATUS_FLAG_BLUE.$CONTENT."</a></div>\n";
    }
  
 }

?>
<div id="activity1">
<table id="cal_table" class="TableBlock" style="width:95%;margin-left:5%;" align="center">
  <tr align="center" class="TableHeader" id="table_weeks">
    <td width="6%" class="TableCorner"  id="zhou" <? if(count($CAL_ALL_DAY)>0){ echo "style='cursor:hand;'"; ?>  onclick="if(option1.style.display=='none') {option1.style.display=''; document.getElementById('zhou').innerText='<?=_("隐藏跨天")?>'; } else {option1.style.display='none'; document.getElementById('zhou').innerText='<?=_("显示跨天")?>';}" title="<?=_("点击显示/隐藏跨天日程")?>" <? }?>> <? if(count($CAL_ALL_DAY)>0){?><?=_("隐藏跨天")?><?}else{?><?=_("周数")?><?}?> </td>
    <td  class="week-day" width="14%"><?=_("星期一")?></td>
    <td  class="week-day" width="14%"><?=_("星期二")?></td>
    <td  class="week-day" width="14%"><?=_("星期三")?></td>
    <td  class="week-day" width="14%"><?=_("星期四")?></td>
    <td  class="week-day" width="14%"><?=_("星期五")?></td>
    <td  class="week-day" width="12%"><?=_("星期六")?></td>
    <td  class="week-day" width="12%"><?=_("星期日")?></td>
  </tr>
<?
if(count($CAL_ALL_DAY)>0)
{
?>
   <tbody id="option1" style="display:">
    <tr class="TableData">
      <td class="TableLeft" align="center"><?=_("跨天")?></td>
      <td colspan="7">
<?
   foreach($CAL_ALL_DAY as $ALL_DAY)
      echo $ALL_DAY;
?>
      </td>
    </tr>
   </tbody>
<?
}

for($I=1;$I<=date("t",$DATE);$I++)
{
  $WEEK=date("w",strtotime($YEAR."-".$MONTH."-".$I));
  $WEEK= $WEEK==0 ? 6: $WEEK-1;
  $WEEK_DAY=date("w",strtotime($YEAR."-".$MONTH."-".$I));
  $DATE_EV=strtotime($YEAR."-".$MONTH."-".$I);
  if($WEEK==0 || $I==1)
  {
     $WEEKS=date("W", $MONTH_BEGIN+($I-1)*24*3600);
     
     $WEEK_BEGIN=date("Ymd", strtotime("-".$WEEK."days",strtotime($YEAR."-".$MONTH."-".$I)));
     
     $MSG = sprintf(_("第(%d)周"), $WEEKS);
     echo "  <tr height=\"80\" class=\"TableData\">\n";
     echo "    <td id=\"tw_".$WEEK_BEGIN."\" class=\"TableLeft\" align=\"center\">".$MSG."</td>\n";
  }

  for($J=0;$J<$WEEK&&$I==1;$J++)
  {
?>
    <td class="TableData" valign="top" >&nbsp</td>
<?
  }
  
?>
    <td val="<?=$YEAR.$MONTH.($I<10 ? "0".$I : $I)?>" id="<?=$I?>" class="dayTd<?if($I==$DAY) echo " TableRed";?>" valign="top" <? if($WEEK_DAY==0 or $WEEK_DAY==6) echo "style='background-color:#ffdff9;'";?> >
      <div id="div_<?=$YEAR.$MONTH.($I<10 ? "0".$I : $I)?>" align="right" class="<?=$I==$DAY ? "TableRed" : "Date";?>" <? $date =$lunar->convertSolarToLunar($DATE_EV); ?> ><a   href="day.php?YEAR=<?=$YEAR?>&MONTH=<?=$MONTH?>&DAY=<?=$I?>&OVER_STATUS=<?=$_GET['OVER_STATUS']?>" onclick='CancelBuble(event);' style="cursor:hand;" title="<?=$date[0]._("年").$date[1].$date[2]._("(农历)")."\n"?><?=_("转到该日查看")?>"><b><?=$I?></b></a></div>
      <div>
        <?=$CAL_ARRAY[$I]?>
      </div>
         <?
         $DATE_STR=$YEAR."-".$MONTH."-".$I;
         $Tquery = "SELECT * from AFFAIR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER)) order by AFF_ID desc";
         $Tcursor= exequery(TD::conn(),$Tquery,$QUERY_MASTER);
         while($TROW=mysql_fetch_array($Tcursor))
         {
            $AFF_ID=$TROW["AFF_ID"];      
            $TYPE=$TROW["TYPE"];         
            $REMIND_DATE=$TROW["REMIND_DATE"];
            $BEGIN_TIME=$TROW["BEGIN_TIME"];
            $LAST_REMIND=$ROW["LAST_REMIND"];
            $BEGIN_TIME=date("Y-m-d H:i:s",$BEGIN_TIME);
            $END_TIME=$TROW["END_TIME"];
            $MANAGER_ID=$TROW["MANAGER_ID"]; 
            $REMIND_TIME=$TROW["REMIND_TIME"];  
            $BEGIN_DATE=substr($BEGIN_TIME,0,10);
            if($END_TIME!=0)
            {
               $END_TIME=date("Y-m-d H:i:s",$END_TIME);
               $END_DATE=substr($END_TIME,0,10); 
            }
            else
               $END_DATE="0000-00-00";   
            $CONTENT=$TROW["CONTENT"];
            if(compare_date($DATE_STR,$BEGIN_DATE)<0)
               continue;
       
            if($END_DATE!="0000-00-00")
            {
               if(compare_date($DATE_STR,$END_DATE)>0)
                    continue;
            }
            
            $FLAG=0;
            if($TYPE=="2")
               $FLAG=1;
            elseif($TYPE=="3" && date("w",strtotime($DATE_STR))==$REMIND_DATE)
               $FLAG=1;
            elseif($TYPE=="4" && date("j",strtotime($DATE_STR))==$REMIND_DATE)
               $FLAG=1;
            elseif($TYPE=="5")
            {
               $REMIND_ARR=explode("-",$REMIND_DATE);
               $REMIND_DATE_MON=$REMIND_ARR[0];
               $REMIND_DATE_DAY=$REMIND_ARR[1];
               if(date("n",strtotime($DATE_STR))==$REMIND_DATE_MON && date("j",strtotime($DATE_STR))==$REMIND_DATE_DAY)
                  $FLAG=1;
            }elseif($TYPE=="6")
            {
            	 if(date("w",strtotime($DATE_STR))!=0 && date("w",strtotime($DATE_STR))!=6)
                  $FLAG=1;	
            }
            $AFF_TITLE=_("提醒时间：每日 ").substr($REMIND_TIME,0,-3)."\n"._("上次提醒：").$LAST_REMIND."\n"._("起始时间：").$BEGIN_TIME;
            if($FLAG==1)
            {
            	 if(strlen($CONTENT)>38)
   	           {
   	              $CONTENT=csubstr(strip_tags($CONTENT),0,38)."...";
   	           }
               else
   	           {
   	  	          $CONTENT=csubstr(strip_tags($CONTENT),0,38);
   	           }
               
               echo $REMIND_TIME."&nbsp;<a id='aff_".$AFF_ID."' href='javascript:my_aff_note(".$AFF_ID.",1,\"".$IS_MAIN."\")' onclick='cancelevent(event);' onmousedown='cancelevent(event);'  onmouseup='cancelevent(event);' onmouseover='showMenu(this.id);' title='".$AFF_TITLE."'>".$CONTENT."</a><br>";
             
            }
            
         }  
         
         $Tquery = "SELECT * from TASK where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by TASK_ID desc";    
         $Tcursor= exequery(TD::conn(),$Tquery,$QUERY_MASTER);
         while($TROW=mysql_fetch_array($Tcursor))
         {
            $TASK_ID=$TROW["TASK_ID"];      
            $BEGIN_DATE =$TROW["BEGIN_DATE"];
            $END_DATE=$TROW["END_DATE"];
            $MANAGER_ID=$TROW["MANAGER_ID"]; 
            $SUBJECT=$TROW["SUBJECT"];
            if(compare_date($DATE_STR,$BEGIN_DATE)<0)
               continue;
       
            if($END_DATE!="0000-00-00")
            {
               if(compare_date($DATE_STR,$END_DATE)>0)
                    continue;
            }
            
           $MANAGER_ID_NAME="";
           if($MANAGER_ID!="")
           {
              if(substr($MANAGER_ID,-1)!=",")
                 $MANAGER_NEW_ID=$MANAGER_ID.",";
              $MANAGER_ID_NAME=GetUserNameById($MANAGER_NEW_ID);
              if(substr($MANAGER_ID_NAME,-1)==",")
                 $MANAGER_ID_NAME=substr($MANAGER_ID_NAME,0,-1);   
              $MANAGER_ID_NAME="("._("安排人:").$MANAGER_ID_NAME.")";
              
           }
            
       if(strlen($SUBJECT)>38)
   	  {
   	     $SUBJECT=csubstr(strip_tags($SUBJECT),0,38)."...";
   	  }
   	  else
   	  {
   	  	$SUBJECT=csubstr(strip_tags($SUBJECT),0,38);
   	  }
           echo _("任务:")."<a id='task_".$TASK_ID."' href='javascript:my_task_note(".$TASK_ID.",1,0,\"".$IS_MAIN."\")' onclick='cancelevent(event);' onmousedown='cancelevent(event);'  onmouseup='cancelevent(event);' onmouseover='showMenu(this.id);' style='color:blue' title='"._("点击查看任务详情")."'>".$SUBJECT."</a>".$MANAGER_ID_NAME."<br>";
         
         } 
        ?>
       </div>
     </td>
<?
  if ($WEEK==6)
     echo "  </tr>\n";
}//while

//------------- 补结尾空格 -------------

if($WEEK!=6)
{
  for($I=$WEEK;$I<6;$I++)
  {
  	
?>
    <td class="TableData"  >&nbsp</td>
<?
  }
?>
  </tr>
<?
}

//-------------------------- 本月员工生日 -------------------------
 $CUR_MONTH=$MONTH;

 $query = "SELECT USER_NAME,BIRTHDAY from USER where (NOT_LOGIN='0' or NOT_MOBILE_LOGIN='0') and DEPT_ID!=0 order by SUBSTRING(BIRTHDAY,6,5),USER_NAME ASC";
 $cursor= exequery(TD::conn(),$query);
 $PERSON_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $USER_NAME=$ROW["USER_NAME"];
    $BIRTHDAY=$ROW["BIRTHDAY"];
    $MON=substr($BIRTHDAY,5,2);
    $DATA=substr($BIRTHDAY,5,5);

    if($MON!=$CUR_MONTH || $BIRTHDAY=="1900-01-01 00:00:00" || $BIRTHDAY=="0000-00-00 00:00:00")
       continue;
    $PERSON_STR.=$USER_NAME."(".$DATA.")&nbsp&nbsp&nbsp&nbsp";
    $PERSON_COUNT++;
}
if($PERSON_COUNT>0)
{
?>
  <tr class="TableData">
    <td style="color:#46A718" align="center"><b><?=_("生日：")?></b></td>
    <td colspan="7"><?=$PERSON_STR?></td>
  </tr>
<?
}
?>
</table>
</div>
<?=$OP_MENU?>

<div id="overlay"></div>
<div id="form_div" class="ModalDialog" style="width:500px;">
  <div class="header"><span id="title" class="title"><?=_("新建日程")?></span><a class="operation" href="javascript:aa();"><img src="<?=MYOA_STATIC_SERVER?>/static/images/close.png"/></a></div>
  <div id="form_body" class="body" style="text-align:center;height:352px;overflow-y:auto;">
     
  </div>
</div>
<iframe name="form_iframe" id="form_iframe" style="display:none;"></iframe>
</body>
</html>

