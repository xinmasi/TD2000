<?
if($_COOKIE["cal_info_view"]=="day" || $_COOKIE["cal_info_view"]=="month")
   header("location:".$_COOKIE["cal_info_view"].".php");

include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("check_priv.inc.php");
include_once("../date_change.class.php");

//2013-4-11 主服务查询
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

$DATE=strtotime($YEAR."-".$MONTH."-".$DAY);
$WEEK=date("W",$DATE);
$WEEK = ($WEEK=="1" && $MONTH=="12") ? "53" : $WEEK;
$WEEK_BEGIN=strtotime("-".(date("w",$DATE)==0 ? 6 : date("w",$DATE)-1)."days",$DATE);
$WEEK_END=strtotime("+6 days",$WEEK_BEGIN);

$CUR_TIME=date("Y-m-d H:i:s",time());
$CUR_TIME1=time();
$CONDITION_STR="";
if($_GET['OVER_STATUS']=="1")
{
   $CONDITION_STR.=" and OVER_STATUS='0' and CAL_TIME>'$CUR_TIME1'";
   $state="and BEGIN_TIME>'$CUR_TIME1'";
   $task_status="and TASK_STATUS = '1'";
   $STATUS_DESC="<font color=''>"._("未开始")."</font>";
}
else if($_GET['OVER_STATUS']=="2")
{
   $CONDITION_STR.=" and OVER_STATUS='0' and CAL_TIME<='$CUR_TIME1' and END_TIME>='$CUR_TIME1'";
   $state="and BEGIN_TIME<='$CUR_TIME1' and END_TIME > '$CUR_TIME1'";
   $task_status="and TASK_STATUS = '2'";
   $calendar_status = "and OVER_STATUS = '0'";
   $STATUS_DESC="<font color=''>"._("进行中")."</font>";
}
else if($_GET['OVER_STATUS']=="3")
{
   $CONDITION_STR.=" and OVER_STATUS='0' and END_TIME<'$CUR_TIME1'";
   $task_status = "and TASK_STATUS in (4,5)";
   $STATUS_DESC="<font color=''>"._("已超时")."</font>";
}
else if($_GET['OVER_STATUS']=="4")
{
   $CONDITION_STR.=" and OVER_STATUS='1'";
   $state="and  END_TIME < '$CUR_TIME1'";
   $task_status = "and TASK_STATUS = '3'";
   $calendar_status = "and OVER_STATUS = '1'";
   $STATUS_DESC="<font color=''>"._("已完成")."</font>";
}
else
{
   $STATUS_DESC=_("全部");
}

