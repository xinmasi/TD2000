<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("工作安排查询");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
  <table class="TableList" align="center">
    <tr align="center" class="TableHeader">
      <td width="80"><?=_("姓名")?></td>
<?
//---------------- 表头 ---------------
for($DAY=$BEGIN_DAY;$DAY<=$END_DAY;$DAY++)
{
  $WEEK=date("w",mktime(0,0,0,$MONTH,$DAY,$YEAR));

  switch($WEEK)
  {
    case 0:$WEEK_DESC=_("星期日");
           break;
    case 1:$WEEK_DESC=_("星期一");
           break;
    case 2:$WEEK_DESC=_("星期二");
           break;
    case 3:$WEEK_DESC=_("星期三");
           break;
    case 4:$WEEK_DESC=_("星期四");
           break;
    case 5:$WEEK_DESC=_("星期五");
           break;
    case 6:$WEEK_DESC=_("星期六");
           break;
  }

?>

      <td <?if($WEEK==0)echo "bgcolor=#FFCCFF";else if($WEEK==6)echo "bgcolor=#CCFFCC";?> >
          <?=$YEAR?>-<?=$MONTH?>-<?=$DAY?><br>
          (<?=$WEEK_DESC?>)
      </td>

<?
}
?>
    </tr>

<?
$CUR_YEAR = date('Y');
$CUR_MON = date('m');
$CUR_DAY = date('d');

//============================ 逐日显示工作安排 =======================================

$query = "SELECT * from USER where USER_ID='$USER_ID' and (NOT_LOGIN='0' or NOT_MOBILE_LOGIN='0')";
$cursor1= exequery(TD::conn(),$query);

