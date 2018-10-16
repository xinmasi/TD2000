<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("借书登记");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
if($BORROW_DATE!="")
{
   $TIME_OK=is_date($BORROW_DATE);

   if(!$TIME_OK)
   { 
   	  Message(_("错误"),_("借书日期格式不对，应形如 1999-1-2"));
?>
<br>
<div align="center">
   <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='new.php?TO_ID=<?=$TO_ID?>&BOOK_NO=<?=$BOOK_NO?>&BORROW_DATE=<?=$BORROW_DATE?>&RETURN_DATE=<?=$RETURN_DATE?>&BORROW_REMARK=<?=$BORROW_REMARK?>'">
</div>

<?
     exit;
  }
}


if($RETURN_DATE!="")
{
   $TIME_OK=is_date($RETURN_DATE);

   if(!$TIME_OK)
   { 
   	  Message(_("错误"),_("还书日期格式不对，应形如 1999-1-2"));
?>
<br>
<div align="center">
   <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='new.php?TO_ID=<?=$TO_ID?>&BOOK_NO=<?=$BOOK_NO?>&BORROW_DATE=<?=$BORROW_DATE?>&RETURN_DATE=<?=$RETURN_DATE?>&BORROW_REMARK=<?=$BORROW_REMARK?>'">
</div>
<?
     exit;
  }
}

if($BORROW_DATE!="" && $RETURN_DATE!="" && compare_date($RETURN_DATE,$BORROW_DATE)<=0)
{
   Message(_("错误"),_("归还日期不能小于借书日期！"));
   Button_Back();
   exit;
}

//是否有要借的书
$query = "SELECT * from BOOK_INFO where BOOK_NO='$BOOK_NO'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $LEND=$ROW["LEND"];
   $AMT=$ROW["AMT"];
   $DEPT=$ROW["DEPT"];
   $OPEN=$ROW["OPEN"];
}
else
{
   Message(_("提示"),_("所借阅的图书不存在"));
?>
<br>
<div align="center">
   <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='new.php?TO_ID=<?=$TO_ID?>&BOOK_NO=<?=$BOOK_NO?>&BORROW_DATE=<?=$BORROW_DATE?>&RETURN_DATE=<?=$RETURN_DATE?>&BORROW_REMARK=<?=$BORROW_REMARK?>'">
</div>
<?	 
   exit;
}

//是否有权借
$query = "SELECT * from USER where USER_ID='$TO_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$DEPT_ID   = $ROW["DEPT_ID"];
	$USER_PRIV = $ROW["USER_PRIV"];
	
}
   

$OPEN_ARR=explode(";", $OPEN);
//if($OPEN=="1")
   //$OPEN="ALL_DEPT";
   
if (!find_id($OPEN_ARR[0], $DEPT_ID) && !find_id($OPEN_ARR[1], $TO_ID) && !find_id($OPEN_ARR[2], $USER_PRIV) && $OPEN_ARR[0]!="ALL_DEPT")
//if(($OPEN=="0" && $DEPT!=$DEPT_ID) or ($OPEN!="ALL_DEPT" && !strpos($OPEN,",")) or (!find_id($OPEN,$DEPT_ID) && $OPEN!="ALL_DEPT"))
{
   Message(_("提示"),_("借书人无权借阅本书"));
?>
<br>
<div align="center">
   <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='new.php?TO_ID=<?=$TO_ID?>&BOOK_NO=<?=$BOOK_NO?>&BORROW_DATE=<?=$BORROW_DATE?>&RETURN_DATE=<?=$RETURN_DATE?>&BORROW_REMARK=<?=$BORROW_REMARK?>'">
</div>
<?	 
   exit;
}

$query = "SELECT count(*) from BOOK_MANAGE where BOOK_NO='$BOOK_NO' and ((BOOK_STATUS='0' and STATUS='1') or (BOOK_STATUS='1' and STATUS='0'))";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $LEND_COUNT=$ROW[0];
   
