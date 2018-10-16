<?
include_once("../date_change.class.php");
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
//2013-4-11 主服务查询
if($IS_MAIN!="" && $IS_MAIN=="1?IS_MAIN=1")
	$IS_MAIN=1;
if($IS_MAIN==1)
	$QUERY_MASTER=true;
else
   $QUERY_MASTER="";   
$CUR_YEAR = date('Y');
$CUR_MON = date('m');
$CUR_DAY = date('d');
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


$DATE_STR=$YEAR."-".$MONTH."-".$DAY;
$DATE=strtotime($DATE_STR);
$WEEK=date("W",$DATE);
$WEEK_BEGIN=strtotime("-".(date("w",$DATE)==0 ? 6 : date("w",$DATE)-1)."days",$DATE);
$MONTH_BEGIN=strtotime($YEAR."-".$MONTH."-01");
$CUR_TIME=date("Y-m-d H:i:s",time());
$CONDITION_STR="";
$CUR_TIME_U=time();
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
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery-ui.custom.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.ui.draggable.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.ui.droppable.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
jQuery(document).ready(function(){
    	var activityHelper = new ActivityHelper({
    		renderTo : 'activity',
    		loadDate : '2012-08-15'
    		});
    		init();
      	jQuery(".drag").bind("mouseover",function(event){
		 // activityHelper.stopmouse();  
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
			 event.stopPropagation(); 
		}
		
		});
		jQuery('.ui-draggable').mousedown(function(){
			activityHelper.stopmouse(); 
		
		});
		
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
function bb(){
	var drag=jQuery("#DRAG").val();
	var selectItemId=jQuery("#selectItemId").val();
	aa(drag,selectItemId);
	close_modify(drag);
	}
function init()
{
   /*var elementI=document.getElementsByTagName("TR");
   for(i=0;i<elementI.length;i++)
   {
      if(elementI[i].id.substr(0,3)!="tr_")
         continue;
       elementI[i].onmouseover=function(){ 
          	   this.title='<?=_("单击建立日事务")?>';
          	};
     
      elementI[i].onclick =function() {new_cal(this.id.substr(3));};
   }
   var elementI=document.getElementsByTagName("td");
   for(i=0;i<elementI.length;i++)
   {
      if(elementI[i].id.substr(0,3)!="td_")
         continue;
      
      elementI[i].onmouseover=function(){ 
          	   this.style.cursor='crosshair';
          	   this.title='<?=_("单击建立日事务")?>';
          	};
       elementI[i].onmouseout=function(){
          		 this.style.backgroundImage = '';
          	}; 
      elementI[i].onclick =function() {new_cal(this.id.substr(3));};
   }
   */
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
<body class="bodycolor" onselectstart="return false"  style="-moz-user-select: none;" >
<div class="PageHeader calendar_icon">
 <div class="header-left">
   <form name="form1" action="<?=$_SERVER["SCRIPT_NAME"]?>" style="margin-bottom:5px;">
   <input type="hidden" value="" name="BTN_OP">
   <input type="hidden" value="<?=$_GET['OVER_STATUS']?>" name="OVER_STATUS">
   <a href="<?=$_SERVER["SCRIPT_NAME"]?>?YEAR=<?=$CUR_YEAR?>&MONTH=<?=$CUR_MON?>&DAY=<?=$CUR_DAY?>" class="ToolBtn ToolBtn-active"><span><?=_("今天")?></span></a>
<!-------------- 年 ------------>
   <a href="javascript:set_year(-1);" class="ArrowButtonL" title="<?=_("上一年")?>"></a><select name="YEAR" class="SmallSelect" onChange="My_Submit();">
<?
   for($I=2000;$I<=2030;$I++)
   {
?>
     <option value="<?=$I?>" <? if($I==$YEAR) echo "selected";?>><?=$I?><?=_("年")?></option>
<?
   }
?>
   </select><a href="javascript:set_year(1);" class="ArrowButtonR" title="<?=_("下一年")?>"></a>

<!-------------- 月 ------------>
   <a href="javascript:set_mon(-1);" class="ArrowButtonL" title="<?=_("上一月")?>"></a><select name="MONTH" class="SmallSelect" onChange="My_Submit();">
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
   </select><a href="javascript:set_mon(1);" class="ArrowButtonR" title="<?=_("下一月")?>"></a>
<!-------------- 日 ------------>
   <a href="javascript:set_day(-1);" class="ArrowButtonL" title="<?=_("上一天")?>"></a><select name="DAY" class="SmallSelect" onChange="My_Submit();">
<?
   for($I=1;$I<=date("t",$DATE);$I++)
   {
      if($I<10)
         $I="0".$I;
?>
      <option value="<?=$I?>" <? if($I==$DAY) echo "selected";?>><?=$I?><?=_("日")?></option>
<?
   }
?>
   </select><a href="javascript:set_day(1);" class="ArrowButtonR" title="<?=_("下一天")?>"></a>
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
 $CAL_ALL_DAY=array();
 
 //============================ 显示日程安排 =======================================
 $query = "SELECT * from CALENDAR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OWNER))".$CONDITION_STR." and to_days(from_unixtime(CAL_TIME))<=to_days('$YEAR-$MONTH-$DAY') and to_days(from_unixtime(END_TIME))>=to_days('$YEAR-$MONTH-$DAY') order by CAL_TIME";
 $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
 $CAL_COUNT=0;

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
   //$CAL_TYPE_DESC=$CODE_NAME[$CAL_TYPE]._("：");
   $CAL_TITLE=_("类型：").$CODE_NAME[$CAL_TYPE]."\n";
       if($CAL_LEVEL==1) //如果是重要紧急 红色
   {
   	 $AFF_DIV_COLOR="#ff0000";
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
    $MANAGER_NAME="";
    if($MANAGER_ID!="")
    {
       if(!array_key_exists($MANAGER_ID, $MANAGER))
       {
          $query = "SELECT USER_NAME from USER where USER_ID='$MANAGER_ID'";
          $cursor1= exequery(TD::conn(),$query);
          if($ROW1=mysql_fetch_array($cursor1))
             $MANAGER[$MANAGER_ID]=$ROW1["USER_NAME"];
       }
       $MANAGER_NAME="("._("安排人:").$MANAGER[$MANAGER_ID].")";
    }
    
    if($MANAGER_ID!=""&&$MANAGER_ID!=$_SESSION["LOGIN_USER_ID"])
      $CONTENT="<b>".csubstr(strip_tags($CONTENT),0,100)."</b>";
    else
      $CONTENT=csubstr(strip_tags($CONTENT),0,100);
    
    if(substr($CAL_TIME,0,10) != substr($END_TIME,0,10))
    {
       $ALL_DAY="<div id=\"div_".$CAL_ID."\" title='".$CAL_TITLE."'>";
       if(substr($CAL_TIME,0,10) < $DATE_STR)
          $ALL_DAY.="<a href=\"javascript:set_day(-1);\" title=\""._("上一天")."\">".menu_arrow("LEFT")."</a> ";
      // $ALL_DAY.="<div style='width:30%;background:".$AFF_DIV_COLOR."'><span class=\"CalLevel".$CAL_LEVEL."\" title=\"".cal_level_desc($CAL_LEVEL)."\">".substr($CAL_TIME,0,16)." - ".substr($END_TIME,0,16)."</span> ".$CAL_TYPE_DESC."<a id=\"cal_".$CAL_ID."\" href='javascript:my_note($CAL_ID);' onclick='cancelevent(event);'  onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' onmouseover=\"showMenu(this.id);\" >".$CONTENT."</a> ".$MANAGER_NAME."</div>";
         $ALL_DAY.="<span class=\"CalLevel".$CAL_LEVEL."\" >".substr($CAL_TIME,0,16)." - ".substr($END_TIME,0,16)."</span> <a id=\"cal_".$CAL_ID."\" href='javascript:my_note($CAL_ID,\"".$IS_MAIN."\");' onclick='CancelBuble(event);' onmouseover=\"showMenu(this.id);\" style='color:".$STATUS_COLOR.";'>".$STATUS_FLAG_BLUE.$CONTENT."</a> ".$MANAGER_NAME;
       if(substr($END_TIME,0,10) > $DATE_STR)
          $ALL_DAY.="<a href=\"javascript:set_day(1);\" title=\""._("下一天")."\">".menu_arrow("RIGHT")."</a>";
       $ALL_DAY.="</div>\n";
       $CAL_ALL_DAY[]=$ALL_DAY;
    }
    else
    {
    	 if($EDIT_FLAG==1)
          $CAL_ARRAY[date("G",strtotime($CAL_TIME))].="<div  style='width:30%;float:left;background:".$AFF_DIV_COLOR."' class='drag'  id=\"div_".$CAL_ID."\" title='".$CAL_TITLE."'><span  >".substr($CAL_TIME,11,5)." - ".substr($END_TIME,11,5)."</span> <a id=\"cal_".$CAL_ID."\" href='javascript:my_note($CAL_ID,\"".$IS_MAIN."\");' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' status=\"$OVER_STATUS\" onmouseover=\"showMenu(this.id);\" style='color:#000000'>".$STATUS_FLAG.$CONTENT."</a> ".$MANAGER_NAME."</div>";
       else
          $CAL_ARRAY[date("G",strtotime($CAL_TIME))].="<span >".substr($CAL_TIME,11,5)." - ".substr($END_TIME,11,5)."</span> <a id=\"cal_".$CAL_ID."\" href='javascript:my_note($CAL_ID,\"".$IS_MAIN."\");' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' status=\"$OVER_STATUS\" onmouseover=\"showMenu(this.id);\" >".$STATUS_FLAG_BLUE.$CONTENT."</a> ".$MANAGER_NAME."<br>";
    }
   
}
//时间戳转换 7-1
$BEGIN_TIME_CHANGE_B=date("Y-m-d",$DATE)." 00:00:00";
$BEGIN_TIME_CHANGE_B=strtotime($BEGIN_TIME_CHANGE_B);
$BEGIN_TIME_CHANGE_E=date("Y-m-d",$DATE)." 23:59:59";
$BEGIN_TIME_CHANGE_E=strtotime($BEGIN_TIME_CHANGE_E);
//事务查询
$AFF_DIV_COLOR="#b2d1ec";
$query = "SELECT * from AFFAIR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER)) and BEGIN_TIME<='$BEGIN_TIME_CHANGE_E' and (END_TIME='' or END_TIME='0' or END_TIME>='$BEGIN_TIME_CHANGE_B') and (TYPE='2' or TYPE='3' and REMIND_DATE='".date("w",$DATE)."' or TYPE='6' or  TYPE='4' and REMIND_DATE='".date("j",$DATE)."' or TYPE='5' and REMIND_DATE='".date("n-j",$DATE)."') order by BEGIN_TIME desc";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
while($ROW=mysql_fetch_array($cursor))
{
   $AFF_ID=$ROW["AFF_ID"];
   $BEGIN_TIME=$ROW["BEGIN_TIME"];
   $BEGIN_TIME=date("Y-m-d H:i:s",$BEGIN_TIME);
   $END_TIME=$ROW["END_TIME"];
   if($END_TIME!=0)
      $END_TIME=date("Y-m-d H:i:s",$END_TIME);
   $REMIND_DATE=$ROW["REMIND_DATE"];
   $REMIND_TIME=$ROW["REMIND_TIME"];
   $CONTENT=$ROW["CONTENT"];
   $TYPE=$ROW["TYPE"];
   $LAST_REMIND=$ROW["LAST_REMIND"];
   $CONTENT=csubstr(strip_tags($CONTENT),0,100);
   if($LAST_REMIND=="0000-00-00")
      $LAST_REMIND="";
   if($TYPE==6 && (date("w",$DATE)==0 || date("w",$DATE)==6))
    continue;
   
   switch($TYPE)
   {
     case "2":
         $TYPE_DESC=_("每日");
         break;
     case "3":
         $TYPE_DESC="";
         if($REMIND_DATE=="1")
            $REMIND_DATE=_("每周一");
         elseif($REMIND_DATE=="2")
            $REMIND_DATE=_("每周二");
         elseif($REMIND_DATE=="3")
            $REMIND_DATE=_("每周三");
         elseif($REMIND_DATE=="4")
            $REMIND_DATE=_("每周四");
         elseif($REMIND_DATE=="5")
            $REMIND_DATE=_("每周五");
         elseif($REMIND_DATE=="6")
            $REMIND_DATE=_("每周六");
         elseif($REMIND_DATE=="0")
            $REMIND_DATE=_("每周日");
         break;
     case "4":
         $TYPE_DESC=_("每月");
         $REMIND_DATE.=_("日");
         break;
     case "5":
         $TYPE_DESC=_("每年");
         $REMIND_DATE=str_replace("-",_("月"),$REMIND_DATE)._("日");
         break;
     case "6":
         $TYPE_DESC=_("工作日");
         break;
   }
   $AFF_TITLE=_("提醒时间：").$TYPE_DESC.$REMIND_DATE." ".substr($REMIND_TIME,0,-3)."\n"._("上次提醒：").$LAST_REMIND."\n"._("起始时间：").$BEGIN_TIME;
   //$CONTENT="<div  style='width:30%;float:left;background:".$AFF_DIV_COLOR."'  id=\"div_".$CAL_ID."\" title='".$CAL_TITLE."'>".substr($REMIND_TIME,0,-3)." <a href='javascript:my_aff_note($AFF_ID,1);' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' title='".$AFF_TITLE."'>".$CONTENT."</a></div>";
   $CONTENT=substr($REMIND_TIME,0,-3)." <a href='javascript:my_aff_note($AFF_ID,1,\"".$IS_MAIN."\");' onclick='cancelevent(event);' onmousedown='cancelevent(event);' onmouseup='cancelevent(event);' title='".$AFF_TITLE."'>".$CONTENT."</a><br>";

   $REMIND_HOUR=intval(substr($REMIND_TIME,0,strpos($REMIND_TIME,":")));
   if($TYPE!=6)
   {
      if(substr($BEGIN_TIME, 0, 10)<=date("Y-m-d",$DATE))
         $CAL_ARRAY[$REMIND_HOUR].=$CONTENT;
   }
   else
   {
      if(substr($BEGIN_TIME, 0, 10)<=date("Y-m-d",$DATE) && date("w",$DATE)!=0 && date("w",$DATE)!=1 )
         $CAL_ARRAY[$REMIND_HOUR].=$CONTENT;   	
   }   
}
?>
<div id="activity">
  <table class="TableList" style="width:95%;margin-left:5%;" align="center" >
    <tr align="center" class="TableHeader">
      <td width="9%" class="TableCorner"><a href="javascript:display_front();"><?=_("0-6点")?></a></td>
      <td onClick="new_cal(<?=$DATE?>,'+1 days');" title="<?=_("单击建立日事务")?>"><?=date(_("Y年m月d日"),$DATE)?>(<?=get_week(date("Y-m-d",$DATE));
	  ?>) <? $date = $lunar->convertSolarToLunar($DATE); echo  $date[1].$date[2]._("(农历)");?></td>
    </tr>
<?
if(count($CAL_ALL_DAY)>0)
{
?>
    <tr class="TableData">
      <td class="TableLeft" align="center"><?=_("跨天")?></td>
      <td>
<?
   foreach($CAL_ALL_DAY as $ALL_DAY)
     echo $ALL_DAY;
?>
     </td>
    </tr>
<?
}
?>
<? 
$temp_display="display:none;";
for($I=0;$I< 7;$I++)
{
   if($CAL_ARRAY[$I]!="")
   {
      $temp_display="display:";
      break;
   }  
}   
?>
    <tbody id="front" style="<?=$temp_display?>">
<?
for($I=0;$I< 7;$I++)
{
?>
    <tr id="tr_<?=$DATE+$I*3600?>" class="TableData" height="30">
      <td class="TableLeft" align="center" width="9%"><?if($I< 10) echo "0";?><?=$I?>:00</td>
      <td id="td_<?=$DATE+$I*3600?>"><?=$CAL_ARRAY[$I]?></td>
    </tr>
<?
}
?>
    </tbody>
<?
for($I=7;$I< 24;$I++)
{
?>
    <tr id="<?=$I?>" class="TableData" height="30">
      <td class="TableLeft" align="center" width="9%"><?if($I< 10) echo "0";?><?=$I?>:00</td>
      <td id="td_<?=$DATE+$I*3600?>" class="timeItem"><?=$CAL_ARRAY[$I]?></td>
    </tr>
<?
}
?>
  </table>
</div>
<?=$OP_MENU?>

<div id="overlay"></div>
<div id="form_div" class="ModalDialog" style="width:500px;">
  <div class="header"><span id="title" class="title"><?=_("新建日程")?></span><a class="operation" href="javascript:bb();"><img src="<?=MYOA_STATIC_SERVER?>/static/images/close.png"/></a></div>
  <div id="form_body" class="body" style="text-align:center;height:350px;overflow-y:auto;">     
  </div>
</div>
<iframe name="form_iframe" id="form_iframe" style="display:none;"></iframe>
</body>
</html>
