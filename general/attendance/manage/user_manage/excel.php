<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$FILENAME=_("人员考勤记录");
  //----------- 合法性校验 ---------
   if($DATE1!="")
   {
      $TIME_OK=is_date($DATE1);
   
      if(!$TIME_OK)
      {
         Message(_("错误"),_("起始日期格式不对，应形如 1999-1-2"));
         Button_Back();
         exit;
      }
   }
   
   if($DATE2!="")
   {
      $TIME_OK=is_date($DATE2);
   
      if(!$TIME_OK)
      {
         Message(_("错误"),_("截止日期格式不对，应形如 1999-1-2"));
         Button_Back();
         exit;
      }
   }
   
   if(compare_date($DATE1,$DATE2)==1)
   { 
      Message(_("错误"),_("查询的起始日期不能晚于截止日期"));
      Button_Back();
      exit;
   }

   $query = "SELECT * from USER_EXT,USER where USER.UID=USER_EXT.UID and USER.USER_ID='$USER_ID'";
   $cursor= exequery(TD::conn(),$query);
   $LINE_COUNT=0;
   if($ROW=mysql_fetch_array($cursor))
   {
      $USER_ID=$ROW["USER_ID"];
      $USER_NAME=$ROW["USER_NAME"];
      $DUTY_TYPE=$ROW["DUTY_TYPE"];
      $DEPT_ID=$ROW["DEPT_ID"];
   }
   
   if(!is_dept_priv($DEPT_ID) && $_SESSION["LOGIN_USER_PRIV"]!=1)
   {
    	Message(_("错误"),_("不属于管理范围内的用户").$DEPT_ID);
      exit;
   }
   
   $CUR_DATE=date("Y-m-d",time());
   
   $query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $DAY_TOTAL=$ROW[0]+1;
      
$MSG = sprintf(_("共（ %d ）天"), $DAY_TOTAL);

ob_end_clean();
require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName($FILENAME);


?>

<!------------------------------------- 上下班 ------------------------------->
<table border="1" width="100%" cellspacing="0" cellpadding="3" >
  <tr>
    <td colspan="8" align="center"><?=_("上下班统计")?>
    (<?=$USER_NAME?> <?=_("从")?> <?=format_date($DATE1)?> <?=_("至")?> <?=format_date($DATE2)?> <?=$MSG?>)
    </td>
  </tr>
</table>

<?
if(MYOA_IS_UN == 1)
	$OUTPUT_HEAD_DUTY="ALLDAYS(DAY),LATE,GOTO_UNREGISTERED,LEAVE_EARLY,LEAVE_UNREGISTERED,GOTO_OVERTIME_WORK,LEAVE_OVERTIME_WORK";
else
	$OUTPUT_HEAD_DUTY=array(_("全勤(天)"),_("迟到"),_("上班未登记"),_("早退"),_("下班未登记"),_("加班上班登记"),_("加班下班登记"));
	
$objExcel->addHead($OUTPUT_HEAD_DUTY);
 //---- 取规定上下班时间 -----
$DUTY_TYPE=intval($DUTY_TYPE);
$query1 = "SELECT * from ATTEND_CONFIG where DUTY_TYPE='$DUTY_TYPE'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW=mysql_fetch_array($cursor1))
{
   $DUTY_NAME=$ROW["DUTY_NAME"];
   $GENERAL=$ROW["GENERAL"];

   $DUTY_TIME1=$ROW["DUTY_TIME1"];
   $DUTY_TIME2=$ROW["DUTY_TIME2"];
   $DUTY_TIME3=$ROW["DUTY_TIME3"];
   $DUTY_TIME4=$ROW["DUTY_TIME4"];
   $DUTY_TIME5=$ROW["DUTY_TIME5"];
   $DUTY_TIME6=$ROW["DUTY_TIME6"];

   $DUTY_TYPE1=$ROW["DUTY_TYPE1"];
   $DUTY_TYPE2=$ROW["DUTY_TYPE2"];
   $DUTY_TYPE3=$ROW["DUTY_TYPE3"];
   $DUTY_TYPE4=$ROW["DUTY_TYPE4"];
   $DUTY_TYPE5=$ROW["DUTY_TYPE5"];
   $DUTY_TYPE6=$ROW["DUTY_TYPE6"];
}

$EARLY_COUNT="";
$LATE_COUNT="";
$DUTY_ON_COUNT="";
$DUTY_OFF_COUNT="";
$DUTY_ON_TOTAL="";
$DUTY_OFF_TOTAL="";
$OVER_ON_COUNT="";
$OVER_OFF_COUNT="";