//是否该书已经借出
if($LEND==1 && $LEND_COUNT>=$AMT)
{
   Message(_("提示"),_("该图书已经借出"));
?>
<br>
<div align="center">
   <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='new.php?TO_ID=<?=$TO_ID?>&BOOK_NO=<?=$BOOK_NO?>&BORROW_DATE=<?=$BORROW_DATE?>&RETURN_DATE=<?=$RETURN_DATE?>&BORROW_REMARK=<?=$BORROW_REMARK?>'">
</div>
<?	 
   exit;
}

$CUR_DATE=date("Y-m-d",time());
if($BORROW_DATE=="")
   $BORROW_DATE=$CUR_DATE;
if($RETURN_DATE=="")
   $RETURN_DATE = date("Y-m-d",dateadd("d",30,$BORROW_DATE));
   
$REMIND_DATE = date("Y-m-d",dateadd("d",-2,$RETURN_DATE))." 08:30:00";

$query="INSERT into BOOK_MANAGE(BUSER_ID,BOOK_NO,BORROW_DATE,BORROW_REMARK,RUSER_ID,RETURN_DATE,REG_FLAG,BOOK_STATUS,STATUS) values ('$TO_ID','$BOOK_NO','$BORROW_DATE','$BORROW_REMARK','".$_SESSION["LOGIN_USER_ID"]."','$RETURN_DATE','1','0','1')";
exequery(TD::conn(),$query);
//$ROW_ID=mysql_insert_id();

//改变图书状态
if($LEND_COUNT+1 < $AMT)
   $query="update BOOK_INFO set LEND='0' where BOOK_NO='$BOOK_NO'";
else
   $query="update BOOK_INFO set LEND='1' where BOOK_NO='$BOOK_NO'";
exequery(TD::conn(),$query);

//事务提醒
$MSG = sprintf(_("您借的图书(编号：%s)于%s到期,请按时归还。"), $BOOK_NO,$RETURN_DATE);
send_sms($REMIND_DATE,$_SESSION["LOGIN_USER_ID"],$TO_ID,73,$MSG,$REMIND_URL);

Message(_("提示"),_("保存成功"));
Button_Back();
exit;
?>
</body>
</html>

<?
/**
* 转换为unix时间戳
*/
function gettime($d)
{
   if(is_numeric($d))
      return $d;
   else
   {
      if(!is_string($d))
         return 0;
      if(strpos($d, ":") > 0)
      {
         $buf = explode(" ",$d);
         $year = explode("-",$buf[0]);
         $hour = explode(":",$buf[1]);
         if(stripos($buf[2], "pm") > 0)
            $hour[0] += 12;
            
         return mktime($hour[0],$hour[1],$hour[2],$year[1],$year[2],$year[0]);
      }
      else
      {
         $year = explode("-",$d);
         return mktime(0,0,0,$year[1],$year[2],$year[0]);
      }
   }
}

function dateadd($interval, $number, $date)
{
   $date = gettime($date);
   $date_time_array = getdate($date); 
   $hours = $date_time_array["hours"]; 
   $minutes = $date_time_array["minutes"]; 
   $seconds = $date_time_array["seconds"]; 
   $month = $date_time_array["mon"]; 
   $day = $date_time_array["mday"]; 
   $year = $date_time_array["year"]; 
   switch ($interval)
   { 
      case "yyyy": $year +=$number; break; 
      case "q": $month +=($number*3); break; 
      case "m": $month +=$number; break; 
      case "y": 
      case "d": 
      case "w": $day+=$number; break; 
      case "ww": $day+=($number*7); break; 
      case "h": $hours+=$number; break; 
      case "n": $minutes+=$number; break; 
      case "s": $seconds+=$number; break; 
   } 
   $timestamp = mktime($hours ,$minutes, $seconds,$month ,$day, $year); 
   return $timestamp;
} 
?>