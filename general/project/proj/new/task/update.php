<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$year1 = substr ($TASK_START_TIME , 0 , 4 );    
$month1 = substr ($TASK_START_TIME , 5 , 2 );    
$day1 = substr ($TASK_START_TIME , 8 , 2 );
$year2 = substr ($TASK_END_TIME , 0 , 4 );    
$month2 = substr ($TASK_END_TIME , 5 , 2 );    
$day2 = substr ($TASK_END_TIME , 8 , 2 );
$time1 = mktime (0, 0, 0, $month1, $day1, $year1); 
$time2 = mktime (0, 0, 0, $month2, $day2, $year2); 
$TASK_TIME = floor(($time2-$time1)/86400)+1;
//---djg----
$query = "SELECT PROJ_NAME from proj_project where PROJ_ID='$PROJ_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $PROJ_NAME=$ROW['PROJ_NAME'];
}
$CONTENT_LOG = $_SESSION['LOGIN_USER_NAME'] ." 从项目：".$PROJ_NAME." 中给我分配了任务: " . $TASK_NAME . "！";
if ($CAL_ID ==0)
{
if($_POST['add_executor']==9){
 $query1 = "insert into calendar(USER_ID,CONTENT,OVER_STATUS,ALLDAY,CAL_TIME,END_TIME,CAL_LEVEL) 
 VALUES  ('$TASK_USER','$CONTENT_LOG','0','1','$time1','$time2','$TASK_LEVEL')";
 exequery(TD::conn(),$query1);
 $CAL_ID=mysql_insert_id();
  } 
}
if($CAL_ID !=0 )
{ 
$USER_COUNT=0;
$query = "select * from calendar where CAL_ID='$CAL_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $USER_COUNT++;
}
if($USER_COUNT==0){
$query1 = "insert into calendar(CAL_ID,USER_ID,CONTENT,OVER_STATUS,ALLDAY,CAL_TIME,END_TIME,CAL_LEVEL) 
 VALUES  ('$CAL_ID','$TASK_USER','$CONTENT_LOG','0','1','$time1','$time2','$TASK_LEVEL')";
 exequery(TD::conn(),$query1);
        }
}


if($TASK_MILESTONE=="on")
   $TASK_MILESTONE="1";
else 
   $TASK_MILESTONE="0";

if($CONSTRAIN=="on")
   $CONSTRAIN="1";
else 
   $CONSTRAIN="0";
 
 
$query1 = "update calendar SET USER_ID='$TASK_USER',CONTENT='$CONTENT_LOG',OVER_STATUS='0',ALLDAY='1',CAL_TIME='$time1',END_TIME='$time2',CAL_LEVEL='$TASK_LEVEL' where CAL_ID='$CAL_ID'"; 
exequery(TD::conn(),$query1);

$query = "update PROJ_TASK SET CAL_ID='$CAL_ID',TASK_NO='$TASK_NO', TASK_NAME='$TASK_NAME', TASK_DESCRIPTION='$TASK_DESCRIPTION', TASK_USER='$TASK_USER', TASK_MILESTONE='$TASK_MILESTONE', TASK_START_TIME='$TASK_START_TIME', TASK_END_TIME='$TASK_END_TIME', TASK_TIME='$TASK_TIME', TASK_LEVEL='$TASK_LEVEL',PARENT_TASK='$PARENT_TASK',PRE_TASK='$PRE_TASK',REMARK='$REMARK',FLOW_ID_STR='$FLOW_ID_STR',TAST_CONSTRAIN='$CONSTRAIN' WHERE TASK_ID='$TASK_ID'";
exequery(TD::conn(),$query);
header("Location:index.php?PROJ_ID=$PROJ_ID");
?>