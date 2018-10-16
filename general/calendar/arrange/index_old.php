<?
include_once("../date_change.class.php");
if($_COOKIE["cal_view"]=="day" || $_COOKIE["cal_view"]=="month" || $_COOKIE["cal_view"]=="list")
   header("location:".$_COOKIE["cal_view"].".php");

include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/i18n/CDateFormatter.php"); 
//2013-4-11 主服务查询
if($IS_MAIN!="" && $IS_MAIN=="1?IS_MAIN=1")
	$IS_MAIN=1;
if($IS_MAIN==1)
	$QUERY_MASTER=true;
else
   $QUERY_MASTER="";   
$df = new CDateFormatter(MYOA_LANG_COOKIE);
$local = $df->getLocale();
$lunar = new Lunar();
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
$CUR_YEAR = date('Y');
$CUR_MON = date('m');
$CUR_DAY = date('d');
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
$MONTH_BEGIN=strtotime($YEAR."-".$MONTH."-01");

$WEEK=date("W",$DATE);
$WEEK = ($WEEK=="1" && $MONTH=="12") ? "53" : $WEEK;
$WEEK_BEGIN=strtotime("-".(date("w",$DATE)==0 ? 6 : date("w",$DATE)-1)."days",$DATE);
$WEEK_END=strtotime("+6 days",$WEEK_BEGIN);
$CUR_TIME=date("Y-m-d H:i:s",time());
$CUR_TIME_CHANGE=time();
$CONDITION_STR="";
if($_GET['OVER_STATUS']=="1")
{
   $CONDITION_STR.=" and OVER_STATUS='0' and CAL_TIME>'$CUR_TIME_CHANGE'";
   $STATUS_DESC="<font color='#0000FF'>"._("未开始")."</font>";
}
else if($_GET['OVER_STATUS']=="2")
{
   $CONDITION_STR.=" and OVER_STATUS='0' and CAL_TIME<='$CUR_TIME_CHANGE' and END_TIME>='$CUR_TIME_CHANGE'";
   $STATUS_DESC="<font color='#0000FF'>"._("进行中")."</font>";
}
else if($_GET['OVER_STATUS']=="3")
{
   $CONDITION_STR.=" and OVER_STATUS='0' and END_TIME<'$CUR_TIME_CHANGE'";
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
<style type="text/css" mce_bogus="1">
	.hilite{
    background-color:Silver;}
</style>
<style type="text/css">
div.CalAllDay2{background:#ececec;border:1px #cccccc solid;position:relative;margin: 1px 0px;padding:0px 3px;}
</style>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery-ui.custom.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.ui.draggable.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.ui.droppable.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/mytable.js"></script>
<script>
jQuery(document).ready(function(){
    	 var activityHelper = new ActivityHelper({
    		renderTo : 'activity',
    		loadDate : '2012-08-15'
    			
  });
    	init();
      jQuery(".drag").bind("mouseover",function(event){
		 jQuery(this).css("cursor","move");
		 
	});
	
 var NEW_TIME="";
 var CAL_ID="";
 window.lastDrag = {
 		handle:null,
 		parent:null
	};
 
	jQuery(".drag").draggable({
		cursor:"move",
		containment:"table",
		start:function(event, ui) 
		{ 
			 activityHelper.stopmouse(); 
		},
			stop:function(event, ui) 
		{ 
			 activityHelper.startmouse();
		}
		});
		jQuery('.ui-draggable').mousedown(function(){
			activityHelper.stopmouse(); 
		
		});
	  //jQuery("td.timeItem").droppable({
	  	 jQuery("td").droppable({
		drop: function( event, ui ) {
			   NEW_TIME=jQuery(this).attr("id").substr(3);
			   CAL_ID=ui.draggable.attr("id").substr(4);
			   window.lastDrag.handle = ui.draggable;
			  if(jQuery(this).attr("id").substr(0,3)=="td_")
						edit_cal(CAL_ID,2,NEW_TIME);
			  else
			  	  close_modify(2);
				activityHelper.startmouse(); 
			}
		});
	
});

//al.init(jQuery("td div"));
function set_date(id)
{
   var th = $("tbl_header"),col=0;
   if(!th) return;
   for(i=0;i<th.cells.length;i++)
   {
      if(th.cells[i].id!=id)
         continue;
      col=i;
      break;
   }
   if(col==0) return;

   var tbl = $("cal_table");
   if(!tbl) return;
   for(i=0;i<tbl.rows.length;i++)
   {
      for(j=0;j<tbl.rows[i].cells.length;j++)
      {
         var td=tbl.rows[i].cells[j];
         if(td.id.substr(0,3)!="td_")
            continue;

         if(td.className=="TableRowHover")
            td.className="";
         if(j==col)
         {
            td.className="TableRowHover";
         }
      }
   }
   document.form1.YEAR.value=id.substr(3,4);
   document.form1.MONTH.value=id.substr(7,2);
   document.form1.DAY.value=id.substr(9,2);
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
         	td.onmouseover=function(){
          	   this.style.cursor='crosshair';
          	   this.title='<?=_("单击建立日事务")?>';
          	};
          	td.onmouseout=function(){
          		 this.style.backgroundImage = '';
          	};

            td.onclick =function() {new_cal(this.id.substr(3));};
          }
         else 
         */
         if(td.id.substr(0,3)=="th_")
         {

            td.onclick =function() {set_date(this.id);};
            td.onclick =function() {new_cal(this.id.substr(3),'+1 days',3);};
            td.title="<?=_("单击建立日事务")?>";
         }
      }
   }

   var th = $("tbl_header"),week_width=0;
   if(!th) return;

   for(j=1;j<th.cells.length;j++)
      week_width+=th.cells[j].offsetWidth;

   for(i=0;i<cal_div_array.length;i++)
   {
      var left=width=0;
      for(j=1;j<cal_div_array[i][1];j++)
         left+=th.cells[j].offsetWidth;
      for(j=cal_div_array[i][1];j<=cal_div_array[i][2];j++)
         width+=th.cells[j].clientWidth;

      if(left+width > week_width-6)
         width=week_width-left-6;

      $("div_"+cal_div_array[i][0]).style.left=left+"px";
      $("div_"+cal_div_array[i][0]).style.width=width+"px";
   }
  
}

