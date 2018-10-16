<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();
$MODULE_BODY="";
if($UPDATE_STATUS==1)
{
  $query = "UPDATE CALENDAR SET OVER_STATUS='$OVER_STATUS' WHERE USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and CAL_ID='$CAL_ID'";
  $cursor = exequery(TD::conn(),$query);
}

if($SHOW_FLAG=="1")
{
   $MODULE_BODY.="<ul>";
   $CUR_DATE=date("Y-m-d",time());
   $CUR_TIME=date("Y-m-d H:i:s",time());
   $CUR_TIME_U=time();
   $COUNT=0;
   $CUR_DATE1=date("Y-m-d H:i:s",time()+10*24*60*60);
   $THE_BEGIN=date("Y-m-d H:i:s",time()-10*24*60*60);
   $THE_BEGIN=substr($THE_BEGIN,0,10)." 00:00:00";
   $THE_END=$CUR_DATE1;
   $THE_END=substr($THE_END,0,10)." 23:59:59";
   $THE_BEGIN_U=strtotime($THE_BEGIN);
   $THE_END_U=strtotime($THE_END);
   $DATE_STR="((CAL_TIME<='$THE_END_U' and CAL_TIME>='$THE_BEGIN_U') or (END_TIME<='$THE_END_U' and END_TIME>='$THE_BEGIN_U') or (CAL_TIME<='$THE_BEGIN_U' and END_TIME>='$THE_END_U'))";
   $queryq = "SELECT * from CALENDAR where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and ".$DATE_STR." order by CAL_TIME limit 0,$MAX_COUNT";
   $cursorq= exequery(TD::conn(),$queryq);
   while($ROWQ=mysql_fetch_array($cursorq))
   {
      $COUNT++;
      $CAL_ID=$ROWQ["CAL_ID"];
      $CAL_TIME=$ROWQ["CAL_TIME"];
      $END_TIME=$ROWQ["END_TIME"];
      $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
      $END_TIME=date("Y-m-d H:i:s",$END_TIME);
      $MANAGER_ID=$ROWQ["MANAGER_ID"];
      $CONTENT=$ROWQ["CONTENT"];

      $SUBJECT_TITLE="";
      if(strlen($CONTENT) > 50)
      {
         $SUBJECT_TITLE=td_htmlspecialchars($CONTENT);
         $CONTENT=csubstr($CONTENT, 0, 50)."...";
      }
      $CONTENT=td_htmlspecialchars($CONTENT);
      if($MANAGER_ID!=""&&$MANAGER_ID!=$_SESSION["LOGIN_USER_ID"])
         $CONTENT="<b>".$CONTENT."</b>";

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

      $OVER_STATUS=$ROWQ["OVER_STATUS"];

      if($OVER_STATUS=="" || $OVER_STATUS=="1")
         $OVER_STATUS1="<font color='#00AA00'><b>"._("已完成")."</b></font>";
      else if($OVER_STATUS=="0")
         $OVER_STATUS1="";

      $MODULE_BODY.='<li><div id="cal1_'.$CAL_ID.'">';
      $MODULE_BODY.='  '.$CAL_TIME.' - '.$END_TIME.' <a href="javascript:my_note_cal('.$CAL_ID.')" title="'.$SUBJECT_TITLE.'">'.$CONTENT.'</a> '.$OVER_STATUS1;
      $MODULE_BODY.="<span id=\"cal1_".$CAL_ID."_op\" style=\"display:none;\">";
      if($OVER_STATUS=="0")
         $MODULE_BODY.="<a href=\"?STATUS=1&CAL_ID=$CAL_ID&OVER_STATUS=1&YEAR=$YEAR&MONTH=$MONTH&DAY=$DAY\"> &nbsp;"._("完成")."</a>";
      elseif($OVER_STATUS=="" || $OVER_STATUS=="1")
         $MODULE_BODY.="<a href=\"?STATUS=1&CAL_ID=$CAL_ID&OVER_STATUS=0&YEAR=$YEAR&MONTH=$MONTH&DAY=$DAY\"> "._("未完成")."</a>";
      if($MANAGER_ID!="" && $MANAGER_ID!=$_SESSION["LOGIN_USER_ID"])
      {
         $query12 = "SELECT USER_NAME from USER where USER_ID='$MANAGER_ID'";
         $cursor12= exequery(TD::conn(),$query12);
         if($ROW12=mysql_fetch_array($cursor12))
            $MANAGER_NAME=$ROW12["USER_NAME"];
         $MODULE_BODY.=' <a href="javascript:sms_back(\''.$MANAGER_ID.'\',\''.$MANAGER_NAME.'\');">'._("回复微讯").'</a>';
      }

      if($MANAGER_ID=="" || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"])
      {
         $MODULE_BODY.=_("<a  onclick=window.open('\"/general/calendar/arrange/edit_table.php?FROM=1&CAL_ID=$CAL_ID&OVER_STATUS=1&YEAR=$YEAR&MONTH=$MONTH&DAY=$DAY\"');> 修改</a>");
      }
      $MODULE_BODY.="</span></div>";
      $MODULE_BODY.='</li>';
   }

   $MAX_COUNT2 = $MAX_COUNT - $COUNT;
   $DATE_STR="((BEGIN_TIME<='$THE_END_U' and BEGIN_TIME>='$THE_BEGIN_U') or (END_TIME<='$THE_END_U' and END_TIME>='$THE_BEGIN_U') or (BEGIN_TIME<='$THE_BEGIN_U' and END_TIME>='$THE_END_U') or (BEGIN_TIME<='$THE_BEGIN_U' and END_TIME is null))";
   $query2 = "SELECT * from AFFAIR where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and ".$DATE_STR."order by BEGIN_TIME desc limit 0,$MAX_COUNT2";
   $cursor2= exequery(TD::conn(),$query2);
   while($ROW2=mysql_fetch_array($cursor2))
   {
     $COUNT++;
     $AFF_ID=$ROW2["AFF_ID"];
     $CONTENT=$ROW2["CONTENT"];
     $MANAGER_ID=$ROW2["MANAGER_ID"];
     $REMIND_TIME=$ROW2["REMIND_TIME"];

     if($MANAGER_ID!=""&&$MANAGER_ID!=$_SESSION["LOGIN_USER_ID"])
        $MODULE_BODY.='<li>'.substr($REMIND_TIME,0,5).' <b><a href="javascript:my_affair('.$AFF_ID.')">'.$CONTENT.'</a></b></li>';
     else
        $MODULE_BODY.='<li>'.substr($REMIND_TIME,0,5).' <a href="javascript:my_affair('.$AFF_ID.')">'.$CONTENT.'</a></li>';
   }

   if($COUNT==0)
      $MODULE_BODY.= "<li>"._("近日暂无日程安排")."</li>";

   $MODULE_BODY.= "<ul>";
}
else if($SHOW_FLAG=="0"||$SHOW_FLAG=="")
{
   $MODULE_BODY.= "<ul>";
   $COUNT=0;
   $CUR_DATE=date("Y-m-d",time());
   $CUR_TIME=date("Y-m-d H:i:s",time());
   $CUR_TIME_U = time();
   $querys = "SELECT * from CALENDAR where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and to_days(from_unixtime(CAL_TIME))<=to_days('$CUR_DATE') and to_days(from_unixtime(END_TIME))>=to_days('$CUR_DATE') order by CAL_TIME limit 0,$MAX_COUNT";
   $cursors= exequery(TD::conn(),$querys);
   while($ROWS=mysql_fetch_array($cursors))
   {
      $COUNT++;
      $CAL_ID=$ROWS["CAL_ID"];
      $CAL_TIME=$ROWS["CAL_TIME"];
      $END_TIME=$ROWS["END_TIME"];
      $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
      $END_TIME=date("Y-m-d H:i:s",$END_TIME);
      $MANAGER_ID=$ROWS["MANAGER_ID"];

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

      $CONTENT=$ROWS["CONTENT"];

      $SUBJECT_TITLE="";
      if(strlen($CONTENT) > 50)
      {
         $SUBJECT_TITLE=td_htmlspecialchars($CONTENT);
         $CONTENT=csubstr($CONTENT, 0, 50)."...";
      }
      $CONTENT=td_htmlspecialchars($CONTENT);
      if($MANAGER_ID!=""&&$MANAGER_ID!=$_SESSION["LOGIN_USER_ID"])
         $CONTENT="<b>".$CONTENT."</b>";

      $OVER_STATUS=$ROWS["OVER_STATUS"];
      if($OVER_STATUS=="" || $OVER_STATUS=="1")
         $OVER_STATUS1="<font color='#00AA00'><b>"._("已完成")."</b></font>";
      elseif($OVER_STATUS=="0")
         $OVER_STATUS1="";
      $MODULE_BODY.='<li><div id="cal_'.$CAL_ID.'">'.$CAL_TIME.' - '.$END_TIME.' <a href="javascript:my_note_cal('.$CAL_ID.')" title="'.$SUBJECT_TITLE.'">'.$CONTENT.'</a> '.$OVER_STATUS1;
      $MODULE_BODY.="<span id=\"cal_".$CAL_ID."_op\" style=\"display:none;\">";
      if($OVER_STATUS=="0") 
         $MODULE_BODY.=_("<a href=\"?UPDATE_STATUS=1&CAL_ID=$CAL_ID&OVER_STATUS=1&YEAR=$YEAR&MONTH=$MONTH&DAY=$DAY\"> &nbsp;完成</a>");
      elseif($OVER_STATUS=="" || $OVER_STATUS=="1")
         $MODULE_BODY.=_("<a href=\"?UPDATE_STATUS=1&CAL_ID=$CAL_ID&OVER_STATUS=0&YEAR=$YEAR&MONTH=$MONTH&DAY=$DAY\"> &nbsp;未完成</a>");
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
      $MODULE_BODY.=_("<a onclick=\"window.open('/general/calendar/arrange/edit_table.php?FROM=1&CAL_ID=$CAL_ID&OVER_STATUS=1&YEAR=$YEAR&MONTH=$MONTH&DAY=$DAY')\";> 修改</a>");
     }
   $MODULE_BODY.="</span></div>";
   $MODULE_BODY.='</li>';
   
   }

   //============================ 显示日常事务 =======================================
   $query3 = "SELECT * from AFFAIR where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and BEGIN_TIME<='$CUR_TIME_U' and (END_TIME='' or END_TIME='0' or END_TIME>='$CUR_TIME_U') order by REMIND_TIME";
   $cursor3= exequery(TD::conn(),$query3);
   while($ROW3=mysql_fetch_array($cursor3))
   {
      $AFF_ID=$ROW3["AFF_ID"];
      $USER_ID=$ROW3["USER_ID"];
      $TYPE=$ROW3["TYPE"];
      $REMIND_DATE=$ROW3["REMIND_DATE"];
      $REMIND_TIME=$ROW3["REMIND_TIME"];
      $CONTENT=$ROW3["CONTENT"];
      $MANAGER_ID=$ROW3["MANAGER_ID"];
      $OVER_STATUS=$ROW3["OVER_STATUS"];

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
}
else if($SHOW_FLAG=="2")
{
   $MODULE_BODY= "<ul>";
   $query = "SELECT * from TASK where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by TASK_NO limit 0,$MAX_COUNT";
   $cursor= exequery(TD::conn(),$query);
   $TASK_COUNT=0;

   while($ROW=mysql_fetch_array($cursor))
   {
      $TASK_COUNT++;

      $TASK_ID=$ROW["TASK_ID"];
      $TASK_NO=$ROW["TASK_NO"];

      $TASK_TYPE=$ROW["TASK_TYPE"];
      $TASK_STATUS=$ROW["TASK_STATUS"];

      $SUBJECT=$ROW["SUBJECT"];
      $SUBJECT=td_htmlspecialchars($SUBJECT);
      if(strlen($SUBJECT)>40)
         $SUBJECT=csubstr($SUBJECT,0,40)."...";

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

      $MODULE_BODY.='<li>';
      $MODULE_BODY.='<a href="javascript:" onclick="my_task_note(\''.$TASK_ID.'\')">['.$TYPE_DESC.'] '.$SUBJECT.' '.$STATUS.'</a></li>';
   }
   if($TASK_COUNT==0)
      $MODULE_BODY.="<li>"._("暂无日程安排任务")."</li>";

   $MODULE_BODY.= "</ul>";
}

if($MODULE_SCROLL=="true" && stristr($MODULE_BODY, "href"))
{
   $MODULE_BODY='<marquee id="module_'.$MODULE_ID.'_marquee" height="100%" direction="up" behavior=scroll scrollamount=2 scrolldelay=100 onmouseover="this.stop()" onmouseout="this.start()" border=0>'.$MODULE_BODY.'</marquee>';
}
echo $MODULE_BODY;
?>