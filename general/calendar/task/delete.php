<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_calendar.php");
$TASK_ID = td_trim($TASK_ID);
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";
if($TASK_ID!="")
{
   $query="select * from TASK where TASK_ID in ($TASK_ID) ";
   $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
   if($ROW=mysql_fetch_array($cursor))
   {
     $SUBJECT=$ROW["SUBJECT"];
     
     $MSG = sprintf(_("请查看%s安排的任务！"), $_SESSION["LOGIN_USER_NAME"]);
     $SMS_CONTENT=$MSG."\n"._("标题：").csubstr($SUBJECT,0,50);
     $SMS_CONTENT2=_("OA任务:").$SUBJECT;
     $REMIND_URL="1:calendar/task/note.php?TASK_ID=".$TASK_ID;
     $ADD_TIME=$ROW['ADD_TIME'];
     $MANAGER_ID=$ROW["MANAGER_ID"];
     $REMIND_TIME=$ROW["REMIND_TIME"];
        
    if($ADD_TIME!="0000-00-00 00:00:00")
    {
           $querys="select TASK_ID from TASK where ADD_TIME='$ADD_TIME' and MANAGER_ID='$MANAGER_ID'";
           $cursors=exequery(TD::conn(),$querys,$QUERY_MASTER);
           $TASK_ID_STR="";
           while($ROWS=mysql_fetch_array($cursors))
           {
      	      $TASK_IDS=$ROWS["TASK_ID"];
              $TASK_ID_STR.=$TASK_IDS.",";
           }
          if(substr($TASK_ID_STR,-1)==",")
             $TASK_ID_STR=substr($TASK_ID_STR,0,-1);
      }
      else
      {
         $TASK_ID_STR=$TASK_ID;
      }
   }
   if($FLAG==1 || $FLAG=="info")
   {
      $query="delete from TASK where TASK_ID in ($TASK_ID_STR) ";
      delete_taskcenter('TASK',$TASK_ID_STR,1);
   }
   else
   {
      $query="delete from TASK where TASK_ID ='$TASK_ID'";
       delete_taskcenter('TASK',$TASK_ID);
   }
   exequery(TD::conn(),$query);      
   delete_remind_sms(5,$_SESSION["LOGIN_USER_ID"],$SMS_CONTENT,"",$REMIND_URL);
   DelSMS2byContent($_SESSION["LOGIN_USER_ID"], $SMS_CONTENT2);
}
if($FROM!=1)
{
if($FLAG=="info")
{
   $REFER_URL=$_COOKIE["cal_info_view"]=="" ? "../info/index.php" : "../info/".$_COOKIE["cal_info_view"].".php";
   header("location: $REFER_URL?OVER_STATUS=$OVER_STATUS&YEAR=$YEAR&MONTH=$MONTH&DAY=$DAY&DEPT_ID=$DEPT_ID&IS_MAIN=1");
   exit;
}
else
{
   header("location: index.php?PAGE_START=$PAGE_START&IS_MAIN=1");
   exit;
}  
}
else
{  
	if($FLAG=="info" || $FLAG==1)
	  $REFER_URL=$_COOKIE["cal_info_view"]=="" ? "../info/index.php" : "../info/".$_COOKIE["cal_info_view"].".php";
	else if($FLAG==2)
	  $REFER_URL="../task/index.php?IS_MAIN=1"; 
	else
	  $REFER_URL=$_COOKIE["cal_view"]=="" ? "../arrange/index.php" : "../arrange/".$_COOKIE["cal_view"].".php";
   header("location: $REFER_URL?OVER_STATUS=$OVER_STATUS&YEAR=$YEAR&MONTH=$MONTH&DAY=$DAY&DEPT_ID=$DEPT_ID&IS_MAIN=1");
	
	/*
?>
<script Language="JavaScript">
window.opener.location.reload();
window.close();

</script>

<?
*/
}
?>