function sms_back(MANAGER_ID,MANAGER_NAME)
{
   var top = (screen.availHeight-265)/2;
   var left= (screen.availWidth-420)/2;
   window.open("../../status_bar/sms_back.php?TO_ID="+escape(MANAGER_ID)+"&CONTENT=<?=urlencode(_("您好，已收到您的日程安排。"))?>&TO_NAME="+escape(MANAGER_NAME),"","height=265,width=420,status=0,toolbar=no,menubar=no,location=no,scrollbars=no,top="+top+",left="+left+",resizable=yes");
}
</script>
<body class="bodycolor"   onselectstart="return false"  style="-moz-user-select: none;">
<div class="PageHeader calendar_icon">
 <div class="header-left">
   <form name="form1" action="<?=$_SERVER["SCRIPT_NAME"]?>" style="margin-bottom:5px;">
   <input type="hidden" value="" name="BTN_OP">
   <input type="hidden" value="<?=$_GET['OVER_STATUS']?>" name="OVER_STATUS">
   <input type="hidden" value="<?=$MONTH?>" name="MONTH">
   <input type="hidden" value="<?=$DAY?>" name="DAY">
   <a href="<?=$_SERVER["SCRIPT_NAME"]?>?YEAR=<?=$CUR_YEAR?>&MONTH=<?=$CUR_MON?>&DAY=<?=$CUR_DAY?>" class="ToolBtn ToolBtn-active"><span><?=_("今天")?></span></a>
<!-------------- 年 ------------>
   <a href="javascript:set_year(-1);" class="ArrowButtonLL" title="<?=_("上一年")?>"></a>
   <a href="javascript:set_week(-1);" class="ArrowButtonL" title="<?=_("上一周")?>"></a>
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
<!-------------- 周 ------------>
   <select name="WEEK" class="SmallSelect" onChange="set_week(this.value-<?=$WEEK?>);">
<?
   for($I=1;$I<=53;$I++)
   {
$MSG = sprintf(_("第%d周"), $I);
?>
      <option value="<?=$I?>" <? if($I==$WEEK) echo "selected";?>><?=$MSG?></option>
<?
   }
?>
   </select>
   <a href="javascript:set_week(1);" class="ArrowButtonR" title="<?=_("下一周")?>"></a>
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
 <div class="header-right" >
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
 $COUNT_A=0;