for($I=1;$I<=6;$I++)
{
   $STR="DUTY_TIME".$I;
   $DUTY_TIME=$$STR;

   $STR="DUTY_TYPE".$I;
   $DUTY_TYPE=$$STR;

   if($DUTY_TIME=="")
      continue;

   if($DUTY_TYPE==1)
      $DUTY_ON_TOTAL+=$DAY_TOTAL;
   else
      $DUTY_OFF_TOTAL+=$DAY_TOTAL;

   for($J=$DATE1;$J<=$DATE2;$J=date("Y-m-d",strtotime($J)+24*3600))
   {
      $WEEK=date("w",strtotime($J));
      $HOLIDAY=0;
      $query="select * from ATTEND_HOLIDAY where BEGIN_DATE <='$J' and END_DATE>='$J'";
      $cursor= exequery(TD::conn(),$query);
      if($ROW=mysql_fetch_array($cursor))
         $HOLIDAY=1;
      else
         if(find_id($GENERAL,$WEEK))
            $HOLIDAY=1;

      if($HOLIDAY==0)
      {
         $query="select * from ATTEND_EVECTION where USER_ID='$USER_ID' and ALLOW='1' and to_days(EVECTION_DATE1)<=to_days('$J') and to_days(EVECTION_DATE2)>=to_days('$J')";
         $cursor= exequery(TD::conn(),$query);
         if($ROW=mysql_fetch_array($cursor))
            $HOLIDAY=1;
      }
      if($HOLIDAY==0)
      {
         $query="select * from ATTEND_LEAVE where USER_ID='$USER_ID' and ALLOW='1' and LEAVE_DATE1<='$J $DUTY_TIME' and LEAVE_DATE2>='$J $DUTY_TIME'";
         $cursor= exequery(TD::conn(),$query);
         if($ROW=mysql_fetch_array($cursor))
            $HOLIDAY=1;
      }

      if($HOLIDAY==1)
      {
         if($DUTY_TYPE==1)
             $DUTY_ON_TOTAL--;
         else
             $DUTY_OFF_TOTAL--;
      }
   }

   $query1 = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)>=to_days('$DATE1') and to_days(REGISTER_TIME)<=to_days('$DATE2') and REGISTER_TYPE='$I'";
   $cursor1= exequery(TD::conn(),$query1);

   while($ROW=mysql_fetch_array($cursor1))
   {
      $REGISTER_TIME=$ROW["REGISTER_TIME"];
      $SOME_DATE=strtok($REGISTER_TIME," ");
      $REGISTER_TIME=strtok(" ");

      $WEEK=date("w",strtotime($SOME_DATE));
      $HOLIDAY=0;
      $query="select * from ATTEND_HOLIDAY where BEGIN_DATE <='$SOME_DATE' and END_DATE>='$SOME_DATE'";
      $cursor= exequery(TD::conn(),$query);
      if($ROW=mysql_fetch_array($cursor))
         $HOLIDAY=1;
      else
      {
         if(find_id($GENERAL,$WEEK))
            $HOLIDAY=1;
      }

      if($HOLIDAY==0)
      {
         $query="select * from ATTEND_EVECTION where USER_ID='$USER_ID' and ALLOW='1' and to_days(EVECTION_DATE1)<=to_days('$SOME_DATE') and to_days(EVECTION_DATE2)>=to_days('$SOME_DATE')";
         $cursor= exequery(TD::conn(),$query);
         if($ROW=mysql_fetch_array($cursor))
            $HOLIDAY=1;
         }
         if($HOLIDAY==0)
         {
            $query="select * from ATTEND_LEAVE where USER_ID='$USER_ID' and ALLOW='1' and LEAVE_DATE1<='$SOME_DATE $DUTY_TIME' and LEAVE_DATE2>='$SOME_DATE $DUTY_TIME'";
            $cursor= exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
               $HOLIDAY=1;
         }

         if($DUTY_TYPE=="1")
         {
           if($HOLIDAY==1)
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
           if($HOLIDAY==1)
           {
             $OVER_OFF_COUNT++;
             continue;
           }
         
            $DUTY_OFF_COUNT++;
            if(compare_time($REGISTER_TIME,$DUTY_TIME)==-1)
               $EARLY_COUNT++;
         }
   }
}//for

for($J=$DATE1;$J<=$DATE2;$J=date("Y-m-d",strtotime($J)+24*3600))
{
   $COUNT=0;
   $query="";
   for($I=1;$I<=6;$I++)
   {
      $STR="DUTY_TIME".$I;
      $DUTY_TIME=$$STR;

      $STR="DUTY_TYPE".$I;
      $DUTY_TYPE=$$STR;

      if($DUTY_TIME!="")
      {
         $COUNT++;
         if($DUTY_TYPE=="1")
            $query.=" REGISTER_TYPE='$I' and to_days(REGISTER_TIME)=to_days('$J') and REGISTER_TIME<='$J $DUTY_TIME' or";
         else
            $query.=" REGISTER_TYPE='$I' and to_days(REGISTER_TIME)=to_days('$J') and REGISTER_TIME>='$J $DUTY_TIME' or";
      }
   }
   $query=substr($query,0,-3);

   $query1 = "SELECT count(*) from ATTEND_DUTY where USER_ID='$USER_ID'";
   if($query!="")
      $query1 .= " and (".$query.")";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $REG_COUNT=$ROW[0];

   if($REG_COUNT>=$COUNT)
      $PERFECT_COUNT++;
}
$OUTPUT_BODY_DUTY=format_cvs($PERFECT_COUNT).','.format_cvs($LATE_COUNT).','.format_cvs($DUTY_ON_TOTAL-$DUTY_ON_COUNT).','.format_cvs($EARLY_COUNT).','.format_cvs($DUTY_OFF_TOTAL-$DUTY_OFF_COUNT).','.format_cvs($OVER_ON_COUNT).','.format_cvs($OVER_OFF_COUNT);
$objExcel->addRow($OUTPUT_BODY_DUTY);
$MEMO=$USER_NAME._("从").format_date($DATE1)._("至").format_date($DATE2).$MSG;
$MEMO=format_cvs($MEMO);
$objExcel->addRow($MEMO);
$objExcel->Save();
?>