<?
include_once("inc/utility.php");

$MODULE_FUNC_ID="8";
$MODULE_DESC=_("日程安排");
$MODULE_BODY=$MODULE_OP=$MODULE_TYPE="";
$MODULE_HEAD_CLASS = 'calendar';

if($UPDATE_STATUS==1)
{
  $query = "UPDATE CALENDAR SET OVER_STATUS='$OVER_STATUS' WHERE USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and CAL_ID='$CAL_ID'";
  $cursor = exequery(TD::conn(),$query);
}

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_OP='<a href="#" title="'._("全部").'" class="all_more" onclick="view_more(\'calendar\',\''._("日程安排").'\',\'/general/calendar/\');">'._("全部").'&nbsp;</a>';
	
$MODULE_TYPE .= '<a href="javascript:get_arr(\'0\');">'._("今日日程").'</a> ';
$MODULE_TYPE .= '<a href="javascript:get_arr(\'1\');">'._("近日日程").'</a> ';
$MODULE_TYPE .= '<a href="javascript:get_arr(\'2\');">'._("日程任务").'</a> ';
$MODULE_BODY.= "<ul>";

$COUNT=0;
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
$CUR_TIME_U=time();
$query = "SELECT * from CALENDAR where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and to_days(from_unixtime(CAL_TIME))<=to_days('$CUR_DATE') and to_days(from_unixtime(END_TIME))>=to_days('$CUR_DATE') order by CAL_TIME limit 0,$MAX_COUNT";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $COUNT++;
   $CAL_ID=$ROW["CAL_ID"];
   $CAL_TIME=$ROW["CAL_TIME"];
   $END_TIME=$ROW["END_TIME"];
   $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
   $END_TIME=date("Y-m-d H:i:s",$END_TIME);
   $MANAGER_ID=$ROW["MANAGER_ID"];
 
   if(substr($CAL_TIME,0,10) == $CUR_DATE && substr($END_TIME,0,10) == $CUR_DATE)
   {
      $CAL_TIME=substr($CAL_TIME,11,5);
      $END_TIME=substr($END_TIME,11,5);
   }
   else
   {
      $CAL_TIME=substr($CAL_TIME,0,16);
      $END_TIME=substr($END_TIME,0,16);
   }

   $CONTENT=$ROW["CONTENT"];
   
   $SUBJECT_TITLE="";
   if(strlen($CONTENT) > 50)
   {
      $SUBJECT_TITLE=td_htmlspecialchars($CONTENT);
      $CONTENT=csubstr($CONTENT, 0, 50)."...";
   }
   $CONTENT=td_htmlspecialchars($CONTENT);
   if($MANAGER_ID!=""&&$MANAGER_ID!=$_SESSION["LOGIN_USER_ID"])
      $CONTENT="<b>".$CONTENT."</b>";
   
   $OVER_STATUS=$ROW["OVER_STATUS"];
   if($OVER_STATUS=="" || $OVER_STATUS=="1")
      $OVER_STATUS1="<font color='#00AA00'><b>"._("已完成")."</b></font>";
   elseif($OVER_STATUS=="0")
      $OVER_STATUS1="";

   $MODULE_BODY.='<li><div id="cal_'.$CAL_ID.'">'.$CAL_TIME.' - '.$END_TIME.' <a href="javascript:my_note_cal('.$CAL_ID.')" title="'.$SUBJECT_TITLE.'">'.$CONTENT.'</a> '.$OVER_STATUS1;
   $MODULE_BODY.="<span id=\"cal_".$CAL_ID."_op\" style=\"display:none;\">";
   if($OVER_STATUS=="0") 
      $MODULE_BODY.="<a href=\"?UPDATE_STATUS=1&CAL_ID=$CAL_ID&OVER_STATUS=1&YEAR=$YEAR&MONTH=$MONTH&DAY=$DAY\"> &nbsp;"._("完成")."</a>";
   elseif($OVER_STATUS=="" || $OVER_STATUS=="1")
      $MODULE_BODY.="<a href=\"?UPDATE_STATUS=1&CAL_ID=$CAL_ID&OVER_STATUS=0&YEAR=$YEAR&MONTH=$MONTH&DAY=$DAY\"> &nbsp;"._("未完成")."</a>";
   if($MANAGER_ID!="" && $MANAGER_ID!=$_SESSION["LOGIN_USER_ID"])
   {
      $query = "SELECT USER_NAME from USER where USER_ID='$MANAGER_ID'";
      $cursor1= exequery(TD::conn(),$query);
      if($ROW1=mysql_fetch_array($cursor1))
         $MANAGER_NAME=$ROW1["USER_NAME"];
      $MODULE_BODY.=' <a href="javascript:sms_back(\''.$MANAGER_ID.'\',\''.$MANAGER_NAME.'\');">'._("回复微讯").'</a>';
   }
   
   if($MANAGER_ID=="" || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"])
   {
      $MODULE_BODY.="<a onclick=\"window.open('/general/calendar/arrange/edit_table.php?FROM=1&CAL_ID=$CAL_ID&OVER_STATUS=1&YEAR=$YEAR&MONTH=$MONTH&DAY=$DAY')\";> "._("修改")."</a>";
   }
   $MODULE_BODY.="</span></div>";
   $MODULE_BODY.='</li>';
}