// 转化成时间戳比较
$CAL_TIME_CHANGE_B=date("Y-m-d",$WEEK_BEGIN)." 00:00:00";
$CAL_TIME_CHANGE_B=strtotime($CAL_TIME_CHANGE_B);
$CAL_TIME_CHANGE_E=date("Y-m-d",$WEEK_END)." 23:59:59";
$CAL_TIME_CHANGE_E=strtotime($CAL_TIME_CHANGE_E);
//...........
//日程安排
$query = "SELECT * from CALENDAR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OWNER)) ".$CONDITION_STR." and (CAL_TIME>='$CAL_TIME_CHANGE_B' and CAL_TIME<='$CAL_TIME_CHANGE_E' || END_TIME>='$CAL_TIME_CHANGE_B' and END_TIME<='$CAL_TIME_CHANGE_E' || CAL_TIME<='$CAL_TIME_CHANGE_B' and END_TIME>='$CAL_TIME_CHANGE_E') order by CAL_TIME";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
while($ROW=mysql_fetch_array($cursor))
{
	 $COUNT_A++;
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
   $OWNER=$ROW["OWNER"];
   $TAKER=$ROW["TAKER"];
   $CREATOR=$ROW["USER_ID"];
   if(($CREATOR==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1 || find_id($OWNER,$_SESSION["LOGIN_USER_ID"])) //有修改权限
   {
   		$EDIT_FLAG=1;
   }
   else
     $EDIT_FLAG=0;
   if(!array_key_exists($CAL_TYPE, $CODE_NAME))
      $CODE_NAME[$CAL_TYPE]=get_code_name($CAL_TYPE,"CAL_TYPE");
   
   $CAL_TITLE=_("类型：").$CODE_NAME[$CAL_TYPE]."\n";
// 给层增加背景颜色
   if($CAL_LEVEL==1) //如果是重要紧急 红色
   {
   	 $AFF_DIV_COLOR="#fd0a0a";
   }
   else if($CAL_LEVEL==2) //如果是重要不紧急 黄色
   {
   	 $AFF_DIV_COLOR="#ff9933";
   }
   else if($CAL_LEVEL==3) //如果是不重要紧急 绿色
   {
   	 $AFF_DIV_COLOR="#00aa00";
   }
   else if($CAL_LEVEL==4) //如果是不重要紧急 灰色
   {
   	 $AFF_DIV_COLOR="#6f7274";
   }
   else
       $AFF_DIV_COLOR="#b2d1ec";
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
             $MANAGER[$MANAGER_ID]=td_trim(getUserNameById($MANAGER_ID));

       }
       $CAL_TITLE.=_("安排人：").$MANAGER[$MANAGER_ID];
   }

   if($MANAGER_ID!=""&&$MANAGER_ID!=$_SESSION["LOGIN_USER_ID"])
      $CONTENT=csubstr(strip_tags($CONTENT),0,80)."</b>";
   else
      $CONTENT=csubstr(strip_tags($CONTENT),0,80);
   if(substr($CAL_TIME,0,10) != substr($END_TIME,0,10))
   {
   	  if($COUNT_A%2==0)
   	    $STYLE="class=CalAllDay";
   	  else
   	    $STYLE="class=CalAllDay2";
      $ALL_DAY="<div id=\"div_".$CAL_ID."\" title='".$CAL_TITLE."' ".$STYLE.">";
      if(substr($CAL_TIME,0,10) < date("Y-m-d",$WEEK_BEGIN))
         $ALL_DAY.="<a href=\"javascript:set_week(-1);\" title=\""._("上一周")."\">".menu_arrow("LEFT")."</a> ";
     // $ALL_DAY.="<span class=\"CalLevel".$CAL_LEVEL."\" title=\"".cal_level_desc($CAL_LEVEL)."\">".substr($CAL_TIME,0,16)." - ".substr($END_TIME,0,16)."</span> ".$CAL_TYPE_DESC."<div id=\"div1_".$CAL_ID."\"  style='width:90%;background:".$AFF_DIV_COLOR."'><a id=\"cal_".$CAL_ID."\" href='javascript:my_note($CAL_ID);' onclick='cancelevent();' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' onmouseover=\"showMenu(this.id);\" >".$CONTENT."</div></a> ".$MANAGER_NAME;
      $ALL_DAY.="<span class=\"CalLevel".$CAL_LEVEL."\" >".substr($CAL_TIME,0,16)." - ".substr($END_TIME,0,16)."</span> ".$CAL_TYPE_DESC."<a id=\"cal_".$CAL_ID."\" href='javascript:my_note($CAL_ID,\"".$IS_MAIN."\");' onclick='CancelBuble(event);' onmouseover=\"showMenu(this.id);\" style='color:".$STATUS_COLOR.";'>".$STATUS_FLAG_BLUE.$CONTENT."</a> ".$MANAGER_NAME;
      if(substr($END_TIME,0,10) > date("Y-m-d",$WEEK_END))
         $ALL_DAY.="<a href=\"javascript:set_week(1);\" title=\""._("下一周")."\">".menu_arrow("RIGHT")."</a>";
      $ALL_DAY.="</div>\n";
      $CAL_ALL_DAY[]=$ALL_DAY;

      $COL_BEGIN = floor((strtotime(substr($CAL_TIME,0,10))-$WEEK_BEGIN)/86400)+1;
      $COL_BEGIN = $COL_BEGIN<=0 ? 1 : $COL_BEGIN;
      $COL_END = floor((strtotime(substr($END_TIME,0,10))-$WEEK_BEGIN)/86400)+1;
      $COL_END = $COL_END>7 ? 7 : $COL_END;
      $CAL_DIV_ARRAY.="Array($CAL_ID, $COL_BEGIN, $COL_END), ";
   }
   else
   {

   	  if(strlen($CONTENT)>40)
   	  {
   	     $CONTENT=csubstr(strip_tags($CONTENT),0,40)."...";
   	  }
   	  else
   	  {
   	  	$CONTENT=csubstr(strip_tags($CONTENT),0,40);
   	  }
      if($EDIT_FLAG==1)
      {
          $CAL_ARRAY[date("w",strtotime($CAL_TIME))==0 ? 6 : date("w",strtotime($CAL_TIME))-1][date("G",strtotime($CAL_TIME))].="<div id=\"div_".$CAL_ID."\" class='drag'  title='".$CAL_TITLE."' style='width:90%;background:".$AFF_DIV_COLOR.";'><span>".substr($CAL_TIME,11,5)."-".substr($END_TIME,11,5)."</span>&nbsp;<a id=\"cal_".$CAL_ID."\" href='javascript:my_note($CAL_ID,\"".$IS_MAIN."\");' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' status=\"$OVER_STATUS\" onmouseover=\"showMenu(this.id);\" style='color:#000000'>".$STATUS_FLAG.$CONTENT."</a></div>";
      }
      else
      {
      	  $CAL_ARRAY[date("w",strtotime($CAL_TIME))==0 ? 6 : date("w",strtotime($CAL_TIME))-1][date("G",strtotime($CAL_TIME))].="<span   class=\"CalLevel".$CAL_LEVEL."\" >".substr($CAL_TIME,11,5)."-".substr($END_TIME,11,5)."</span>&nbsp;<a id=\"cal_".$CAL_ID."\" href='javascript:my_note($CAL_ID,\"".$IS_MAIN."\");' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' status=\"$OVER_STATUS\" onmouseover=\"showMenu(this.id);\"  style='color:".$STATUS_COLOR.";' title='".$CAL_TITLE."'>".$STATUS_FLAG_BLUE.$CONTENT."</a><br>";
      	  //$CAL_ARRAY[date("w",strtotime($CAL_TIME))==0 ? 6 : date("w",strtotime($CAL_TIME))-1][date("G",strtotime($CAL_TIME))].="<div id=\"div_".$CAL_ID."\" title='".$CAL_TITLE."'><span class=\"CalLevel".$CAL_LEVEL."\" title=\"".cal_level_desc($CAL_LEVEL)."\">".substr($CAL_TIME,11,5)."-".substr($END_TIME,11,5)."</span>&nbsp;<a id=\"cal_".$CAL_ID."\" href='javascript:my_note($CAL_ID);' onclick='CancelBuble(event);' status=\"$OVER_STATUS\" onmouseover=\"showMenu(this.id);\" style='color:".$STATUS_COLOR.";'>".$CONTENT."</a></div>";
      }
         
   }

}

