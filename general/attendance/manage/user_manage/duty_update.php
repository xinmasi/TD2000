<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$query1="select * from USER where USER_ID='$USER_ID'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW=mysql_fetch_array($cursor1))
   $DEPT_ID=$ROW["DEPT_ID"];

$query1="select DUTY_TYPE from USER_EXT where USER_ID='$USER_ID'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW=mysql_fetch_array($cursor1))
    $DUTY_TYPE_USER=$ROW["DUTY_TYPE"];

if(!is_dept_priv($DEPT_ID) && $_SESSION["LOGIN_USER_PRIV"]!=1)
{
   Message ("",_("无上下班登记修改权限"));
   exit;
}

for($I=1;$I<=6;$I++)
{
  $STR="REGISTER_TIME".$I;
  $REGISTER_TIME=$$STR;
  if(strlen($REGISTER_TIME)<=5)
     $REGISTER_TIME.=":00";

  $REGISTER_TIME1="";
  $query = "select * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$SOME_DATE') and REGISTER_TYPE='$I'";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
  {
     $REGISTER_TIME1=$ROW["REGISTER_TIME"];
     $REMARK_OLD=$ROW["REMARK"];
  }

  if($REGISTER_TIME=="") //删除记录
  {
     $query = "delete from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$SOME_DATE') and REGISTER_TYPE='$I'";
     $cursor= exequery(TD::conn(),$query);

     $REMARK=sprintf(_("删除考勤记录，日期[%s]，类型[%s]"), $SOME_DATE, $I).",USER_ID=".$USER_ID;
     if($REGISTER_TIME1!="")
        add_log(13,$REMARK,$_SESSION["LOGIN_USER_ID"]);
  }
  else if(is_time($REGISTER_TIME))
  {
     $REGISTER_TIME=$SOME_DATE." ".$REGISTER_TIME;
     if($REMARK_EDIT!="")
        $REMARK_DATA=$REMARK_OLD._("  修改原因：").$REMARK_EDIT;
     else
        $REMARK_DATA=$REMARK_OLD;
     $query = "select * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$SOME_DATE') and REGISTER_TYPE='$I'";
     $cursor= exequery(TD::conn(),$query);
     if($ROW=mysql_fetch_array($cursor)) //存在记录，则更新
     {
     		if($REGISTER_TIME_OLD!=$REGISTER_TIME)
     		{
     		    $query = "update ATTEND_DUTY set REMARK='$REMARK_DATA',REGISTER_TIME='$REGISTER_TIME',DUTY_TYPE='$DUTY_TYPE_USER',REGISTER_IP='".$_SESSION["LOGIN_USER_NAME"]._("修改' where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$SOME_DATE') and REGISTER_TYPE='$I'");
     		}
        
     }
     else
     {
     		$MSG = sprintf(_("%s修改"), $_SESSION["LOGIN_USER_NAME"]);
        $query = "insert into  ATTEND_DUTY(USER_ID,REGISTER_TYPE,REGISTER_TIME,REGISTER_IP,REMARK,DUTY_TYPE ) values ('$USER_ID','$I','$REGISTER_TIME','$MSG','$REMARK_DATA','$DUTY_TYPE_USER')";
     }
     $cursor= exequery(TD::conn(),$query);

     $REMARK=sprintf(_("修改考勤时间[%s],原时间[%s]"), $REGISTER_TIME, $REGISTER_TIME1).",USER_ID=".$USER_ID;
     if($REGISTER_TIME1!=$REGISTER_TIME)
        add_log(13,$REMARK,$_SESSION["LOGIN_USER_ID"]);
  }
}

header("location: some_day.php?SOME_DATE=$SOME_DATE&USER_ID=$USER_ID&connstatus=1");
?>