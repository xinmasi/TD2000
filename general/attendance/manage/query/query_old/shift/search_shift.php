<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$query = "SELECT * from SYS_PARA where PARA_NAME='NO_DUTY_USER'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $NO_DUTY_USER=$ROW["PARA_VALUE"];
$WHERE_STR=" where 1=1";
if($DEPARTMENT1!="ALL_DEPT" && $DEPARTMENT1!='')
{
   $DET_LIST=substr(GetChildDeptId($DEPARTMENT1),0,-1);
   $WHERE_STR.=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";
}

if($DUTY_TYPE1!="ALL_TYPE" && $DUTY_TYPE1!='')
   $WHERE_STR.=" and USER_EXT.DUTY_TYPE='$DUTY_TYPE1' ";
   
$query4 = "SELECT USER.USER_NAME,USER.USER_ID,DEPARTMENT.DEPT_NAME from USER,USER_EXT,USER_PRIV,DEPARTMENT,ATTEND_DUTY_SHIFT ".$WHERE_STR." and not find_in_set(USER.USER_ID,'$NO_DUTY_USER') and USER_EXT.USER_ID=USER.USER_ID and USER_EXT.DUTY_TYPE='99' and DEPARTMENT.DEPT_ID = USER.DEPT_ID and USER.USER_PRIV=USER_PRIV.USER_PRIV and USER.USER_ID=ATTEND_DUTY_SHIFT.USER_ID group by ATTEND_DUTY_SHIFT.USER_ID order by DEPT_NO,PRIV_NO,USER_NO,USER_NAME";
$cursor4= exequery(TD::conn(),$query4);
$USER_COUNT=0;
$COUNT_ARRAY[$USER_COUNT][$COUNT_TYPE]=0;
while($ROW4=mysql_fetch_array($cursor4))
{
   $USER_COUNT++;
   $TIME_TOTAL = 0;
   $USER_NAME=$ROW4["USER_NAME"];
   $DEPT_NAME=$ROW4["DEPT_NAME"];
   $USER_ID=$ROW4["USER_ID"];
   
   $query5 = "select count(*) from ATTEND_DUTY_SHIFT where USER_ID='$USER_ID' and to_days(REGISTER_TIME)>=to_days('$DATE1') and to_days(REGISTER_TIME)<=to_days('$DATE2')";
   $cursor5 = exequery(TD::conn(),$query5);
   $DJCS=mysql_fetch_row($cursor5);
   
   $query1 = "SELECT * from ATTEND_DUTY_SHIFT where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$J') GROUP by to_days(REGISTER_TIME)";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
   {
   	  $REGISTER_TIME=$ROW["REGISTER_TIME"];
      $query="select * from ATTEND_EVECTION where USER_ID='$USER_ID' and ALLOW='1' and to_days(EVECTION_DATE1)<=to_days('$J') and to_days(EVECTION_DATE2)>=to_days('$J')";
      $cursor= exequery(TD::conn(),$query);
      if($ROW=mysql_fetch_array($cursor))
         $HOLIDAY=_("出差");
		$count_all = 0;
      for($I=1;$I<=6;$I++)
      {
          $DUTY_TIME_I="DUTY_TIME".$I;
          $DUTY_TIME_I=$$DUTY_TIME_I;
          $DUTY_TYPE_I="DUTY_TYPE".$I;
          $DUTY_TYPE_I=$$DUTY_TYPE_I;

          if($DUTY_TIME_I=="" || $DUTY_TIME_I=="00:00:00")
             continue;

          $HOLIDAY1="";
          if($HOLIDAY=="")
          {
              $query="select * from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1<='$J $DUTY_TIME_I' and LEAVE_DATE2>='$J $DUTY_TIME_I'";
              $cursor= exequery(TD::conn(),$query);
              if($ROW=mysql_fetch_array($cursor))
              {
       	         $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
       	         $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");
                 $HOLIDAY=_("请假");
                 $HOLIDAY1=_("请假");
              }
          }
          else
             $HOLIDAY1=$HOLIDAY;

          if($HOLIDAY==""&&$HOLIDAY1=="")
          {
             $query="select * from ATTEND_OUT where USER_ID='$USER_ID' and ALLOW='1' and to_days(SUBMIT_TIME)=to_days('$J') and OUT_TIME1<='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."' and OUT_TIME2>='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."'";
             $cursor= exequery(TD::conn(),$query);
             if($ROW=mysql_fetch_array($cursor))
                $HOLIDAY1=_("外出");
          }
      }
    }
      else
      {
         
           for($I=1;$I<=$COUNT;$I++)
           {
              $DUTY_TIME_I="DUTY_TIME".$I;
              $DUTY_TIME_I=$$DUTY_TIME_I;
              $DUTY_TYPE_I="DUTY_TYPE".$I;
              $DUTY_TYPE_I=$$DUTY_TYPE_I;

           	  $OUT = "";
              $query="select USER_ID from ATTEND_EVECTION where USER_ID='$USER_ID' and ALLOW='1' and to_days(EVECTION_DATE1)<=to_days('$J') and to_days(EVECTION_DATE2)>=to_days('$J')";
              $cursor= exequery(TD::conn(),$query);
              if($ROW=mysql_fetch_array($cursor))
                 $OUT=_("出差");
              $query="select * from ATTEND_OUT where USER_ID='$USER_ID' and ALLOW='1' and to_days(SUBMIT_TIME)=to_days('$J') and OUT_TIME1<='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."' and OUT_TIME2>='".substr($DUTY_TIME_I,0,strrpos($DUTY_TIME_I,":"))."'";
              $cursor= exequery(TD::conn(),$query);
              if($ROW=mysql_fetch_array($cursor))
                 $OUT=_("未登记外出");
              $query="select LEAVE_TYPE2 from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1 <= '$J $DUTY_TIME_I' and LEAVE_DATE2 >= '$J $DUTY_TIME_I'";
              $cursor= exequery(TD::conn(),$query);
              if($ROW=mysql_fetch_array($cursor))
              {
              	  $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
              	  $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");
                 $OUT=_("请假");

              }

              if($OUT=="" && $HOLIDAY=="")
              {
              	 if($DUTY_TYPE_I=="1")
              	    $COUNT_ARRAY[$USER_COUNT][6]++;
              	 if($DUTY_TYPE_I=="2")
              	    $COUNT_ARRAY[$USER_COUNT][8]++;
              }
           }
      }
}
ob_end_clean();
echo array_to_json($COUNT_ARRAY);
?>