<?
if($_COOKIE["cal_info_view"]=="index" || $_COOKIE["cal_info_view"]=="month")
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

$DATE_STR=$YEAR."-".$MONTH."-".$DAY;
$DATE=strtotime($DATE_STR);

$CUR_TIME=date("Y-m-d H:i:s",time());
$CUR_TIME_U=time();
$CONDITION_STR="";
if($_GET['OVER_STATUS']=="1")
{
   $CONDITION_STR.=" and OVER_STATUS='0' and CAL_TIME>'$CUR_TIME_U'";
   $STATUS_DESC="<font color=''>"._("未开始")."</font>";
}
else if($_GET['OVER_STATUS']=="2")
{
   $CONDITION_STR.=" and OVER_STATUS='0' and CAL_TIME<='$CUR_TIME_U' and END_TIME>='$CUR_TIME_U'";
   $STATUS_DESC="<font color=''>"._("进行中")."</font>";
}
else if($_GET['OVER_STATUS']=="3")
{
   $CONDITION_STR.=" and OVER_STATUS='0' and END_TIME<'$CUR_TIME_U'";
   $STATUS_DESC="<font color=''>"._("已超时")."</font>";
}
else if($_GET['OVER_STATUS']=="4")
{
   $CONDITION_STR.=" and OVER_STATUS='1'";
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
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
if(window.external && typeof window.external.OA_SMS != 'undefined')
{        
    var h = Math.min(800, screen.availHeight - 180),
        w = Math.min(1280, screen.availWidth - 180);
    window.external.OA_SMS(w, h, "SET_SIZE");
}
function init()
{
   var elementI=document.getElementsByTagName("TR");
   for(i=0;i<elementI.length;i++)
   {
      if(elementI[i].id.substr(0,3)!="tr_")
         continue;
      elementI[i].onclick =function() {new_arrange(user_id_array[this.id.substr(3)],'<?=$DATE?>','+1 days');};
   }
   var elementI=document.getElementsByTagName("TD");
   for(i=0;i<elementI.length;i++)
   {
      if(elementI[i].id.substr(0,3)!="td_")
         continue;
       elementI[i].onmouseover=function(){
          	    this.style.cursor='pointer';
          	   this.title='<?=_("单击建立日事务")?>';
          	};
       elementI[i].onmouseout=function(){
          		 this.style.backgroundImage = '';
          	}; 
      
      elementI[i].onclick =function() {new_arrange(user_id_array[this.id.substr(3)],'<?=$DATE?>','+1 days');};
   }
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
jQuery(document).ready(function(){
	jQuery("div[id^=new_affair_]").each(function(i){
	    jQuery(this).parent().mouseover(function(){
	        jQuery("div[id=new_affair_"+(i+1)+"]").css("visibility","visible");
	    })
        jQuery(this).parent().mouseout(function(){
        	jQuery("div[id^=new_affair_]").css("visibility","hidden"); 
        })
        jQuery("div[id^=new_affair_] a").hover(function(){
        	jQuery("div[id=new_affair_"+(i+1)+"] a").css("color","#000");
        	this.style.cursor='pointer';
        },function(){
        	jQuery("div[id=new_affair_"+(i+1)+"] a").css("color","#888");
        })
	})
});
</script>

<!--  onLoad="init();"-->
<body class="bodycolor">
<div class="PageHeader calendar_icon page_top">
 <div class="header-left">
   <form name="form1" action="<?=$_SERVER["SCRIPT_NAME"]?>" style="margin-bottom:5px;" class="form-inline">
   <input type="hidden" value="" name="BTN_OP">
   <input type="hidden" value="<?=$_GET['OVER_STATUS']?>" name="OVER_STATUS">
   <a href="<?=$_SERVER["SCRIPT_NAME"]?>?YEAR=<?=$CUR_YEAR?>&MONTH=<?=$CUR_MON?>&DAY=<?=$CUR_DAY?>" style="text-decoration:none;"><button class="btn" type="button"><?=_("今天")?></button></a>
<!-------------- 年 ------------>
   <a href="javascript:set_year(-1);" class="ArrowButtonL" title="<?=_("上一年")?>"></a><select name="YEAR" class="smallSelect" onChange="My_Submit();">
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
   <a href="javascript:set_mon(-1);" class="ArrowButtonL" title="<?=_("上一月")?>"></a><select name="MONTH" class="smallSelect" onChange="My_Submit();">
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
   <a href="javascript:set_day(-1);" class="ArrowButtonL" title="<?=_("上一天")?>"></a><select name="DAY" class="smallSelect" onChange="My_Submit();">
<?
   for($I=1;$I<=date("t",strtotime($YEAR."-".$MONTH."-".$DAY));$I++)
   {
      if($I<10)
         $I="0".$I;
?>
      <option value="<?=$I?>" <? if($I==$DAY) echo "selected";?>><?=$I?><?=_("日")?></option>
<?
   }
?>
   </select><a href="javascript:set_day(1);" class="ArrowButtonR" title="<?=_("下一天")?>"></a>
   <div class="btn-group" style="width:100px;display:inline">
   <button type="button" id="status" class="btn"><span><?=$STATUS_DESC?></span></button>
   <button type="button" class="btn dropdown-toggle" onClick="show_menu();"><b class="caret"></b></button>
   <ul id="status_menu" class="dropdown-menu" style="top:12px;display:none;">
      <li><a href="javascript:set_status_index('');"><?=_("全部")?></a></li>
      <li><a href="javascript:set_status_index(1);" ><?=_("未开始")?></a></li>
      <li><a href="javascript:set_status_index(2);" ><?=_("进行中")?></a></li>
      <li><a href="javascript:set_status_index(3);" ><?=_("已超时")?></a></li>
      <li><a href="javascript:set_status_index(4);" ><?=_("已完成")?></a></li>
   </ul>
   </div>
 </div>
 <div class="header-right form-inline">
    <button type="button" onClick="javascript:window.location.href='query.php?DEPT_ID=<?=$DEPT_ID?>'"  class="btn btn-primary"><?=_("查询")?></button>
<?
if($DEPT_PRIV!="3")
{
?>
   <select name="DEPT_ID" style="width:130px;" onChange="My_Submit();">
<?=my_dept_tree(0,$DEPT_ID,array("DEPT_PRIV" => $DEPT_PRIV,"DEPT_ID_STR" => $DEPT_ID_STR));?>
   </select>
<?
}
?>
   <div class="btn-group" style="display:inline">
       <button onClick="set_view('day','cal_info_view');" class="btn btn-info"><?=_("日")?></button>
       <button onClick="set_view('index','cal_info_view');" class="btn"><?=_("周")?></button>
       <button onClick="set_view('month','cal_info_view');" class="btn"><?=_("月")?></button>
   </div>
   </form>
 </div>
</div>
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
$CODE_NAME=$MANAGER=$USERS=array();

$USER_ID_STR ='';
$query = "SELECT USER_ID,USER_NAME from USER,USER_PRIV where USER.USER_PRIV=USER_PRIV.USER_PRIV and (USER.NOT_LOGIN='0' or USER.NOT_MOBILE_LOGIN='0') ".$WHERE_STR." order by PRIV_NO,USER_NO,USER_NAME";
$cursor1= exequery(TD::conn(),$query,$QUERY_MASTER);
while($ROW=mysql_fetch_array($cursor1))
{
   $USERS[$ROW["USER_ID"]]["NAME"]=$ROW["USER_NAME"];
   $USER_ID_STR.=$ROW["USER_ID"].",";
}
//$USER_ID_STR.=$_SESSION['LOGIN_USER_ID'];
//参与或者所属的日程(find_in_set('$USER_ID',TAKER) or find_in_set('$USER_ID',OWNER))
$USER_ID_STR_ARRAY=explode(",",td_trim($USER_ID_STR));
for($I=0;$I<count($USER_ID_STR_ARRAY);$I++)
{
    if($USER_ID_STR_ARRAY[$I]=="")
    {
        continue;
    }
	$query = "SELECT * from CALENDAR where (USER_ID='$USER_ID_STR_ARRAY[$I]' or find_in_set('$USER_ID_STR_ARRAY[$I]',TAKER) or find_in_set('$USER_ID_STR_ARRAY[$I]',OWNER))".$CONDITION_STR." and CAL_TYPE!='2' and to_days(from_unixtime(CAL_TIME))<=to_days('$YEAR-$MONTH-$DAY') and to_days(from_unixtime(END_TIME))>=to_days('$YEAR-$MONTH-$DAY') order by CAL_TIME";
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
	  //$USER_ID=$ROW["USER_ID"];
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
	     $MANAGER_NAME="("._("安排人:").$MANAGER[$MANAGER_ID].")";
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
	  $CAL_TITLE.=_("优先级：").cal_level_desc($CAL_LEVEL)."\n";
	  $CONTENT=csubstr(strip_tags($CONTENT),0,100);
	  if(substr($CAL_TIME,0,10) != substr($END_TIME,0,10))
	  {
	     $ALL_DAY="<div class='fc-event fc-event-vert fc-event-draggable fc-event-start fc-event-end ui-draggable ui-resizable fc-event-color".$CAL_LEVEL."' style='margin-bottom:5px;'><div id=\"div_".$CAL_ID."\" title='".$CAL_TITLE."' class=\"fc-event-inner\">";
	     if(substr($CAL_TIME,0,10) < $DATE_STR)
	        $ALL_DAY.="<a href=\"javascript:set_day(-1);\" title=\""._("上一天")."\">".menu_arrow("LEFT")."</a> ";
	     $ALL_DAY.="<span class=\"fc-event-time".$CAL_LEVEL."\" >".substr($CAL_TIME,0,16)." - ".substr($END_TIME,0,16)."</span> &nbsp;".$CAL_TYPE_DESC."<a id=\"cal_".$CAL_ID."\" href='javascript:my_note($CAL_ID,\"".$IS_MAIN."\");' onclick='CancelBuble(event);' onmouseover=\"showMenu(this.id);\" style='color:#686868".$STATUS_COLOR.";' class='fc-event-title' title='".$CAL_TITLE."'>".$STATUS_FLAG_BLUE.$CONTENT."</a> ".$MANAGER_NAME;
	     if(substr($END_TIME,0,10) > $DATE_STR)
	        $ALL_DAY.="<a href=\"javascript:set_day(1);\" title=\""._("下一天")."\">".menu_arrow("RIGHT")."</a>";
	     $ALL_DAY.="</div></div>\n";
	     $USERS[$USER_ID]["CAL"].=$ALL_DAY;
	  }
	  else
	  {
	     $USERS[$USER_ID]["CAL"].="<div class='fc-event fc-event-vert fc-event-draggable fc-event-start fc-event-end ui-draggable ui-resizable fc-event-color".$CAL_LEVEL."' style='margin-bottom:5px;'><div title='".$CAL_TITLE."' class='fc-event-inner'><span class=\"fc-event-time".$CAL_LEVEL."\" >".substr($CAL_TIME,11,5)." - ".substr($END_TIME,11,5)." ".$CAL_TYPE_DESC."</span><a id=\"cal_".$CAL_ID."\" href='javascript:my_note($CAL_ID,\"".$IS_MAIN."\");' onclick='CancelBuble(event);' onmouseover=\"showMenu(this.id);\" style='color:#686868".$STATUS_COLOR.";' title='".$CAL_TITLE."' class='fc-event-title'>".$STATUS_FLAG_BLUE.$CONTENT."</a> ".$MANAGER_NAME."</div></div>\n";
	  }
	 
	}//while 日循环
}
?>
  <table id="cal_table" class="table table-bordered">
    <tr class="first_tr" align="center" title="<?=_("单击为该部门所有用户建立日事务")?>" onClick="new_arrange(user_id_array,'<?=$DATE?>','+1 days');">
      <td width="80" class="fc-agenda-axis fc-first" id="name"><?=_("姓名")?></td>
      <td style="color:#000;text-align: center;line-height:100%; font-weight:bold"><?=date(_("Y年m月d日"),$DATE)?>(<?=get_week(date("Y-m-d",$DATE))?>) <? $date = $lunar->convertSolarToLunar($DATE); echo $date[1].$date[2]."("._("农历").")";?></td>
    </tr>
<?
$I=0;
$aff_count = 0;
foreach($USERS as $USER_ID => $USER)
{
    $aff_count++;
$MSG = sprintf(_("单击为 %s 建立日事务"), $USER["NAME"]);
?>
    <tr id="tr_<?=$I?>">
      <td align="center" style="text-align:center;cursor:pointer;" title="<?=$MSG?>" onClick="new_arrange('<?=$USER_ID?>','<?=$DATE?>','+1 days');">
      <?=($USER["NAME"])?$USER["NAME"]:$_SESSION['LOGIN_USER_NAME']?>
      </td>
      <td id="td_<?=$I?>">
      <div id="new_affair_<?=$aff_count?>" onClick="new_arrange('<?=$USER_ID?>','<?=$DATE?>','+1 days');" class="new_affair" title="<?=_("单击建立日事务")?>"><a><?=_("新建事务")?></a></div>
        <?=$USER["CAL"]?>
        <?
         $Tquery = "SELECT * from AFFAIR where (USER_ID='$USER_ID' or find_in_set('$USER_ID',TAKER)) and CAL_TYPE<>'2' order by AFF_ID desc";    
         $Tcursor= exequery(TD::conn(),$Tquery,$QUERY_MASTER);
         while($TROW=mysql_fetch_array($Tcursor))
         {
            $AFF_ID=$TROW["AFF_ID"];      
            $TYPE=$TROW["TYPE"];         
            $REMIND_DATE=$TROW["REMIND_DATE"];
            $BEGIN_TIME=$TROW["BEGIN_TIME"];
            $BEGIN_TIME=date("Y-m-d H:i:s",$BEGIN_TIME);
            $LAST_REMIND=$ROW["LAST_REMIND"];
            $END_TIME=$TROW["END_TIME"];
            if($END_TIME!=0)
            $END_TIME=date("Y-m-d H:i:s",$END_TIME);
            $MANAGER_ID=$TROW["MANAGER_ID"]; 
            $REMIND_TIME=$TROW["REMIND_TIME"]; 
             
            $BEGIN_DATE=substr($BEGIN_TIME,0,10);
            if($END_TIME!=0)
               $END_DATE=substr($END_TIME,0,10);
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
               echo "<div class='fc-event fc-event-vert fc-event-draggable fc-event-start fc-event-end ui-draggable ui-resizable' style='margin-bottom:5px;'><div class='fc-event-inner'>".$REMIND_TIME." <a id='aff_".$AFF_ID."' href='javascript:my_aff_note(".$AFF_ID.",1,\"".$IS_MAIN."\")' onclick='CancelBuble(event);' onmouseover='showMenu(this.id);' style='color:#686868' class='fc-event-title' title='".$AFF_TITLE."'>".$CONTENT."</a>".$MANAGER_ID_NAME."</div></div>";
             
            }
            
         }  
         
         
         $Tquery = "SELECT * from TASK where USER_ID='$USER_ID' order by TASK_ID desc";
         
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
            
           echo "<div class='fc-event fc-event-vert fc-event-draggable fc-event-start fc-event-end ui-draggable ui-resizable fc-event-color".$COLOR."' style='margin-bottom:5px;'><div class='fc-event-inner'><span style='white-space:nowrap;'>"._("任务：")."</span><a id='task_".$TASK_ID."' href='javascript:my_task_note(".$TASK_ID.",1,1,\"".$IS_MAIN."\")' onclick='CancelBuble(event);' onmouseover='showMenu(this.id);' style='color:#686868' title='"._("点击查看任务详情")."' class='fc-event-title'>".$SUBJECT."</a>".$MANAGER_ID_NAME."</div></div>";
         
         } 
        ?>
      </td>
    </tr>
<?
   $USER_ARRAY_STR.="'".$USER_ID."',";
   $I++;
}//foreach 用户循环
?>
</table>
<?=$OP_MENU?>
<?=$OP_MENU_AFF?>
<?=$OP_MENU_TASK?>
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