$HTML_PAGE_TITLE = _("日程安排查询");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/calendar/css/calendar_person.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/fullcalendar/fullcalendar.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<?
if(MYOA_IS_UN && find_id("zh-TW,en,", MYOA_LANG_COOKIE) && find_id(MYOA_FASHION_THEME, $_SESSION["LOGIN_THEME"]))
{
?>
   <link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/un_<?=MYOA_LANG_COOKIE?>.css" />
<?
}
?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.js"></script>
<script>
if(window.external && typeof window.external.OA_SMS != 'undefined')
{
    var h = Math.min(800, screen.availHeight - 180),
        w = Math.min(1280, screen.availWidth - 180);
    window.external.OA_SMS(w, h, "SET_SIZE");
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
         if(td.id.substr(0,3)=="th_")
         {
            td.onclick =function() {set_data(this.id);};
            td.onclick =function() {new_arrange(user_id_array, this.id.substr(3), '+1 days');};
            td.title="<?=_("单击为下面所有人员建立日事务")?>";
         }
         else if(td.id.substr(0,3)=="tr_")
         {
            td.onclick =function() {new_arrange(user_id_array[this.id.substr(3)],'<?=$WEEK_BEGIN?>' , '+1 weeks');};
            td.title="<?=_("单击建立周事务")?>";
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

      var caldiv = $("div_"+cal_div_array[i][0]).parentNode;
      caldiv.style.left=left+"px";
      caldiv.style.width=width+"px";
      caldiv.style.position="relative";
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
function show_menu()
{
    if(document.getElementById("status_menu").style.display=="none")
    {
        document.getElementById("status_menu").style.display="block";
    }
    else if(document.getElementById("status_menu").style.display=="block")
    {
         document.getElementById("status_menu").style.display="none";
    }
}
jQuery(document).ready(function(){
	jQuery("div[id^=new_affair_]").each(function(i){
	    jQuery(this).parent().mouseover(function(){
	        jQuery("div[id=new_affair_"+(i+1)+"]").css("visibility","visible");
	    })
        jQuery(this).parent().mouseout(function(){
        	jQuery("div[id^=new_affair_]").css("visibility","hidden");
        })
        jQuery("div[id^=new_affair_] a").hover(function(){
        	jQuery("div[id=new_affair_"+(i+1)+"] a").css({border:"#000",color:"#000"});
        	this.style.cursor='pointer';
        },function(){
        	jQuery("div[id=new_affair_"+(i+1)+"] a").css({border:"#999",color:"#686868"});
        })
	})
});
</script>

<body class="bodycolor" onLoad="init();">
<div class="PageHeader calendar_icon page_top">
 <div class="header-left">
   <form name="form1" action="<?=$_SERVER["SCRIPT_NAME"]?>" style="margin-bottom:5px;" class="form-inline">
   <input type="hidden" value="" name="BTN_OP">
   <input type="hidden" value="<?=$_GET['OVER_STATUS']?>" name="OVER_STATUS">
   <input type="hidden" value="<?=$MONTH?>" name="MONTH">
   <input type="hidden" value="<?=$DAY?>" name="DAY">
   <button type="button" onClick="javascript:window.location.href='<?=$_SERVER["SCRIPT_NAME"]?>?YEAR=<?=$CUR_YEAR?>&MONTH=<?=$CUR_MON?>&DAY=<?=$CUR_DAY?>'" class="btn"><?=_("今天")?></button>
<!-------------- 年 ------------>
   <a href="javascript:set_year(-1);" class="ArrowButtonLL" title="<?=_("上一年")?>"></a>
   <a href="javascript:set_week(-1);" class="ArrowButtonL" title="<?=_("上一周")?>"></a>
   <select name="YEAR" onChange="My_Submit();" class="smallSelect">
<?
   if(isset($WEEK) && $WEEK=='53'){
       $YEAR = $YEAR-1;
   }
   for($I=2000;$I<=2030;$I++)
   {
?>
      <option value="<?=$I?>" <? if($I==$YEAR) echo "selected";?>><?=$I?><?=_("年")?></option>
<?
   }
?>
        </select>
<!-------------- 周 ------------>
   <select name="WEEK" onChange="set_week(this.value-<?=$WEEK?>);" class="smallSelect">
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
   <a href="javascript:set_year(1);" class="ArrowButtonRR" title="<?=_("下一年")?>"></a>
   <div class="btn-group" style=" display:inline">
   <button type="button" id="status" class="btn"><span><?=$STATUS_DESC?></span></button>
   <button type="button" class="btn dropdown-toggle"  onclick="show_menu();"><b class="caret"></b></button>
   <ul id="status_menu" class="dropdown-menu" style="display:none;top:12px;">
      <li><a href="javascript:set_status_index('');"><?=_("全部")?></a></li>
      <li><a href="javascript:set_status_index(1);" style="color:;"><?=_("未开始")?></a></li>
      <li><a href="javascript:set_status_index(2);" style="color:;"><?=_("进行中")?></a></li>
      <li><a href="javascript:set_status_index(3);" style="color:;"><?=_("已超时")?></a></li>
      <li><a href="javascript:set_status_index(4);" style="color:;"><?=_("已完成")?></a></li>
   </ul>
   </div>
 </div>
 <div class="header-right form-inline">
 	<button type="button" onClick="javascript:window.location.href='query.php?DEPT_ID=<?=$DEPT_ID?>'"  class="btn btn-primary"><?=_("查询")?></button>
<?
if($DEPT_PRIV!="3")
{
?>
   <select name="DEPT_ID" onChange="My_Submit();" style="width:130px;">
<?=my_dept_tree(0,$DEPT_ID,array("DEPT_PRIV" => $DEPT_PRIV,"DEPT_ID_STR" => $DEPT_ID_STR));?>
   </select>
<?
}
?>
   <div class="btn-group" style="display:inline">
       <button type="button" onClick="set_view('day','cal_info_view');" class="btn"><?=_("日")?></button>
       <button type="button" onClick="set_view('index','cal_info_view');" class="btn btn-info"><?=_("周")?></button>
       <button type="button" onClick="set_view('month','cal_info_view');" class="btn"><?=_("月")?></button>
   </div>
   </form>
 </div>
</div>
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
$CODE_NAME=$MANAGER=$USERS=array();
$USER_ID_STR="";
//用户角色类型
if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION['LOGIN_USER_PRIV_TYPE']!="1")
{
    $dept_id = get_manage_dept_ids($_SESSION['LOGIN_UID']);
    if($dept_id)
    {
        $dept_str = $dept_id;
    }
    else
    {
        $dept_str = $_SESSION["LOGIN_DEPT_ID"];
    }
    $UID = rtrim(GetUidByOther('','',$dept_str),",");
    $user_id = rtrim(GetUserIDByUid($UID),",");
    if($user_id != "") {
        $query = "SELECT USER_ID,USER_NAME from USER,USER_PRIV where find_in_set(USER.USER_ID,'".$user_id."') and USER.USER_PRIV=USER_PRIV.USER_PRIV and (USER.NOT_LOGIN='0' or USER.NOT_MOBILE_LOGIN='0') ".$WHERE_STR." order by PRIV_NO,USER_NO,USER_NAME";
    }else{
        $query = "SELECT USER_ID,USER_NAME from USER,USER_PRIV where USER.USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and USER.USER_PRIV=USER_PRIV.USER_PRIV and (USER.NOT_LOGIN='0' or USER.NOT_MOBILE_LOGIN='0') ".$WHERE_STR." order by PRIV_NO,USER_NO,USER_NAME";
    }
}else{
    $query = "SELECT USER_ID,USER_NAME from USER,USER_PRIV where USER.USER_PRIV=USER_PRIV.USER_PRIV and (USER.NOT_LOGIN='0' or USER.NOT_MOBILE_LOGIN='0') ".$WHERE_STR." order by PRIV_NO,USER_NO,USER_NAME";
}
$cursor1= exequery(TD::conn(),$query,$QUERY_MASTER);
while($ROW=mysql_fetch_array($cursor1))
{
   $USERS[$ROW["USER_ID"]]["NAME"]=$ROW["USER_NAME"];
   $USER_ID_STR.=$ROW["USER_ID"].",";
}
//转换成时间戳
$CAL_TIME_CHANGE_B=date("Y-m-d",$WEEK_BEGIN)." 00:00:00";
$CAL_TIME_CHANGE_B=strtotime($CAL_TIME_CHANGE_B);
$CAL_TIME_CHANGE_E=date("Y-m-d",$WEEK_END)." 23:59:59";
$CAL_TIME_CHANGE_E=strtotime($CAL_TIME_CHANGE_E);
//..........
//参与或者所属的日程(find_in_set('$USER_ID',TAKER) or find_in_set('$USER_ID',OWNER))
$USER_ID_STR_ARRAY=explode(",",td_trim($USER_ID_STR));
$user_count = 0;
for($I=0;$I<count($USER_ID_STR_ARRAY);$I++)
{
    if($USER_ID_STR_ARRAY[$I]=="")
    {
        continue;
    }
    $user_count++;
//$query = "SELECT * from CALENDAR where find_in_set(USER_ID,'$USER_ID_STR')".$CONDITION_STR." and CAL_TYPE!='2' and (CAL_TIME>='$CAL_TIME_CHANGE_B' and CAL_TIME<='$CAL_TIME_CHANGE_E' || END_TIME>='$CAL_TIME_CHANGE_B' and END_TIME<='$CAL_TIME_CHANGE_E' || CAL_TIME<='$CAL_TIME_CHANGE_B' and END_TIME>='$CAL_TIME_CHANGE_E') order by CAL_TIME";
   $query = "SELECT * from CALENDAR where (USER_ID='$USER_ID_STR_ARRAY[$I]' or find_in_set('$USER_ID_STR_ARRAY[$I]',TAKER) or find_in_set('$USER_ID_STR_ARRAY[$I]',OWNER))".$CONDITION_STR." and CAL_TYPE!='2' and (CAL_TIME>='$CAL_TIME_CHANGE_B' and CAL_TIME<='$CAL_TIME_CHANGE_E' || END_TIME>='$CAL_TIME_CHANGE_B' and END_TIME<='$CAL_TIME_CHANGE_E' || CAL_TIME<='$CAL_TIME_CHANGE_B' and END_TIME>='$CAL_TIME_CHANGE_E')".$calendar_status."  order by CAL_TIME";
   $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
   while($ROW=mysql_fetch_array($cursor))
   {
	   $CAL_ID=$ROW["CAL_ID"];//var_dump($CAL_ID);
	   $CAL_TIME=$ROW["CAL_TIME"];
	   $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
	   $END_TIME=$ROW["END_TIME"];
	   $END_TIME=date("Y-m-d H:i:s",$END_TIME);
	   $CAL_TYPE=$ROW["CAL_TYPE"];
	   $CAL_LEVEL=$ROW["CAL_LEVEL"];
  	   // $USER_ID=$ROW["USER_ID"];
	   $USER_ID=$USER_ID_STR_ARRAY[$I];//将有权限查看的人USER_ID作为ID
	   $CONTENT=$ROW["CONTENT"];
	   $MANAGER_ID=$ROW["MANAGER_ID"];
	   $OVER_STATUS=$ROW["OVER_STATUS"];
	   $MANAGER_NAME="";
	   if($MANAGER_ID!="")
	   {
	      if(!array_key_exists($MANAGER_ID, $MANAGER))
	      {
	            $MANAGER[$MANAGER_ID]=td_trim(getUserNameById($MANAGER_ID));
	      }
	      $MANAGER_NAME=_("安排人:").$MANAGER[$MANAGER_ID]."<br>";
	   }
     if(!array_key_exists($CAL_TYPE, $CODE_NAME))
       $CODE_NAME[$CAL_TYPE]=get_code_name($CAL_TYPE,"CAL_TYPE");
     $CAL_TITLE=_("类型：").$CODE_NAME[$CAL_TYPE]."\n";
	   if($OVER_STATUS=="0")
	   {
	      if(compare_time($CUR_TIME,$END_TIME)>0)
	      {
	         $STATUS_COLOR="";
	         $CAL_TITLE.=_("状态：已超时")."\n";
	         $STATUS_FLAG_BLUE="<img src='".MYOA_STATIC_SERVER."/static/images/calendar/status_delay_blue.png' />";
	         $STATUS_FLAG = "status1";
	      }
	      else if(compare_time($CUR_TIME,$CAL_TIME)<0)
	      {
	         $STATUS_COLOR="";
	         $CAL_TITLE.=_("状态：未开始")."\n";
	         $STATUS_FLAG_BLUE="<img src='".MYOA_STATIC_SERVER."/static/images/calendar/status_undo_blue.png'/>";
	         $STATUS_FLAG = "status2";
	      }
	      else
	      {
	         $STATUS_COLOR="";
	         $CAL_TITLE.=_("状态：进行中")."\n";
	         $STATUS_FLAG_BLUE="<img src='".MYOA_STATIC_SERVER."/static/images/calendar/status_ing_blue.png'/>";
	         $STATUS_FLAG = "status3";
	      }
	   }
	   else
	   {
	      $STATUS_COLOR="";
	      $CAL_TITLE.=_("状态：已完成")."\n";
	      $STATUS_FLAG_BLUE="<img src='".MYOA_STATIC_SERVER."/static/images/calendar/status_over_blue.png'/>";
	      $STATUS_FLAG = "status4";
	   }
	   $CONTENT=csubstr(strip_tags($CONTENT),0,80);
	   if(substr($CAL_TIME,0,10) != substr($END_TIME,0,10))
	   {
	      $ALL_DAY="<div class='fc-event fc-event-vert fc-event-start fc-event-end  fc-event-color".$CAL_LEVEL."' style='margin-bottom:5px;'><div id=\"div_".$CAL_ID."\" title='".$CAL_TITLE."' class=\"fc-event-inner\">";
	      if(substr($CAL_TIME,0,10) < date("Y-m-d",$WEEK_BEGIN))
	         $ALL_DAY.="<a href=\"javascript:set_week(-1);\" title=\""._("上一周")."\">".menu_arrow("LEFT")."</a> ";
	      $ALL_DAY.="<span class=\"fc-event-time".$CAL_LEVEL."\" >".substr($CAL_TIME,0,16).($ROW["END_TIME"] == 0 ? "" : " - ".substr($END_TIME, 0, 16) )."</span> ".$CAL_TYPE_DESC."<a id=\"cal_".$CAL_ID."\" href='javascript:my_note($CAL_ID,\"".$IS_MAIN."\");' onclick='CancelBuble(event);' onmouseover=\"showMenu(this.id);\" style='color:#686868;".$STATUS_COLOR.";' class='fc-event-title'>".$STATUS_FLAG_BLUE.$CONTENT."</a> ".$MANAGER_NAME;
	      if(substr($END_TIME,0,10) > date("Y-m-d",$WEEK_END))
	         $ALL_DAY.="<a href=\"javascript:set_week(1);\" title=\""._("下一周")."\">".menu_arrow("RIGHT")."</a>";
	      $ALL_DAY.="</div></div>\n";
	      $USERS[$USER_ID]["ALL_DAY"].=$ALL_DAY;

	      $COL_BEGIN = floor((strtotime(substr($CAL_TIME,0,10))-$WEEK_BEGIN)/86400)+1;
	      $COL_BEGIN = $COL_BEGIN<=0 ? 1 : $COL_BEGIN;

	      $COL_END = floor((strtotime(substr($END_TIME,0,10))-$WEEK_BEGIN)/86400)+1;
	      $COL_END = $COL_END>7 ? 7 : $COL_END;
          if($ROW["END_TIME"] == 0 )
          {
            $COL_END = 7;
          }
	      $CAL_DIV_ARRAY.="Array($CAL_ID, $COL_BEGIN, $COL_END), ";
	   }
	   else
	   {
	   	 if(strlen($CONTENT)>34)
	   	  {
	   	     $CONTENT=csubstr(strip_tags($CONTENT),0,34)."...";
	   	  }
	   	  else
	   	  {
	   	  	$CONTENT=csubstr(strip_tags($CONTENT),0,34);
	   	  }
	      $USERS[$USER_ID]["WEEK"][date("w",strtotime($CAL_TIME))==0 ? 6 : date("w",strtotime($CAL_TIME))-1].="<div class='fc-event fc-event-vert fc-event-draggable fc-event-start fc-event-end ui-draggable ui-resizable fc-event-color".$CAL_LEVEL."' style='margin-bottom:5px;'><div id=\"div_".$CAL_ID."\" class='fc-event-inner'><span class=\"fc-event-time".$CAL_LEVEL."\" >".substr($CAL_TIME,11,5).($ROW["END_TIME"] == 0 ? "" : "-".substr($END_TIME,11,5) )."</span><a id=\"cal_".$CAL_ID."\" href='javascript:my_note($CAL_ID,\"".$IS_MAIN."\");' onclick='CancelBuble(event);' onmouseover=\"showMenu(this.id);\" style='color:#686868".$STATUS_COLOR.";' title='".$CAL_TITLE."' class='fc-event-title'>".$STATUS_FLAG_BLUE.$CONTENT."</a></div></div>";
	   }

   }//while 日程循环
}
?>
  <table id="cal_table" class="table table-bordered <?= $user_count == 0 ? 'hide' : '' ?>">
    <tr id="tbl_header" class="tbl_header">
      <td width="9%" class="fc-agenda-axis fc-first table_head" id="name"><?=_("姓名")?></td>
<?
//---------------- 表头 ---------------
for($I=0, $WEEK_DATE = $WEEK_BEGIN; $WEEK_DATE <= $WEEK_END; $WEEK_DATE = strtotime("+1 day",$WEEK_DATE), $I++)
{
?>
      <td class="table_head" style="line-height:100%;text-align:center;" id="th_<?=$WEEK_DATE?>" width="13%" <?if($WEEK==0)echo "bgcolor=#FFCCFF";else if($WEEK==6)echo "bgcolor=#CCFFCC";?> >
          <a style="color:#000;" href="day.php?YEAR=<?=date("Y",$WEEK_DATE)?>&MONTH=<?=date("m",$WEEK_DATE)?>&DAY=<?=date("d",$WEEK_DATE)?>&DEPT_ID=<?=$DEPT_ID?>" title=<?=_("转到日视图")?> onclick='CancelBuble(event);'><?=date("m/d",$WEEK_DATE)?>(<?=get_week(date("Y-m-d",$WEEK_DATE))?>)<br><font style=font-size:12px; title=<?=_("农历日期")?>><? $date = $lunar->convertSolarToLunar($WEEK_DATE); echo $date[1].$date[2]; ?></font></a>
      </td>
<?
}
?>
    </tr>
<?
$I=0;
$aff_count = 0;
foreach($USERS as $USER_ID => $USER)
{
   if($USER["ALL_DAY"]!="")
   {
?>
    <tr>
      <td id="tr_<?=$I?>" width="80" rowspan="2" style="text-align:center;"><?=$USER["NAME"]?></td>
      <td colspan="7" style="overflow:hidden"><?=$USER["ALL_DAY"]?></td>
    </tr>
<?
   }
?>
    <tr height="30">
<?
   if($USER["ALL_DAY"]=="")
   {
?>
      <td id="tr_<?=$I?>" width="80" style=" text-align:center"><?=$USER["NAME"]?></td>
<?
   }
   for($WEEK_DATE=$WEEK_BEGIN;$WEEK_DATE<=$WEEK_END;$WEEK_DATE=strtotime("+1 day",$WEEK_DATE))
   {
      $WEEK_YEAR=date("Y",$WEEK_DATE);
      $WEEK_MON=date("m",$WEEK_DATE);
      $WEEK_DAY=date("d",$WEEK_DATE);
      $WEEK_DAYWEEK=date("w",$WEEK_DATE);

      $aff_count++;//新建div计数

      if($WEEK_DAY == $DAY && $YEAR == $WEEK_YEAR && $MONTH == $WEEK_MON)
         $DAY_COLOR = "tablepink";
      else
         $DAY_COLOR = "";
?>
      <td id="td_<?=$I?>_<?=$WEEK_DATE?>" class="<?=$DAY_COLOR?>"  <? if($WEEK_DAYWEEK==0 or $WEEK_DAYWEEK==6) echo "style='background-color:rgb(228, 255, 223)'";?> style="vertical-align:top;">
      <div id="new_affair_<?=$aff_count?>" onClick="new_arrange('<?=$USER_ID?>','<?=$WEEK_DATE?>','+1 days');" class="new_affair" title="<?=_("单击建立日事务")?>"><a><?=_("新建事务")?></a></div>
        <?=$USER["WEEK"][date("w",$WEEK_DATE)==0 ? 6 : date("w",$WEEK_DATE)-1]?>
        <?
         $DATE_STR=date("Y-m-d H:i:s",$WEEK_DATE);
         $DATE_STR=substr($DATE_STR,0,10);
         //周期性事务
         $Tquery = "SELECT * from AFFAIR where (USER_ID='$USER_ID' or find_in_set('$USER_ID',TAKER)) and CAL_TYPE<>'2'".$state."order by AFF_ID desc";
         $Tcursor= exequery(TD::conn(),$Tquery,$QUERY_MASTER);
         while($TROW=mysql_fetch_array($Tcursor))
         {
            $AFF_ID=$TROW["AFF_ID"];
            $TYPE=$TROW["TYPE"];
            $BEGIN_TIME_TIME=$TROW["BEGIN_TIME_TIME"];
            $END_TIME_TIME=$TROW["END_TIME_TIME"];
            $REMIND_DATE=$TROW["REMIND_DATE"];
            $LAST_REMIND=$ROW["LAST_REMIND"];
            $BEGIN_TIME=$TROW["BEGIN_TIME"];
            $BEGIN_TIME=date("Y-m-d H:i:s",$BEGIN_TIME);
            $END_TIME=$TROW["END_TIME"];
             if($END_TIME!=0)
             {
                $END_TIME=date("Y-m-d H:i:s",$END_TIME);
             }
            $MANAGER_ID=$TROW["MANAGER_ID"];
            $REMIND_TIME=$TROW["REMIND_TIME"];
            $BEGIN_DATE=substr($BEGIN_TIME,0,10);
            if($END_TIME!=0)
            {
                $END_DATE=substr($END_TIME,0,10);

            }
            else{
                $END_DATE="0000-00-00";

            }
            $CONTENT=$TROW["CONTENT"];
            if(compare_date($DATE_STR,$BEGIN_DATE)<0)
            {
               continue;
            }

            if($END_DATE!="0000-00-00")
            {
               if(compare_date($DATE_STR,$END_DATE)>0)
               {
                    continue;
               }
            }

           if(compare_date($DATE_STR,$BEGIN_DATE) == 0){
              if(compare_time($BEGIN_TIME_TIME,$REMIND_TIME) > 0)
              {
                continue;
              }

           }
           if(compare_date($DATE_STR,$END_DATE) == 0){
              if(compare_time($END_TIME_TIME,$REMIND_TIME) < 0)
              {
                continue;
              }

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
            $AFF_TITLE=_("提醒时间：每日 ").substr($REMIND_TIME,0,-3)."\n"._("上次提醒：").$LAST_REMIND."\n"._("起始时间：").$BEGIN_DATE." ".$BEGIN_TIME_TIME;
            if($FLAG==1)
            {
               echo "<div class='fc-event fc-event-vert fc-event-draggable fc-event-start fc-event-end' style='margin-bottom:5px;'><div class='fc-event-inner'>".$REMIND_TIME."<a id='aff_".$AFF_ID."' href='javascript:my_aff_note(".$AFF_ID.",1,\"".$IS_MAIN."\")' onclick='CancelBuble(event);' onmouseover='showMenu(this.id);' style='color:#686868' title='".$AFF_TITLE."' class='fc-event-title'>".$CONTENT."</a></div></div>";
            }
         }
         //任务
         $Tquery = "SELECT * from TASK where USER_ID='$USER_ID' ".$task_status." order by TASK_ID desc";
         $Tcursor= exequery(TD::conn(),$Tquery,$QUERY_MASTER);
         while($TROW=mysql_fetch_array($Tcursor))
         {
            $TASK_ID=$TROW["TASK_ID"];
            $BEGIN_DATE =$TROW["BEGIN_DATE"];
            $END_DATE=$TROW["END_DATE"];
            $MANAGER_ID=$TROW["MANAGER_ID"];
            $SUBJECT=$TROW["SUBJECT"];
            $COLOR=$TROW["COLOR"];
            if(compare_date($DATE_STR,$BEGIN_DATE)<0)
               continue;

            if($END_DATE!="0000-00-00")
            {
               if(compare_date($DATE_STR,$END_DATE)>0)
                    continue;
            }
       if(strlen($SUBJECT)>40)
   	  {
   	     $SUBJECT=csubstr(strip_tags($SUBJECT),0,40)."...";
   	  }
   	  else
   	  {
   	  	$SUBJECT=csubstr(strip_tags($SUBJECT),0,40);
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
           echo "<div class='fc-event fc-event-vert fc-event-draggable fc-event-start fc-event-end ui-draggable ui-resizable fc-event-color".$COLOR."' style='margin-bottom:5px;'><div class='fc-event-inner'><span style='white-space:nowrap;'>"._("任务:")."</span><a onclick='CancelBuble(event);' class='fc-event-title' id='task_".$TASK_ID."' href='javascript:my_task_note(".$TASK_ID.",1,1,\"".$IS_MAIN."\")' onmouseover='showMenu(this.id);' style='color:#686868' title='"._("点击查看任务详情")."'>".$SUBJECT."</a>".$MANAGER_ID_NAME."</div></div>";
         }
        ?>

      </td>
<?
   }  //for 日循环
?>
    </tr>
<?
   $USER_ARRAY_STR.="'".$USER_ID."',";
   $I++;
}//foreach 用户循环
?>
  </table>
<?
    if($user_count == 0)
    {
        echo '<div style="width: 440px;margin: auto;">';
        Message("",_("未找到符合条件的结果"));
        echo '</div>';
    }
?>


<?=$OP_MENU?>
<?=$OP_MENU_AFF?>
<?=$OP_MENU_TASK?>
<script>var cal_div_array = new Array(<?=trim(trim($CAL_DIV_ARRAY),",")?>);</script>
<script>var user_id_array = new Array(<?=trim(trim($USER_ARRAY_STR),",")?>);</script>
<br>

<div align=right>
<?=help('005','skill/erp/calendar');?>
</div>
<div id="overlay"></div>

<div id="form_div" class="ModalDialog1">
  <div class="modal-header"><a class="operation" href="javascript:HideDialog('form_div');"><button type="button" class="close" data-dismiss="modal" aria-hidden="true" onClick="HideDialog('form_div');">&times;</button></a>
  <h3><span id="title" class="title"><?=_("新建日程")?></span></h3>
  </div>
  <div id="form_body" class="modal-body" style="height:280px">

  </div>
</div>
</body>
</html>