//日常事务，按日提醒
$AFF_DIV_COLOR="#b2d1ec";
$query = "SELECT * from AFFAIR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER)) and BEGIN_TIME<='$CAL_TIME_CHANGE_E' and TYPE='2' order by BEGIN_TIME desc";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
while($ROW=mysql_fetch_array($cursor))
{
   $AFF_ID=$ROW["AFF_ID"];
   $BEGIN_TIME=$ROW["BEGIN_TIME"];
   $END_TIME=$ROW["END_TIME"];
   $REMIND_DATE=$ROW["REMIND_DATE"];
   $REMIND_TIME=$ROW["REMIND_TIME"];
   $CONTENT=$ROW["CONTENT"];
   $LAST_REMIND=$ROW["LAST_REMIND"];
   $TAKER=$ROW["TAKER"];
   $CONTENT=csubstr(strip_tags($CONTENT),0,80);
   if($LAST_REMIND=="0000-00-00")
      $LAST_REMIND="";
   if($END_TIME=="0000-00-00")
      $END_TIME="";
   $BEGIN_TIME=date("Y-m-d H:i:s",$BEGIN_TIME);
   $AFF_TITLE=_("提醒时间：每日 ").substr($REMIND_TIME,0,-3)."\n"._("上次提醒：").$LAST_REMIND."\n"._("起始时间：").$BEGIN_TIME;
   //$CONTENT="<div id=\"div_".$AFF_ID."\"  style='width:90%;background:".$AFF_DIV_COLOR."'>".substr($REMIND_TIME,0,-3)."&nbsp;<a href='javascript:my_aff_note($AFF_ID,1);' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' title='".$AFF_TITLE."'>".$CONTENT."</a></div>";
    $CONTENT=substr($REMIND_TIME,0,-3)."&nbsp;<a href='javascript:my_aff_note($AFF_ID,1,\"".$IS_MAIN."\");' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' title='".$AFF_TITLE."'>".$CONTENT."</a><br>";
   if($END_TIME!="" && $END_TIME!=0)
     $END_TIME=date("Y-m-d H:i:s",$END_TIME);
   $REMIND_HOUR=intval(substr($REMIND_TIME,0,strpos($REMIND_TIME,":")));

   for($I=0;$I< 7;$I++)
   {//如果起始时间大于这个时间，安排才生效
      if(substr($BEGIN_TIME, 0, 10)<=date("Y-m-d",$WEEK_BEGIN+$I*86400) && ($END_TIME=="" ||$END_TIME==0  || substr($END_TIME, 0, 10)>=date("Y-m-d",$WEEK_BEGIN+$I*86400)))
         $CAL_ARRAY[$I][$REMIND_HOUR].=$CONTENT;
   }
}

//日常事务，按周提醒
$query = "SELECT * from AFFAIR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER)) and BEGIN_TIME<='$CAL_TIME_CHANGE_E' and TYPE='3' and (END_TIME='' or END_TIME='0' or END_TIME>='$CAL_TIME_CHANGE_B') order by BEGIN_TIME desc";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
while($ROW=mysql_fetch_array($cursor))
{
   $AFF_ID=$ROW["AFF_ID"];
   $BEGIN_TIME=$ROW["BEGIN_TIME"];
   $END_TIME=$ROW["END_TIME"];
   if($END_TIME!=0 && $END_TIME!="")
     $END_TIME=date("Y-m-d H:i:s",$END_TIME);
   $REMIND_DATE=$ROW["REMIND_DATE"];
   $REMIND_TIME=$ROW["REMIND_TIME"];
   $CONTENT=$ROW["CONTENT"];
   $LAST_REMIND=$ROW["LAST_REMIND"];
   $TAKER=$ROW["TAKER"];
   $CONTENT=csubstr(strip_tags($CONTENT),0,80);
   if($LAST_REMIND=="0000-00-00")
      $LAST_REMIND="";
   if($REMIND_DATE==0)
      $REMIND_DATE_DESC=_("日");
   else if($REMIND_DATE==1)
      $REMIND_DATE_DESC=_("一");
   else if($REMIND_DATE==2)
      $REMIND_DATE_DESC=_("二");
   else if($REMIND_DATE==3)
      $REMIND_DATE_DESC=_("三");
   else if($REMIND_DATE==4)
      $REMIND_DATE_DESC=_("四");
   else if($REMIND_DATE==5)
      $REMIND_DATE_DESC=_("五");
   else if($REMIND_DATE==6)
      $REMIND_DATE_DESC=_("六");
   $BEGIN_TIME=date("Y-m-d H:i:s",$BEGIN_TIME);	  
   $AFF_TITLE=_("提醒时间：每周").$REMIND_DATE_DESC." ".substr($REMIND_TIME,0,-3)."\n"._("上次提醒：").$LAST_REMIND."\n"._("起始时间：").$BEGIN_TIME;
   //$CONTENT="<div id=\"div_".$AFF_ID."\"  style='width:90%;background:".$AFF_DIV_COLOR."'>".substr($REMIND_TIME,0,-3)."&nbsp;<a href='javascript:my_aff_note($AFF_ID,1);' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' title='".$AFF_TITLE."'>".$CONTENT."</a></div>";
   $CONTENT=substr($REMIND_TIME,0,-3)."&nbsp;<a href='javascript:my_aff_note($AFF_ID,1,\"".$IS_MAIN."\");' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' title='".$AFF_TITLE."'>".$CONTENT."</a><br>";
   $REMIND_HOUR=intval(substr($REMIND_TIME,0,strpos($REMIND_TIME,":")));
   if(substr($BEGIN_TIME, 0, 10)<=date("Y-m-d",$WEEK_BEGIN+$REMIND_DATE*86400))
      $CAL_ARRAY[$REMIND_DATE==0 ? 6 : $REMIND_DATE-1][$REMIND_HOUR].=$CONTENT;
}

