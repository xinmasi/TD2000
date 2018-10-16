<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$EXCEL_OUT=array(_("部门"), _("姓名"), _("全勤(天)"), _("迟到"), _("上班未登记"), _("早退"), _("下班未登记"), _("加班上班登记"), _("加班下班登记"));

$query = "SELECT * from SYS_PARA where PARA_NAME='NO_DUTY_USER'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $NO_DUTY_USER=$ROW["PARA_VALUE"];

//---- 取规定上下班时间 -----
$query1 = "SELECT * from ATTEND_CONFIG";
$cursor1= exequery(TD::conn(),$query1);
while($ROW=mysql_fetch_array($cursor1))
{
   $DUTY_TYPE1=$ROW["DUTY_TYPE"];
   $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_NAME"]=$ROW["DUTY_NAME"];
   $ATTEND_CONFIG[$DUTY_TYPE1]["GENERAL"]=$ROW["GENERAL"];

   $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_TIME1"]=$ROW["DUTY_TIME1"];
   $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_TIME2"]=$ROW["DUTY_TIME2"];
   $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_TIME3"]=$ROW["DUTY_TIME3"];
   $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_TIME4"]=$ROW["DUTY_TIME4"];
   $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_TIME5"]=$ROW["DUTY_TIME5"];
   $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_TIME6"]=$ROW["DUTY_TIME6"];

   $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_TYPE1"]=$ROW["DUTY_TYPE1"];
   $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_TYPE2"]=$ROW["DUTY_TYPE2"];
   $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_TYPE3"]=$ROW["DUTY_TYPE3"];
   $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_TYPE4"]=$ROW["DUTY_TYPE4"];
   $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_TYPE5"]=$ROW["DUTY_TYPE5"];
   $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_TYPE6"]=$ROW["DUTY_TYPE6"];

   for($I=1;$I<=6;$I++)
   {
     $DUTY_TIME_I=$ROW["DUTY_TIME".$I];
     $DUTY_TYPE_I=$ROW["DUTY_TYPE".$I];

     if($DUTY_TIME_I=="")
        continue;

     if($DUTY_TYPE_I==1)
        $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_ON_TIMES"]++;
     else
        $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_OFF_TIMES"]++;
   }
}

//---- 查询用户的上下班时间 -----
$query = "SELECT * from USER,USER_PRIV,DEPARTMENT,USER_EXT where USER.UID=USER_EXT.UID and (USER.NOT_LOGIN = 0 or USER.NOT_MOBILE_LOGIN = 0) and not find_in_set(USER.USER_ID,'$NO_DUTY_USER') and DEPARTMENT.DEPT_ID=USER.DEPT_ID ";
if($DEPARTMENT1!="ALL_DEPT")
{
	  $DET_LIST=substr(GetChildDeptId($DEPARTMENT1),0,-1);
    $query.=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";
}

if($DUTY_TYPE!="ALL_TYPE")
   $query.=" and DUTY_TYPE='$DUTY_TYPE' ";

$query.= " and  USER.USER_PRIV=USER_PRIV.USER_PRIV order by DEPT_NO,PRIV_NO,USER_NO,USER_NAME";

