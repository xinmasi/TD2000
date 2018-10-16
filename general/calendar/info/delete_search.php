<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_org.php");
include_once("inc/utility_calendar.php");
$CAL_ID = td_trim($CAL_ID);
if($CAL_ID!="")
{
    $query="select * from CALENDAR where CAL_ID in ($CAL_ID)";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
     {
       $CAL_TIME=$ROW["CAL_TIME"];
       $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
       $CONTENTS=$ROW["CONTENT"];
       $MANAGER_ID=$ROW["MANAGER_ID"];
       $USER_ID=$ROW["USER_ID"];
       $MANAGER_NAME="";
       if($MANAGER_ID!="")
         $MANAGER_NAME=GetUserNameById($MANAGER_ID);
       if($MANAGER_ID=="")
       {
         $MANAGER_NAME=GetUserNameById($USER_ID);
         $MANAGER_ID=$USER_ID;
       }
       $SMS_CONTENT=_("请查看日程安排！")."\n"._("内容：").csubstr($CONTENTS,0,100);
       
       $MSG = sprintf(_("OA日程安排:%s为您安排新的工作，内容：%s"), $MANAGER_NAME,$CONTENTS);
       $SMS_CONTENT2=$MSG;
     }  
     $query="delete from CALENDAR where CAL_ID in ($CAL_ID)";
     exequery(TD::conn(),$query);     
	 //同步删除任务中心中日程信息
     delete_taskcenter('CALENDAR',$CAL_ID,1);
     delete_remind_sms(5, $MANAGER_ID, $SMS_CONTENT, $CAL_TIME);    
     DelSMS2byContent($MANAGER_ID, $SMS_CONTENT2);
     header("location: search.php?CAL_TYPE=0&SEND_TIME_MIN=$SEND_TIME_MIN&SEND_TIME_MAX=$SEND_TIME_MAX&CAL_LEVEL=$CAL_LEVEL&OVER_STATUS=$OVER_STATUS&CONTENT=$CONTENT&DEPT_ID=$DEPT_ID&IS_MAIN=1");
}
$AFF_ID=td_trim($AFF_ID);
if($AFF_ID!="")
{
   $query="delete from AFFAIR where AFF_ID in ($AFF_ID)";
   exequery(TD::conn(),$query);
   header("location: search.php?CAL_TYPE=1&SEND_TIME_MIN=$SEND_TIME_MIN&SEND_TIME_MAX=$SEND_TIME_MAX&CONTENT=$CONTENT&DEPT_ID=$DEPT_ID&IS_MAIN=1");
}
$TASK_ID=td_trim($TASK_ID);
if($TASK_ID!="")
{
   $query="select * from TASK where TASK_ID in ($TASK_ID) ";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
     $TASK_ID=$ROW["TASK_ID"];
     $SUBJECT=$ROW["SUBJECT"];     
     $ADD_TIME=$ROW['ADD_TIME'];
     $MANAGER_ID=$ROW["MANAGER_ID"];
     $REMIND_TIME=$ROW["REMIND_TIME"];
     $MANAGER_ID=$ROW["MANAGER_ID"];
     $USER_ID=$ROW["USER_ID"];
     $MANAGER_NAME="";
     if($MANAGER_ID!="")
        $MANAGER_NAME=GetUserNameById($MANAGER_ID);
     if($MANAGER_ID=="")
     {
         $MANAGER_NAME=GetUserNameById($USER_ID);
         $MANAGER_ID=$USER_ID;
     }  
    $MSG = sprintf(_("请查看%s安排的任务！"), $MANAGER_NAME);
    $SMS_CONTENT=$MSG."\n"._("标题：").csubstr($SUBJECT,0,50);
     $SMS_CONTENT2=_("OA任务:").$SUBJECT;
     $REMIND_URL="1:calendar/task/note.php?TASK_ID=".$TASK_ID;
   $query="delete from TASK where TASK_ID in ($TASK_ID) ";
   exequery(TD::conn(),$query);      
   //同步删除任务中心中日程信息
   delete_taskcenter('TASK_ID',$TASK_ID,1);
   delete_remind_sms(5,$MANAGER_ID,$SMS_CONTENT,"",$REMIND_URL);
   DelSMS2byContent($MANAGER_ID, $SMS_CONTENT2);
   header("location: search.php?CAL_TYPE=2&SEND_TIME_MIN=$SEND_TIME_MIN&SEND_TIME_MAX=$SEND_TIME_MAX&IMPORTANT=$IMPORTANT&TASK_STATUS=$TASK_STATUS&CONTENT=$CONTENT&DEPT_ID=$DEPT_ID&IS_MAIN=1");
   }
}

 
?>