//============================ 显示日常事务 =======================================
$query = "SELECT * from AFFAIR where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and BEGIN_TIME<='$CUR_TIME_U' and (END_TIME='' or END_TIME='0' or END_TIME>='$CUR_TIME_U') order by REMIND_TIME";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $AFF_ID=$ROW["AFF_ID"];
   $USER_ID=$ROW["USER_ID"];
   $TYPE=$ROW["TYPE"];
   $REMIND_DATE=$ROW["REMIND_DATE"];
   $REMIND_TIME=$ROW["REMIND_TIME"];
   $CONTENT=$ROW["CONTENT"];
   $MANAGER_ID=$ROW["MANAGER_ID"];
   $OVER_STATUS=$ROW["OVER_STATUS"];

   $FLAG=0;
   if($TYPE=="2")
      $FLAG=1;
   elseif($TYPE=="3" && date("w",time())==$REMIND_DATE)
      $FLAG=1;
   elseif($TYPE=="4" && date("j",time())==$REMIND_DATE)
      $FLAG=1;
   elseif($TYPE=="5")
   {
      $REMIND_ARR=explode("-",$REMIND_DATE);
      $REMIND_DATE_MON=$REMIND_ARR[0];
      $REMIND_DATE_DAY=$REMIND_ARR[1];
      if(date("n",time())==$REMIND_DATE_MON && date("j",time())==$REMIND_DATE_DAY)
         $FLAG=1;
   }

   if($FLAG==1)
   {
     $COUNT++;
     if($COUNT>$MAX_COUNT)
        break;
     if($MANAGER_ID!=""&&$MANAGER_ID!=$_SESSION["LOGIN_USER_ID"])
        $CONTENT="<b>".$CONTENT."</b>";
     $MODULE_BODY.='<li>'.substr($REMIND_TIME,0,5).' <a href="javascript:my_affair('.$AFF_ID.')">'.$CONTENT.'</a></li>';
   }
}

if($COUNT==0)
   $MODULE_BODY.= "<li>"._("今日暂无日程安排")."</li>";

$MODULE_BODY.= "<ul>";
$MODULE_BODY.= '<script>
function my_affair(AFF_ID)
{
  myleft=(screen.availWidth-250)/2;
  mytop=(screen.availHeight-200)/2;
  window.open("/general/calendar/affair/note.php?AFF_ID="+AFF_ID,"note_win"+AFF_ID,"height=272,width=480,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,top="+mytop+",left="+myleft);
}

function sms_back(MANAGER_ID,MANAGER_NAME)
{
   var mytop = (screen.availHeight-265)/2;
   var myleft= (screen.availWidth-420)/2;  
   window.open("/general/status_bar/sms_back.php?TO_ID="+unescape(MANAGER_ID)+"&TO_NAME="+unescape(MANAGER_NAME)+"&CONTENT="+unescape(_("您好，已收到您的日程安排。")),"","height=265,width=420,status=0,toolbar=no,menubar=no,location=no,scrollbars=no,top="+mytop+",resizable=yes,left="+myleft);
}

function my_note_cal(CAL_ID)
{
  myleft=(screen.availWidth-250)/2;
  mytop=(screen.availHeight-200)/2;
  window.open("/general/calendar/arrange/note.php?CAL_ID="+CAL_ID,"note_win"+CAL_ID,"height=272,width=480,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,top="+mytop+",left="+myleft);
}

function my_task_note(TASK_ID)
{
  my_left=document.body.scrollLeft+400;
  my_top=document.body.scrollTop+300;
 
  window.open("/general/calendar/task/note.php?TASK_ID="+TASK_ID,"task_win"+TASK_ID,"height=272,width=480,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+ my_top +",left="+ my_left +",resizable=yes");
}

function cal_init()
{
   var elementI=document.getElementsByTagName("DIV");

   for(i=0;i<elementI.length;i++)
   {
      if(elementI[i].id.substr(0,4)!="cal_")
         continue;

      elementI[i].onmouseover=function() {var op_i=document.getElementById(this.id+"_op");if(op_i) op_i.style.display="";}
      elementI[i].onmouseout =function() {var op_i=document.getElementById(this.id+"_op");if(op_i) op_i.style.display="none";}
   }
}
cal_init();

function get_arr(req)
{
   var obj = $("module_'.$MODULE_ID.'_ul");
   if(!obj) return;
   
   if(typeof(req) != "object")
   {
      obj.innerHTML = \'<img src="'.MYOA_STATIC_SERVER.'/static/images/loading.gif" align="absMiddle"> '._("加载中，请稍候……").'\';
      _get("calendar.php", "MAX_COUNT='.$MAX_COUNT.'&SHOW_FLAG="+req+"&MODULE_SCROLL='.$MODULE_SCROLL.'&MODULE_ID='.$MODULE_ID.'", arguments.callee);
   }
   else
   {
      obj.innerHTML = req.status==200 ? req.responseText : ("'._("获取内容错误，代码：").'"+req.status);
   }
}
</script>';
}
?>