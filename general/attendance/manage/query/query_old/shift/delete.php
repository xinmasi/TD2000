<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

if($_SESSION["LOGIN_USER_PRIV"]==1)
{
  if($MYACTION=="out")
  {
     $query="delete from ATTEND_OUT where OUT_ID='$OUT_ID'";
     exequery(TD::conn(),$query);
     header("location: out.php?DEPARTMENT1=$DEPARTMENT1&DUTY_TYPE=$DUTY_TYPE&DATE1=$DATE1&DATE2=$DATE2&connstatus=1");
  }
  if($MYACTION=="leave")
  {
     $query="delete from ATTEND_LEAVE where LEAVE_ID='$LEAVE_ID'";
     exequery(TD::conn(),$query);
     header("location: leave.php?DEPARTMENT1=$DEPARTMENT1&DUTY_TYPE=$DUTY_TYPE&DATE1=$DATE1&DATE2=$DATE2&connstatus=1");
  }  
  if($MYACTION=="evection")
  {
     $query="delete from ATTEND_EVECTION where EVECTION_ID='$EVECTION_ID'";
     exequery(TD::conn(),$query);
     header("location: evection.php?DEPARTMENT1=$DEPARTMENT1&DUTY_TYPE=$DUTY_TYPE&DATE1=$DATE1&DATE2=$DATE2&connstatus=1");
  }   
  if($MYACTION=="evection")
  {
     $query="delete from ATTEND_EVECTION where EVECTION_ID='$EVECTION_ID'";
     exequery(TD::conn(),$query);
     header("location: evection.php?DEPARTMENT1=$DEPARTMENT1&DUTY_TYPE=$DUTY_TYPE&DATE1=$DATE1&DATE2=$DATE2&connstatus=1");
  }  
  if($MYACTION=="overtime")
  {
     $query="delete from ATTENDANCE_OVERTIME where OVERTIME_ID='$OVERTIME_ID'";
     exequery(TD::conn(),$query);
     header("location: overtime.php?DEPARTMENT1=$DEPARTMENT1&DUTY_TYPE=$DUTY_TYPE&DATE1=$DATE1&DATE2=$DATE2&connstatus=1");
  }  
}
?>