//日常事务，按月提醒
$query = "SELECT * from AFFAIR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER)) and TYPE='4' and BEGIN_TIME<='$CAL_TIME_CHANGE_E' order by BEGIN_TIME desc";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
while($ROW=mysql_fetch_array($cursor))
{
   $AFF_ID=$ROW["AFF_ID"];
   $BEGIN_TIME=$ROW["BEGIN_TIME"];
   $BEGIN_TIME=date("Y-m-d H:i:s",$BEGIN_TIME);
   $END_TIME=$ROW["END_TIME"];
   if($END_TIME!=0 && $END_TIME!="")
      $END_TIME=date("Y-m-d H:i:s",$END_TIME);
   $REMIND_DATE=$ROW["REMIND_DATE"];
   $REMIND_TIME=$ROW["REMIND_TIME"];
   $CONTENT=$ROW["CONTENT"];
   $LAST_REMIND=$ROW["LAST_REMIND"];
   $TAKER=$ROW["TAKER"];  
   if(strtotime(date("Y-m-d",$WEEK_BEGIN)) >= strtotime(date("Y-m-",$WEEK_BEGIN).$REMIND_DATE) || strtotime(date("Y-m-d",$WEEK_END)) <= strtotime(date("Y-m-",$WEEK_BEGIN).$REMIND_DATE))
      continue;
   $CONTENTS=strip_tags($CONTENT);
   $CONTENT=csubstr(strip_tags($CONTENT),0,80);
   if($LAST_REMIND=="0000-00-00")
      $LAST_REMIND="";
   $AFF_TITLE=_("提醒时间：每月").$REMIND_DATE._("日 ").substr($REMIND_TIME,0,-3)."\n"._("上次提醒：").$LAST_REMIND."\n"._("起始时间：").$BEGIN_TIME;
   //$CONTENT="<div id=\"div_".$AFF_ID."\"  style='width:90%;background:".$AFF_DIV_COLOR."'>".substr($REMIND_TIME,0,-3)."&nbsp;<a href='javascript:my_aff_note($AFF_ID,1);' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' title='".$AFF_TITLE."'>".$CONTENT."</a></div>";
   $CONTENT=substr($REMIND_TIME,0,-3)."&nbsp;<a href='javascript:my_aff_note($AFF_ID,1,\"".$IS_MAIN."\");' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' title='".$AFF_TITLE."'>".$CONTENT."</a><br>";
   $REMIND_WEEK=date("w",strtotime(date("Y-m-",$WEEK_BEGIN).$REMIND_DATE));
   $REMIND_WEEK= $REMIND_WEEK==0 ? 6: $REMIND_WEEK-1;
   $REMIND_HOUR=intval(substr($REMIND_TIME,0,strpos($REMIND_TIME,":")));
   if(substr($BEGIN_TIME, 0, 10)<=date("Y-m-",$WEEK_BEGIN).$REMIND_DATE)
      $CAL_ARRAY[$REMIND_WEEK][$REMIND_HOUR].=$CONTENT;
}

//日常事务，按年提醒
$query = "SELECT * from AFFAIR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER)) and BEGIN_TIME<='$CAL_TIME_CHANGE_E' and TYPE='5' and (END_TIME='' or END_TIME='0' or END_TIME>='$CAL_TIME_CHANGE_B') order by BEGIN_TIME desc";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
while($ROW=mysql_fetch_array($cursor))
{
   $AFF_ID=$ROW["AFF_ID"];
   $BEGIN_TIME=$ROW["BEGIN_TIME"];
   $BEGIN_TIME=date("Y-m-d H:i:s",$BEGIN_TIME);
   $END_TIME=$ROW["END_TIME"];
   if($END_TIME!=0 && $END_TIME!="")
      $END_TIME=date("Y-m-d H:i:s",$END_TIME);
   $REMIND_DATE=$ROW["REMIND_DATE"];
   $REMIND_TIME=$ROW["REMIND_TIME"];
   $CONTENT=$ROW["CONTENT"];
   $TAKER=$ROW["TAKER"];  
   $LAST_REMIND=$ROW["LAST_REMIND"];

   if(strtotime(date("Y-n-j",$WEEK_BEGIN)) >= strtotime(date("Y-",$WEEK_BEGIN).$REMIND_DATE) || strtotime(date("Y-n-j",$WEEK_END)) <= strtotime(date("Y-",$WEEK_BEGIN).$REMIND_DATE))
      continue;

   $CONTENT=csubstr(strip_tags($CONTENT),0,80);
   if($LAST_REMIND=="0000-00-00")
      $LAST_REMIND="";
   $AFF_TITLE=_("提醒时间：每年").str_replace("-",_("月"),$REMIND_DATE)._("日 ").substr($REMIND_TIME,0,-3)."\n"._("上次提醒：").$LAST_REMIND."\n"._("起始时间：").$BEGIN_TIME;
   //$CONTENT="<div id=\"div_".$AFF_ID."\"  style='width:90%;background:".$AFF_DIV_COLOR."'>".substr($REMIND_TIME,0,-3)."&nbsp;<a href='javascript:my_aff_note($AFF_ID,1);' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' title='".$AFF_TITLE."'>".$CONTENT."</a></div>";
	 $CONTENT=substr($REMIND_TIME,0,-3)."&nbsp;<a href='javascript:my_aff_note($AFF_ID,1,\"".$IS_MAIN."\");' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' title='".$AFF_TITLE."'>".$CONTENT."</a><br>";
   $REMIND_WEEK=date("w",strtotime(date("Y-",$WEEK_BEGIN).$REMIND_DATE));
   $REMIND_WEEK= $REMIND_WEEK==0 ? 6: $REMIND_WEEK-1;
   $REMIND_HOUR=intval(substr($REMIND_TIME,0,strpos($REMIND_TIME,":")));
   if(substr($BEGIN_TIME, 0, 10)<=date("Y-",$WEEK_BEGIN).$REMIND_DATE)
      $CAL_ARRAY[$REMIND_WEEK][$REMIND_HOUR].=$CONTENT;
}
//日常事务，按工作日提醒
$query = "SELECT * from AFFAIR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER)) and BEGIN_TIME<='$CAL_TIME_CHANGE_E' and TYPE='6' and (END_TIME='' or END_TIME='0' or END_TIME>='$CAL_TIME_CHANGE_B') order by BEGIN_TIME desc";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
while($ROW=mysql_fetch_array($cursor))
{
   $AFF_ID=$ROW["AFF_ID"];
   $BEGIN_TIME=$ROW["BEGIN_TIME"];
   $END_TIME=$ROW["END_TIME"];
   $REMIND_DATE=$ROW["REMIND_DATE"];
   $REMIND_TIME=$ROW["REMIND_TIME"];
   $CONTENT=$ROW["CONTENT"];
   $LAST_REMIND=$ROW["LAST_REMIND"];
   $CONTENT=csubstr(strip_tags($CONTENT),0,80);
   $TAKER=$ROW["TAKER"];  

   if($LAST_REMIND=="0000-00-00")
      $LAST_REMIND="";
   if($END_TIME=="0000-00-00")
      $END_TIME="";
   $BEGIN_TIME=date("Y-m-d H:i:s",$BEGIN_TIME);	  
   $AFF_TITLE=_("提醒时间：每日 ").substr($REMIND_TIME,0,-3)."\n"._("上次提醒：").$LAST_REMIND."\n"._("起始时间：").$BEGIN_TIME;
   //$CONTENT="<div id=\"div_".$AFF_ID."\" style='width:90%;background:".$AFF_DIV_COLOR."'>".substr($REMIND_TIME,0,-3)."&nbsp;<a href='javascript:my_aff_note($AFF_ID,1);' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' title='".$AFF_TITLE."'>".$CONTENT."</a></div>";
   $CONTENT=substr($REMIND_TIME,0,-3)."&nbsp;<a href='javascript:my_aff_note($AFF_ID,1,\"".$IS_MAIN."\");' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' title='".$AFF_TITLE."'>".$CONTENT."</a><br>";
   if($END_TIME!="" && $END_TIME!=0)
     $END_TIME=date("Y-m-d H:i:s",$END_TIME);
   $REMIND_HOUR=intval(substr($REMIND_TIME,0,strpos($REMIND_TIME,":")));

   for($I=0;$I< 7;$I++)
   {//如果起始时间大于这个时间，安排才生效
      if(substr($BEGIN_TIME, 0, 10)<=date("Y-m-d",$WEEK_BEGIN+$I*86400) && date("w",$WEEK_BEGIN+$I*86400)!=0 && date("w",$WEEK_BEGIN+$I*86400)!=6 && ($END_TIME=="" ||$END_TIME==0  || substr($END_TIME, 0, 10)>=date("Y-m-d",$WEEK_BEGIN+$I*86400)))
         $CAL_ARRAY[$I][$REMIND_HOUR].=$CONTENT;
   }
}