$cursor= exequery(TD::conn(),$query);
$LINE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
  $USER_ID=$ROW["USER_ID"];
  $DEPT_ID=$ROW["DEPT_ID"];
  $USER_NAME=$ROW["USER_NAME"];
  $DUTY_TYPE=$ROW["DUTY_TYPE"];
  $USER_DEPT_NAME=$ROW["DEPT_NAME"];

  if(!is_dept_priv($DEPT_ID))
     continue;

  $DUTY_NAME=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_NAME"];
  $GENERAL=$ATTEND_CONFIG[$DUTY_TYPE]["GENERAL"];
  $DUTY_ON_TIMES=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_ON_TIMES"];
  $DUTY_OFF_TIMES=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_OFF_TIMES"];

  $DUTY_TIME1=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_TIME1"];
  $DUTY_TIME2=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_TIME2"];
  $DUTY_TIME3=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_TIME3"];
  $DUTY_TIME4=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_TIME4"];
  $DUTY_TIME5=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_TIME5"];
  $DUTY_TIME6=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_TIME6"];

  $DUTY_TYPE1=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_TYPE1"];
  $DUTY_TYPE2=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_TYPE2"];
  $DUTY_TYPE3=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_TYPE3"];
  $DUTY_TYPE4=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_TYPE4"];
  $DUTY_TYPE5=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_TYPE5"];
  $DUTY_TYPE6=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_TYPE6"];

  $LINE_COUNT++;

 $PERFECT_COUNT="";
 $EARLY_COUNT="";
 $LATE_COUNT="";
 $DUTY_ON_COUNT="";
 $DUTY_OFF_COUNT="";
 $DUTY_ON_TOTAL="";
 $DUTY_OFF_TOTAL="";
 $OVER_ON_COUNT="";
 $OVER_OFF_COUNT="";

 for($J=$DATE1;$J<=$DATE2;$J=date("Y-m-d",strtotime($J)+24*3600))
 {
    $WEEK=date("w",strtotime($J));
    $HOLIDAY=0;
    if(find_id($GENERAL,$WEEK))
       $HOLIDAY=1;
    if($HOLIDAY==0)
    {
       $query="select count(*) from ATTEND_HOLIDAY where BEGIN_DATE <='$J' and END_DATE>='$J'";
       $cursor1= exequery(TD::conn(),$query);
       if($ROW=mysql_fetch_array($cursor1))
          $HOLIDAY=$ROW[0];
    }
    if($HOLIDAY==0)
    {
       $query="select count(*) from ATTEND_EVECTION where USER_ID='$USER_ID' and ALLOW='1' and to_days(EVECTION_DATE1)<=to_days('$J') and to_days(EVECTION_DATE2)>=to_days('$J')";
       $cursor1= exequery(TD::conn(),$query);
       if($ROW=mysql_fetch_array($cursor1))
          $HOLIDAY=$ROW[0];
    }
    if($HOLIDAY==0)
    {
       $query="select count(*) from ATTEND_LEAVE where USER_ID='$USER_ID' and ALLOW='1' and LEAVE_DATE1<='$J $DUTY_TIME' and LEAVE_DATE2>='$J $DUTY_TIME'";
       $cursor1= exequery(TD::conn(),$query);
       if($ROW=mysql_fetch_array($cursor1))
          $HOLIDAY=$ROW[0];
    }
    if($HOLIDAY==0)
    {
       $query="select count(*) from ATTEND_OUT where USER_ID='$USER_ID' and ALLOW='1' and to_days(SUBMIT_TIME)=to_days('$J') and OUT_TIME1<='".substr($DUTY_TIME,0,strrpos($DUTY_TIME,":"))."' and OUT_TIME2>='".substr($DUTY_TIME,0,strrpos($DUTY_TIME,":"))."'";
       $cursor1= exequery(TD::conn(),$query);
       if($ROW=mysql_fetch_array($cursor1))
          $HOLIDAY=$ROW[0];
    }

    if($HOLIDAY==0)
    {
        $DUTY_ON_TOTAL+=$DUTY_ON_TIMES;
        $DUTY_OFF_TOTAL+=$DUTY_OFF_TIMES;
    }

    $PERFECT_FLAG=0;
    $query1 = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$J')";
    $cursor1= exequery(TD::conn(),$query1);
    while($ROW=mysql_fetch_array($cursor1))
    {
       $REGISTER_TYPE=$ROW["REGISTER_TYPE"];
       $REGISTER_TIME=$ROW["REGISTER_TIME"];
       $SOME_DATE=strtok($REGISTER_TIME," ");
       $REGISTER_TIME=strtok(" ");

       $STR="DUTY_TIME".$REGISTER_TYPE;
       $DUTY_TIME=$$STR;

       $STR="DUTY_TYPE".$REGISTER_TYPE;
       $DUTY_TYPE=$$STR;

       if($DUTY_TIME=="")
          continue;

       if($DUTY_TYPE=="1")
       {
         if(compare_time($REGISTER_TIME,$DUTY_TIME)< 1)
            $PERFECT_FLAG++;

         if($HOLIDAY>0)
         {
           $OVER_ON_COUNT++;
           continue;
         }

         $DUTY_ON_COUNT++;
         if(compare_time($REGISTER_TIME,$DUTY_TIME)==1)
            $LATE_COUNT++;
       }

       if($DUTY_TYPE=="2")
       {
         if(compare_time($REGISTER_TIME,$DUTY_TIME)>-1)
            $PERFECT_FLAG++;

         if($HOLIDAY>0)
         {
           $OVER_OFF_COUNT++;
           continue;
         }

          $DUTY_OFF_COUNT++;
          if(compare_time($REGISTER_TIME,$DUTY_TIME)==-1)
             $EARLY_COUNT++;
       }
    }

    if($PERFECT_FLAG>=$DUTY_ON_TIMES+$DUTY_OFF_TIMES)
       $PERFECT_COUNT++;
 }

$DUTY_ON_TOTAL1=$DUTY_ON_TOTAL - $DUTY_ON_COUNT;
$DUTY_OFF_TOTAL1=$DUTY_OFF_TOTAL - $DUTY_OFF_COUNT;

$EXCEL_OUT.="$USER_DEPT_NAME,$USER_NAME,$PERFECT_COUNT,$LATE_COUNT,$DUTY_ON_TOTAL1,$EARLY_COUNT,$DUTY_OFF_TOTAL1,$OVER_ON_COUNT,$OVER_OFF_COUNT\n";
}

ob_end_clean();
Header("Cache-control: private");
Header("Content-type: application/vnd.ms-excel");
Header("Accept-Ranges: bytes");
Header("Accept-Length: ".strlen($EXCEL_OUT));
Header("Content-Length: ".strlen($EXCEL_OUT));
Header("Content-Disposition: attachment; ".get_attachment_filename(_("上下班登记数据").".csv"));

if(MYOA_IS_UN == 1)
   echo chr(0xEF).chr(0xBB).chr(0xBF);

echo $EXCEL_OUT;
?>