<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_calendar.php");
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
  if($AFF_ID=="")
  {
     $query="select * from CALENDAR where CAL_ID='$CAL_ID'  ";
     $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
     if($ROW=mysql_fetch_array($cursor))
     {
       $CAL_TIME=$ROW["CAL_TIME"];
       $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
       $CONTENT=$ROW["CONTENT"];
       $ADD_TIME=$ROW["ADD_TIME"];
       $MANAGER_ID=$ROW["MANAGER_ID"];
       if($ADD_TIME!="0000-00-00 00:00:00")
      {
           $querys="select CAL_ID from CALENDAR where ADD_TIME='$ADD_TIME' and MANAGER_ID='$MANAGER_ID'";
           $cursors=exequery(TD::conn(),$querys,$QUERY_MASTER);
           $CAL_ID_STR="";
           while($ROWS=mysql_fetch_array($cursors))
           {
      	      $CAL_IDS=$ROWS["CAL_ID"];
              $CAL_ID_STR.=$CAL_IDS.",";
           }
          if(substr($CAL_ID_STR,-1)==",")
             $CAL_ID_STR=substr($CAL_ID_STR,0,-1);
      }
      else
      {
         $CAL_ID_STR=$CAL_ID;
      }
       $MSG = sprintf(_("OA日程安排:%s为您安排新的工作，内容：%s"), $_SESSION["LOGIN_USER_NAME"],$CONTENT);
       $SMS_CONTENT=_("请查看日程安排！")."\n"._("内容：").csubstr($CONTENT,0,100);
       $SMS_CONTENT2=$MSG;
     }  
     $query="delete from CALENDAR where CAL_ID in ($CAL_ID_STR) ";
     exequery(TD::conn(),$query);     
     //同步删除任务中心中日程信息
     delete_taskcenter('CALENDAR',$CAL_ID_STR,1);
     delete_remind_sms(5, $_SESSION["LOGIN_USER_ID"], $SMS_CONTENT, $CAL_TIME);    
     DelSMS2byContent($_SESSION["LOGIN_USER_ID"], $SMS_CONTENT2);
     $REFER_URL=$_COOKIE["cal_view"]=="" ? "index.php" : $_COOKIE["cal_view"].".php";
     header("location: $REFER_URL?OVER_STATUS=$OVER_STATUS&YEAR=$YEAR&MONTH=$MONTH&DAY=$DAY&DEPT_ID=$DEPT_ID&IS_MAIN=1");
  }
  else
  {
     $REFER_URL=$_COOKIE["cal_view"]=="" ? "index.php" : $_COOKIE["cal_view"].".php";
     $query="delete from AFFAIR where AFF_ID ='$AFF_ID'";
     exequery(TD::conn(),$query);
     header("location: $REFER_URL?OVER_STATUS=$OVER_STATUS&YEAR=$YEAR&MONTH=$MONTH&DAY=$DAY&DEPT_ID=$DEPT_ID&IS_MAIN=1");
     /*
     if($FROM!=1)
        header("location: $REFER_URL?OVER_STATUS=$OVER_STATUS&YEAR=$YEAR&MONTH=$MONTH&DAY=$DAY&DEPT_ID=$DEPT_ID");
     else
       {
       	?>
       	<script Language="JavaScript">
         window.opener.location.reload();
           window.close();
        </script>
       	
      <? 	
       } 
       */
  }
?>
</body>
</html>
