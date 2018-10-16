<?
include_once("inc/auth.inc.php");
include_once("inc/utility.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;
function get_work_days($date1,$date2,$duty_type)
{
   $date1=date("Y-m-d",strtotime($date1));
   $date2=date("Y-m-d",strtotime($date2));
   
   $j=0;
   
   $m = date("m",strtotime($date1));
   $d = date("d",strtotime($date1));
   $Y = date("Y",strtotime($date1));
   
   $query = "SELECT GENERAL FROM ATTEND_CONFIG WHERE DUTY_TYPE = '$duty_type'";
   $cursor = exequery(TD::conn(),$query);
   if($ROW = mysql_fetch_array($cursor))
      $GENERAL = $ROW['GENERAL'];
        
   $work_days = 0; 
   for($i = strtotime($date1); $i <= strtotime($date2); $i+= 86400) 
   {
   	
      $y=mktime(0,0,0,$m,$d,$Y);
      $date = $y+$j*24*3600;
      $week = date("w",$date);   
      //echo $week.'<br/>';
      if(find_id($GENERAL,$week))
      {
         $j++;
         continue;           
      }
      
      $t=date("Y-m-d",$date);
      
      $query2 = "SELECT * FROM ATTEND_HOLIDAY";
      $cursor2 = exequery(TD::conn(),$query2);
      $flag = 0;
      while($ROW2 = mysql_fetch_array($cursor2))
      {
         $BEGIN_DATE = $ROW2['BEGIN_DATE'];
         $END_DATE = $ROW2['END_DATE'];
         if(compare_date($t,$BEGIN_DATE)!=-1 && compare_date($END_DATE,$t)!=-1)
            $flag=1;
      }
      
      if($flag==1)
      {
         $j++;         
         continue;
      }   
      
      //echo $t.'<br/>';
      $work_days++;
      $j++;
   }  
   return $work_days; 
}

//echo get_work_days("2011-05-18 15:53:31","2011-05-26 15:53:28","1");

$HTML_PAGE_TITLE = _("请假记录");
include_once("inc/header.inc.php");
?>


<style>
.AutoNewline
{
  word-break: break-all;/*必须*/
}
</style>


<body class="bodycolor">

<?
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

$CUR_DATE=date("Y-m-d",time());

$query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $DAY_TOTAL=$ROW[0]+1;
?>
<!------------------------------------- 请假记录 ------------------------------->


<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("请假记录")?></span>
    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value=<?=_("导出")?> class="BigButton" onClick="location='export_leave.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE=<?=$DUTY_TYPE?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'" title=<?=_("导出请假记录")?> name="button">
    </td>
  </tr>
</table>

<?
$is_manager = 0;
if($DEPARTMENT1!="ALL_DEPT")
{
	$DET_LIST=substr(GetChildDeptId($DEPARTMENT1),0,-1);
	$WHERE_STR=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";
	
	$sql = "SELECT DEPT_ID FROM hr_manager WHERE find_in_set('".$_SESSION['LOGIN_USER_ID']."',DEPT_HR_MANAGER) and DEPT_ID = '$DEPARTMENT1'";
	$cursor1 = exequery(TD::conn(),$sql);
	if($arr=mysql_fetch_array($cursor1))
	{
		$is_manager = 1;
	}
}

$query = "SELECT DEPT_NO,USER_NO,USER_NAME,LEAVE_TYPE2,USER.DEPT_ID,USER.USER_ID from ATTEND_LEAVE,USER,USER_EXT,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID ".$WHERE_STR." and ATTEND_LEAVE.USER_ID=USER.USER_ID and USER_EXT.USER_ID=USER.USER_ID and USER_EXT.DUTY_TYPE!='99' and ((to_days(LEAVE_DATE1)>=to_days('$DATE1') and to_days(LEAVE_DATE1)<=to_days('$DATE2')) or (to_days(LEAVE_DATE2)>=to_days('$DATE1') and to_days(LEAVE_DATE2)<=to_days('$DATE2')) or (to_days(LEAVE_DATE1)<=to_days('$DATE1') and to_days(LEAVE_DATE2)>=to_days('$DATE2'))) and allow in('1','3') group by DEPT_NO,USER_NO,USER_NAME,LEAVE_TYPE2 order by DEPT_NO,USER_NO,USER_NAME,LEAVE_TYPE2";
$cursor= exequery(TD::conn(),$query, $connstatus);
$LEAVE_COUNT=0;
$USER_COUNT=0;
$LEAVE_DAYS=0;
while($ROW=mysql_fetch_array($cursor))
{
   $USER_ID=$ROW["USER_ID"];
   $DEPT_ID=$ROW["DEPT_ID"];
   $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
   $USER_COUNT++;
   $flag = 0;
   $LEAVE_DAYS = 0;
   
   $query_extra = "SELECT * from ATTEND_LEAVE,USER,DEPARTMENT,USER_EXT where DEPARTMENT.DEPT_ID=USER.DEPT_ID ".$WHERE_STR." and ATTEND_LEAVE.USER_ID=USER.USER_ID and USER.UID=USER_EXT.UID and USER_EXT.DUTY_TYPE!='99' and ((to_days(LEAVE_DATE1)>=to_days('$DATE1') and to_days(LEAVE_DATE1)<=to_days('$DATE2')) or (to_days(LEAVE_DATE2)>=to_days('$DATE1') and to_days(LEAVE_DATE2)<=to_days('$DATE2')) or (to_days(LEAVE_DATE1)<=to_days('$DATE1') and to_days(LEAVE_DATE2)>=to_days('$DATE2'))) and allow in('1','3') and USER.DEPT_ID='$DEPT_ID' and USER.USER_ID='$USER_ID' and LEAVE_TYPE2 ='$LEAVE_TYPE2'";
   $cursor_extra= exequery(TD::conn(),$query_extra,true);
   while($ROW_EXTRA=mysql_fetch_array($cursor_extra))
   {
      $LEAVE_DATE1=$ROW_EXTRA["LEAVE_DATE1"];
      $LEAVE_DATE2=$ROW_EXTRA["LEAVE_DATE2"];
      $DUTY_TYPE=$ROW_EXTRA["DUTY_TYPE"];          
      $DAY_DIFF= get_work_days($LEAVE_DATE1,$LEAVE_DATE2,$DUTY_TYPE);
      $LEAVE_DAYS+=$DAY_DIFF;
      $LEAVE_DAYS=number_format($LEAVE_DAYS, 1, '.', ' ');       	
   }
   
   $query5 = "SELECT * from ATTEND_LEAVE,USER,DEPARTMENT,USER_EXT where DEPARTMENT.DEPT_ID=USER.DEPT_ID ".$WHERE_STR." and ATTEND_LEAVE.USER_ID=USER.USER_ID and USER.UID=USER_EXT.UID and USER_EXT.DUTY_TYPE!='99' and ((to_days(LEAVE_DATE1)>=to_days('$DATE1') and to_days(LEAVE_DATE1)<=to_days('$DATE2')) or (to_days(LEAVE_DATE2)>=to_days('$DATE1') and to_days(LEAVE_DATE2)<=to_days('$DATE2')) or (to_days(LEAVE_DATE1)<=to_days('$DATE1') and to_days(LEAVE_DATE2)>=to_days('$DATE2'))) and allow in('1','3') and USER.DEPT_ID='$DEPT_ID' and USER.USER_ID='$USER_ID' and LEAVE_TYPE2 ='$LEAVE_TYPE2'";
   $cursor5= exequery(TD::conn(),$query5,true);
   $rowspan = mysql_num_rows($cursor5);
   while($ROW2=mysql_fetch_array($cursor5))
   {   	
 	    $AGENT_NAME="";
      $LEAVE_DATE1=$ROW2["LEAVE_DATE1"];
      $LEAVE_DATE2=$ROW2["LEAVE_DATE2"];
      $DUTY_TYPE=$ROW2["DUTY_TYPE"];
      
      $DAY_DIFF= get_work_days($LEAVE_DATE1,$LEAVE_DATE2,$DUTY_TYPE);
   
      $USER_ID=$ROW2["USER_ID"];
      $DEPT_ID=$ROW2["DEPT_ID"];
      $USER_NAME=$ROW2["USER_NAME"];
      $LEAVE_ID=$ROW2["LEAVE_ID"];
      $LEAVE_DATE1=$ROW2["LEAVE_DATE1"];
      $LEAVE_DATE2=$ROW2["LEAVE_DATE2"];
      $REGISTER_IP=$ROW2["REGISTER_IP"];
      $ANNUAL_LEAVE=$ROW2["ANNUAL_LEAVE"];
      $RECORD_TIME=$ROW2["RECORD_TIME"];
      $LEAVE_TYPE=$ROW2["LEAVE_TYPE"];
      $LEAVE_TYPE2=$ROW2["LEAVE_TYPE2"];
      $HANDLE_TIME=$ROW2["HANDLE_TIME"]=="0000-00-00 00:00:00" ? "" : $ROW2["HANDLE_TIME"];
      $AGENT=$ROW2["AGENT"];
      
      $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");   
      $LEADER_ID=$ROW2["LEADER_ID"];
      $ALLOW=$ROW2["ALLOW"];
      $STATUS=$ROW2["STATUS"];
      
      $LEADER_NAME="";
      $query3 = "SELECT * from USER where USER_ID='$LEADER_ID'";
      $cursor3= exequery(TD::conn(),$query3);
      if($ROW3=mysql_fetch_array($cursor3))
         $LEADER_NAME=$ROW3["USER_NAME"];
       
      if($AGENT != $USER_ID && $AGENT != "")
      {
         $query_name = "SELECT * from USER where USER_ID='$AGENT'";
         $cursor_name= exequery(TD::conn(),$query_name);
         if($ROW_NAME=mysql_fetch_array($cursor_name))
            $AGENT_NAME=$ROW_NAME["USER_NAME"];
      }
      
      if(!is_dept_priv($DEPT_ID) && $is_manager !=1)
         continue;
      
      $LEAVE_COUNT++;
      
  		if($ALLOW=="0" && $STATUS=="1")
       	$ALLOW_DESC=_("待审批");
    	else if($ALLOW=="1" && $STATUS=="1")
       	$ALLOW_DESC="<font color=green>"._("已批准")."</font>";
    	else if($ALLOW=="2" && $STATUS=="1")
       	$ALLOW_DESC="<font color=red>"._("未批准")."</font>";
    	else if($ALLOW=="3" && $STATUS=="1")
       	$ALLOW_DESC=_("申请销假");
    	else if($ALLOW=="3" && $STATUS=="2")
       	$ALLOW_DESC=_("已销假");
      $DEPT_ID=intval($DEPT_ID);
      $query4="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
      $cursor4= exequery(TD::conn(),$query4);
      if($ROW4=mysql_fetch_array($cursor4))
         $USER_DEPT_NAME=$ROW4["DEPT_NAME"];
      
      if($LEAVE_COUNT==1)
      {
?>
         <table class="TableList"  width="95%">
<?
      }
?>
      <tr class="TableData">
<?      	
      if($flag==0)
      {
?>
        <td nowrap rowspan=<?=$rowspan?> align="center"><?=$USER_DEPT_NAME?></td>
        <td nowrap rowspan=<?=$rowspan?> align="center"><?=$USER_NAME?></td>
        <td nowrap rowspan=<?=$rowspan?> align="center"><?=$LEAVE_TYPE2?></td>   
        <td nowrap rowspan=<?=$rowspan?> align="center"><?=$LEAVE_DAYS?></td>
<?
         $flag++;
      }
?>   
        <td align="center" class="AutoNewline"><?=$LEAVE_TYPE?></td>
        <td nowrap align="center"><?=$DAY_DIFF?></td> 
        <td nowrap align="center"><?=$RECORD_TIME?></td>
        <td nowrap align="center"><?=$ANNUAL_LEAVE?><?=_("天")?></td>
        <td nowrap align="center"><?=$REGISTER_IP?></td>
        <td nowrap align="center"><?=$LEAVE_DATE1?></td>
        <td nowrap align="center"><?=$LEAVE_DATE2?></td>
        <td nowrap align="center"><?=$AGENT_NAME?></td>
        <td nowrap align="center"><?=$LEADER_NAME?></td>
        <td nowrap align="center"><?=$HANDLE_TIME?></td>
        <td nowrap align="center"><?=$ALLOW_DESC?></td>
        <td nowrap align="center">
<?
      if($_SESSION["LOGIN_USER_PRIV"]==1)
      {
?>
         <a href="delete_leave.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE=<?=$DUTY_TYPE?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&LEAVE_ID=<?=$LEAVE_ID?>"><?=_("删除")?></a>
<?
      }
?>
         </td>
      </tr>
<?   	
   }
}
if($LEAVE_COUNT>0)
{
?>
     <thead class="TableHeader">
      <td nowrap align="center"><?=_("部门")?></td>
      <td nowrap align="center"><?=_("姓名")?></td>
      <td nowrap align="center"><?=_("请假类型")?></td> 
      <td nowrap align="center"><?=_("天数合计")?></td>
      <td nowrap align="center" width="300"><?=_("请假原因")?></td> 
      <td nowrap align="center"><?=_("请假天数")?></td>    
      <td nowrap align="center"><?=_("申请时间")?></td>
      <td nowrap align="center"><?=_("占年休假")?></td>
      <td nowrap align="center"><?=_("登记IP")?></td>
      <td nowrap align="center"><?=_("开始日期")?></td>
      <td nowrap align="center"><?=_("结束日期")?></td>
      <td nowrap align="center"><?=_("代请假人员")?></td>
      <td nowrap align="center"><?=_("审批人员")?></td>
      <td nowrap align="center"><?=_("审批时间")?></td>
      <td nowrap align="center"><?=_("状态")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
     </thead>
   </table>
<?
}
else
   message("",_("无请假记录"));
?>
<br>
</body>
</html>