?>

<div class="TableContainer" id="activity">
  <table id="cal_table" class="TableBlock" width="100%" align="center">
    <tr id="tbl_header" align="center" class="TableHeader">
     <td align="center" class="TableCorner" width="5%" style="background-image:url(<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif); background-position:0 13px; background-repeat:no-repeat;"  onclick="javascript:display_front();" title="<?=_("点击展开/收缩0-6点")?>"> <span width="100%"><?=_("0-6点")?></span></td>
      <? for($i=0; $i<7; $i++):?>
     <td <?if($i<5){?>width="15%"<?}else{?>width="10%"<?}?> id="th_<?=date("Y-m-d",$WEEK_BEGIN+$i*86400)?>"><a href="day.php?YEAR=<?=date("Y",$WEEK_BEGIN+$i*86400)?>&MONTH=<?=date("m",$WEEK_BEGIN+$i*86400)?>&DAY=<?=date("d",$WEEK_BEGIN+$i*86400)?>&OVER_STATUS=<?=$_GET['OVER_STATUS']?>"><?=$df->formatDate($WEEK_BEGIN+$i*86400,'brief');?>(<?=$local->getWeekDayName(date("w",$WEEK_BEGIN+$i*86400),"wide")?>)
        <? if($local->getLanguageID(MYOA_LANG_COOKIE) == 'zh'): ?>
        <br><font style=font-size:12px; title=<?=_("农历日期")?>><? $date = $lunar->convertSolarToLunar($WEEK_BEGIN+$i*86400); echo $date[1].$date[2];?></font></a></b></td>
        <? endif;?>
      <? endfor; ?>
    </tr>
 
<?
if(count($CAL_ALL_DAY)>0)
{

?>
   <tr onClick="if(option1.style.display=='none') option1.style.display=''; else option1.style.display='none';" title="<?=_("点击展开/收缩选项")?>" align="left">
    <td nowrap class="TableHeader"  colspan="8" style="cursor:pointer;background-image:url(<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif); background-position:0 6px; background-repeat:no-repeat;">&nbsp;&nbsp;<?=_("跨天日程")?></td>
  </tr>
    <tbody id="option1" style="display:'';">
    <tr class="TableData">
      <td class="TableLeft" align="center"><?=_("跨天")?></td>
      <td colspan="7" >
<?
   foreach($CAL_ALL_DAY as $ALL_DAY)
   {
      echo $ALL_DAY;

   }

?>
      </td>
    </tr>
  </tbody>
<?
}
?>

<?
$temp_display="display:none;";
for($I=0;$I< 7;$I++)
{
   for($J=0;$J< 7;$J++)
   {
      if($CAL_ARRAY[$I][$J]!="")
      {
         $temp_display="display:";
         break;
      }
   }
   if($temp_display=="display:")
      break;
}
?>
    <tbody id="front" style="<?=$temp_display?>">