if($ROW=mysql_fetch_array($cursor1))
{
  $USER_ID=$ROW["USER_ID"];
  $USER_NAME=$ROW["USER_NAME"];
?>

<tr class="TableData">
<td width="80" align="center"><?=$USER_NAME?></td>

<?
for($DAY=$BEGIN_DAY;$DAY<=$END_DAY;$DAY++)
{
  if($DAY == $CUR_DAY && $YEAR == $CUR_YEAR && $MONTH == $CUR_MON)
     $DAY_COLOR = "TableContent";
  else
     $DAY_COLOR = "TableData";
?>
     <td class="<?=$DAY_COLOR?>">
<?
 $query = "SELECT * from CALENDAR where USER_ID='$USER_ID' and CAL_TYPE!='2' and to_days(from_unixtime(CAL_TIME))<= to_days('$YEAR-$MONTH-$DAY') and to_days(from_unixtime(END_TIME)) >= to_days('$YEAR-$MONTH-$DAY') order by CAL_TIME";
 $cursor= exequery(TD::conn(),$query, $connstatus);
 $COUNT1=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $COUNT1++;
    $CAL_ID=$ROW["CAL_ID"];
    
    $CAL_TIME=date("Y-m-d H:i:s",$ROW["CAL_TIME"]);
    $END_TIME=date("Y-m-d H:i:s",$ROW["END_TIME"]);
    if(strtotime($END_TIME) - strtotime($CAL_TIME) > 60*60*24)
       $IS_CYCLE = _("(周期性事务)");
    else 
       $IS_CYCLE = "";
    $CONTENT=$ROW["CONTENT"];
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $OVER_STATUS=$ROW["OVER_STATUS"];
    $MANAGER_NAME="";
    
    if($MANAGER_ID!="")
    {
       $query = "SELECT * from USER where USER_ID='$MANAGER_ID'";
       $cursor2= exequery(TD::conn(),$query);
       if($ROW1=mysql_fetch_array($cursor2))
          $MANAGER_NAME=_("安排人：").$ROW1["USER_NAME"]."<br>";
    }

    if($OVER_STATUS=="" || $OVER_STATUS=="1")
       $OVER_STATUS1="<font color='#00AA00'><b>"._("已结束")."</b></font>";
    elseif($OVER_STATUS=="0")
       $OVER_STATUS1="";
       
    $CONTENT=str_replace("<","&lt",$CONTENT);
    $CONTENT=str_replace(">","&gt",$CONTENT);
    $CONTENT=stripslashes($CONTENT);

    $CAL_DAY=strtok($CAL_TIME,"-");
    $CAL_DAY=strtok("-");
    $CAL_DAY=strtok(" ");

    if(substr($CAL_DAY,0,1)=="0")
       $CAL_DAY=substr($CAL_DAY,-1);

    $CAL_TIME=strtok($CAL_TIME," ");
    $CAL_TIME=strtok(" ");
    $CAL_TIME=substr($CAL_TIME,0,5);

    $END_TIME=strtok($END_TIME," ");
    $END_TIME=strtok(" ");
    $END_TIME=substr($END_TIME,0,5);

    if($COUNT1==1)
       echo "<font color='red'>"._("日程:")."<br></font>";
?>
     <?=$CAL_TIME?>-<?=$END_TIME?><?=$IS_CYCLE?><br>
<?
   if($MANAGER_ID==$_SESSION["LOGIN_USER_ID"])
   {
?>
     <a href="javascript:;" onClick="window.open('new.php?CAL_ID=<?=$CAL_ID?>','oa_sub_window','height=300,width=500,status=0,toolbar=no,menubar=no,location=no,left=300,top=200,scrollbars=yes,resizable=yes');"><?=$CONTENT?></a>  <?=$OVER_STATUS1?>
     <a href="delete.php?CAL_ID=<?=$CAL_ID?>&YEAR=<?=$YEAR?>&MONTH=<?=$MONTH?>&BEGIN_DAY=<?=$BEGIN_DAY?>&END_DAY=<?=$END_DAY?>&USER_ID=<?=$USER_ID?>"><?=_("删除")?></a>
<?
   }
   else
   {
?>
     <?=$CONTENT?><?=$OVER_STATUS1?>
<?
   }
?>
     &nbsp;<br><?=$MANAGER_NAME?>
<?
 }//日程安排
 $COUNT2=0;
 $query = "SELECT * from TASK where USER_ID='$USER_ID' and BEGIN_DATE<='$YEAR-$MONTH-$DAY' and (END_DATE>='$YEAR-$MONTH-$DAY' or END_DATE='0000-00-00') order by TASK_ID desc ";
 $cursor= exequery(TD::conn(),$query, $connstatus);

 while($ROW=mysql_fetch_array($cursor))
 {
    $COUNT2++;
    $TASK_ID=$ROW["TASK_ID"];
    $TASK_NO=$ROW["TASK_NO"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];

    if($BEGIN_DATE=="0000-00-00")
       $BEGIN_DATE="";

    if($END_DATE=="0000-00-00")
       $END_DATE="";
     
    if($BEGIN_DATE==$YEAR."-".$MONTH."-".$DAY && $END_DATE==$YEAR."-".$MONTH."-".$DAY)
       $DATE_NAME=$YEAR."-".$MONTH."-".$DAY;
    else
    	 $DATE_NAME=_("跨天任务:").$BEGIN_DATE."-".$END_DATE;
    $TASK_TYPE=$ROW["TASK_TYPE"];
    $TASK_STATUS=$ROW["TASK_STATUS"];
    $COLOR=$ROW["COLOR"];
    $IMPORTANT=$ROW["IMPORTANT"];
    $RATE=intval($ROW["RATE"]);
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $SUBJECT=$ROW["SUBJECT"];
    $SUBJECT=td_htmlspecialchars($SUBJECT);

    $CONTENT=$ROW["CONTENT"];
    $CONTENT=td_htmlspecialchars($CONTENT);
    
    switch($TASK_STATUS)
    {
       case "1": $STATUS_DESC=_("未开始");break;
       case "2": $STATUS_DESC=_("进行中");break;
       case "3": $STATUS_DESC=_("已完成");break;
       case "4": $STATUS_DESC=_("等待其他人");break;
       case "5": $STATUS_DESC=_("已推迟");break;
    }
    
    if($COUNT2==1)
       echo "<font color='red'>"._("任务:")."<br></font>";

?>
     <?=$DATE_NAME?><br>
<?
   if($MANAGER_ID==$_SESSION["LOGIN_USER_ID"])
   {
?>
     <a href="javascript:;" onClick="window.open('new.php?CAL_ID=<?=$CAL_ID?>','oa_sub_window','height=300,width=500,status=0,toolbar=no,menubar=no,location=no,left=300,top=200,scrollbars=yes,resizable=yes');"><?=$CONTENT?></a>  <?=$OVER_STATUS1?>
     <a href="delete.php?CAL_ID=<?=$CAL_ID?>&YEAR=<?=$YEAR?>&MONTH=<?=$MONTH?>&BEGIN_DAY=<?=$BEGIN_DAY?>&END_DAY=<?=$END_DAY?>&USER_ID=<?=$USER_ID?>"><?=_("删除")?></a>
<?
   }
   else
   {
?>
     <?=csubstr(strip_tags($SUBJECT),0,100);?> <? if(strlen($SUBJECT)>100)echo "...";?>&nbsp;<font color="green"><?=$RATE?>%</font>
<?
   }
?>
     &nbsp;<br><?=$MANAGER_NAME?>
<?
 }//任务
?>
    </td>
<?
}//for 日循环
?>
   </tr>
<?
}//while 用户循环
?>
</table>

</body>
</html>