<?
for($I=0;$I< 7;$I++)
{

?>
    <tr class="TableData" valign="top" height="40" id=<?=$I?>>
      <td align="center" class="TableLeft" width="5%"><?if($I< 10) echo "0";?><?=$I?>:00</td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+$I*3600?>" width="15%" <? if(date("w",$WEEK_BEGIN)==0 or date("w",$WEEK_BEGIN)==6){?> style="background-color:#ffdff9;" <? }?> ><?=$CAL_ARRAY[0][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+1*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+1*86400+$I*3600?>" width="15%" <? if(date("w",$WEEK_BEGIN+1*86400)==0 or date("w",$WEEK_BEGIN+1*86400)==6){?> style="background-color:#ffdff9;" <? }?> ><?=$CAL_ARRAY[1][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+2*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+2*86400+$I*3600?>" width="15%" <? if(date("w",$WEEK_BEGIN+2*86400)==0 or date("w",$WEEK_BEGIN+2*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[2][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+3*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+3*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+3*86400)==0 or date("w",$WEEK_BEGIN+3*86400)==6){?> style="background-color:#ffdff9;" <? }?> ><?=$CAL_ARRAY[3][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+4*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+4*86400+$I*3600?>" width="15%" <? if(date("w",$WEEK_BEGIN+4*86400)==0 or date("w",$WEEK_BEGIN+4*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[4][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+5*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+5*86400+$I*3600?>" width="10%" <? if(date("w",$WEEK_BEGIN+5*86400)==0 or date("w",$WEEK_BEGIN+5*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[5][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+6*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+6*86400+$I*3600?>" width="10%"<? if(date("w",$WEEK_BEGIN+6*86400)==0 or date("w",$WEEK_BEGIN+6*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[6][$I]?></td>
    </tr>
<?
}
?>
    </tbody>
 <?
for($I=7;$I< 13;$I++)
{
	 if($I==7){
?>   
    <tr class="TableData" valign="top" height="40" id=<?=$I?>>
      <td align="center" class="TableLeft" width="5%" style="background-image:url(<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif); background-position:0 4px; background-repeat:no-repeat;" onClick="javascript:display_front1();" title="<?=_("点击展开/收缩08-12点")?>" >&nbsp;<?if($I< 10) echo "0";?><?=$I?>:00</td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+$I*3600?>" width="15%" <? if(date("w",$WEEK_BEGIN)==0 or date("w",$WEEK_BEGIN)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[0][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+1*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+1*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+1*86400)==0 or date("w",$WEEK_BEGIN+1*86400)==6){?> style="background-color:#ffdff9;" <? }?> ><?=$CAL_ARRAY[1][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+2*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+2*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+2*86400)==0 or date("w",$WEEK_BEGIN+2*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[2][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+3*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+3*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+3*86400)==0 or date("w",$WEEK_BEGIN+3*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[3][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+4*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+4*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+4*86400)==0 or date("w",$WEEK_BEGIN+4*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[4][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+5*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+5*86400+$I*3600?>" width="10%" <? if(date("w",$WEEK_BEGIN+5*86400)==0 or date("w",$WEEK_BEGIN+5*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[5][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+6*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+6*86400+$I*3600?>" width="10%"<? if(date("w",$WEEK_BEGIN+6*86400)==0 or date("w",$WEEK_BEGIN+6*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[6][$I]?></td>
    </tr>
    
    <?
     }else
     {
     	if($I==8){
  ?>
  <tbody id="front1" style="display:'';">
  	<?}?>
  	    <tr class="TableData" valign="top" height="40" id=<?=$I?>>
      <td align="center" class="TableLeft" width="5%">&nbsp;<?if($I< 10) echo "0";?><?=$I?>:00</td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+$I*3600?>" width="15%" <? if(date("w",$WEEK_BEGIN)==0 or date("w",$WEEK_BEGIN)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[0][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+1*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+1*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+1*86400)==0 or date("w",$WEEK_BEGIN+1*86400)==6){?> style="background-color:#ffdff9;" <? }?> ><?=$CAL_ARRAY[1][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+2*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+2*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+2*86400)==0 or date("w",$WEEK_BEGIN+2*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[2][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+3*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+3*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+3*86400)==0 or date("w",$WEEK_BEGIN+3*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[3][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+4*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+4*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+4*86400)==0 or date("w",$WEEK_BEGIN+4*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[4][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+5*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+5*86400+$I*3600?>" width="10%"<? if(date("w",$WEEK_BEGIN+5*86400)==0 or date("w",$WEEK_BEGIN+5*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[5][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+6*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+6*86400+$I*3600?>" width="10%"<? if(date("w",$WEEK_BEGIN+6*86400)==0 or date("w",$WEEK_BEGIN+6*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[6][$I]?></td>
    </tr>
    <?
    if($I==12){
    ?>
    </tbody>
<?
		}
	}
}
for($I=13;$I< 19;$I++)
{
	    if($I==13){
?>

    <tr class="TableData" valign="top" height="40" id=<?=$I?>>
     <td align="center" class="TableLeft" width="5%" style="background-image:url(<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif); background-position:0 4px; background-repeat:no-repeat;" onClick="javascript:display_front2();" title="<?=_("点击展开/收缩14-18点")?>" >&nbsp;<?if($I< 10) echo "0";?><?=$I?>:00</td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+$I*3600?>" width="15%" <? if(date("w",$WEEK_BEGIN)==0 or date("w",$WEEK_BEGIN)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[0][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+1*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+1*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+1*86400)==0 or date("w",$WEEK_BEGIN+1*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[1][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+2*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+2*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+2*86400)==0 or date("w",$WEEK_BEGIN+2*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[2][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+3*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+3*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+3*86400)==0 or date("w",$WEEK_BEGIN+3*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[3][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+4*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+4*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+4*86400)==0 or date("w",$WEEK_BEGIN+4*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[4][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+5*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+5*86400+$I*3600?>" width="10%"<? if(date("w",$WEEK_BEGIN+5*86400)==0 or date("w",$WEEK_BEGIN+5*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[5][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+6*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+6*86400+$I*3600?>" width="10%" <? if(date("w",$WEEK_BEGIN+6*86400)==0 or date("w",$WEEK_BEGIN+6*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[6][$I]?></td>
    </tr>
    <?
      }
      else
      {
      	if($I==14){
    ?>
       <tbody id="front2" style="display:'';">
  	    <?}?>
  	  <tr class="TableData" valign="top" height="40" id=<?=$I?>>
      <td align="center" class="TableLeft" width="5%">&nbsp;<?if($I< 10) echo "0";?><?=$I?>:00</td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+$I*3600?>" width="15%" <? if(date("w",$WEEK_BEGIN)==0 or date("w",$WEEK_BEGIN)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[0][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+1*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+1*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+1*86400)==0 or date("w",$WEEK_BEGIN+1*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[1][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+2*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+2*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+2*86400)==0 or date("w",$WEEK_BEGIN+2*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[2][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+3*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+3*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+3*86400)==0 or date("w",$WEEK_BEGIN+3*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[3][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+4*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+4*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+4*86400)==0 or date("w",$WEEK_BEGIN+4*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[4][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+5*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+5*86400+$I*3600?>" width="10%"<? if(date("w",$WEEK_BEGIN+5*86400)==0 or date("w",$WEEK_BEGIN+5*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[5][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+6*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+6*86400+$I*3600?>" width="10%" <? if(date("w",$WEEK_BEGIN+6*86400)==0 or date("w",$WEEK_BEGIN+6*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[6][$I]?></td>
    </tr>
       <?
    if($I==18){
    ?>
    </tbody> 
<?
		}
	}
}

for($I=19;$I< 24;$I++)
{ 
	  if($I==19)
	  {
?>

    <tr class="TableData" valign="top" height="40" id=<?=$I?>>
      <td align="center" class="TableLeft" width="5%" style="background-image:url(<?=MYOA_STATIC_SERVER?>/static/images/green_arrow.gif); background-position:0 4px; background-repeat:no-repeat;" onClick="javascript:display_front3();" title="<?=_("点击展开/收缩20-23点")?>">&nbsp;<?if($I< 10) echo "0";?><?=$I?>:00</td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+$I*3600?>" width="15%" <? if(date("w",$WEEK_BEGIN)==0 or date("w",$WEEK_BEGIN)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[0][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+1*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+1*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+1*86400)==0 or date("w",$WEEK_BEGIN+1*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[1][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+2*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+2*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+2*86400)==0 or date("w",$WEEK_BEGIN+2*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[2][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+3*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+3*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+3*86400)==0 or date("w",$WEEK_BEGIN+3*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[3][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+4*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+4*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+4*86400)==0 or date("w",$WEEK_BEGIN+4*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[4][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+5*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+5*86400+$I*3600?>" width="10%"<? if(date("w",$WEEK_BEGIN+5*86400)==0 or date("w",$WEEK_BEGIN+5*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[5][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+6*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+6*86400+$I*3600?>" width="10%"<? if(date("w",$WEEK_BEGIN+6*86400)==0 or date("w",$WEEK_BEGIN+6*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[6][$I]?></td>
    </tr>
    <?
    }else
    {  if($I==20)
    	{
    ?>
    		<tbody id="front3" style="display:none;">
    	<?
      }
    	?>
    	<tr class="TableData" valign="top" height="40" id=<?=$I?>>
      <td align="center" class="TableLeft" width="5%">&nbsp;<?if($I< 10) echo "0";?><?=$I?>:00</td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN) echo " TableRowHover";?> " id="td_<?=$WEEK_BEGIN+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN)==0 or date("w",$WEEK_BEGIN)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[0][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+1*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+1*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+1*86400)==0 or date("w",$WEEK_BEGIN+1*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[1][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+2*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+2*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+2*86400)==0 or date("w",$WEEK_BEGIN+2*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[2][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+3*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+3*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+3*86400)==0 or date("w",$WEEK_BEGIN+3*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[3][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+4*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+4*86400+$I*3600?>" width="15%"<? if(date("w",$WEEK_BEGIN+4*86400)==0 or date("w",$WEEK_BEGIN+4*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[4][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+5*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+5*86400+$I*3600?>" width="10%"<? if(date("w",$WEEK_BEGIN+5*86400)==0 or date("w",$WEEK_BEGIN+5*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[5][$I]?></td>
      <td class="timeItem <?if($DATE==$WEEK_BEGIN+6*86400) echo " TableRowHover";?>" id="td_<?=$WEEK_BEGIN+6*86400+$I*3600?>" width="10%"<? if(date("w",$WEEK_BEGIN+6*86400)==0 or date("w",$WEEK_BEGIN+6*86400)==6){?> style="background-color:#ffdff9;" <? }?>><?=$CAL_ARRAY[6][$I]?></td>
    </tr>
    <? if($I==23){?>
    </tbody>
<?
		}
	}
}
?>
  </table>
</div>
<?=$OP_MENU?>
<script>var cal_div_array = new Array(<?=trim(trim($CAL_DIV_ARRAY),",")?>);
function bb(){
	var drag=jQuery("#DRAG").val();
	var selectItemId=jQuery("#selectItemId").val();
	aa(drag,selectItemId);
	close_modify(drag);
	}
</script>

<div id="overlay"></div>
<div id="form_div" class="ModalDialog" style="width:500px;" >
  <div class="header"><span id="title" class="title"><?=_("新建日程")?></span><a class="operation" href="javascript:bb();"><img src="<?=MYOA_STATIC_SERVER?>/static/images/close.png"/></a></div>
  <div id="form_body" class="body" style="text-align:center;height:352px;overflow-y:auto;">

  </div>
</div>
<iframe name="form_iframe" id="form_iframe" style="display:none;"></iframe>
</body>
</html>

