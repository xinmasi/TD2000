<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("加班记录");
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
    { Message(_("错误"),_("起始日期格式不对，应形如 1999-1-2"));
      Button_Back();
      exit;
    }
  }

  if($DATE2!="")
  {
    $TIME_OK=is_date($DATE2);

    if(!$TIME_OK)
    { Message(_("错误"),_("截止日期格式不对，应形如 1999-1-2"));
      Button_Back();
      exit;
    }
  }

  if(compare_date($DATE1,$DATE2)==1)
  { Message(_("错误"),_("查询的起始日期不能晚于截止日期"));
    Button_Back();
    exit;
  }

 $CUR_DATE=date("Y-m-d",time());

 $query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $DAY_TOTAL=$ROW[0]+1;
 if($DEPARTMENT1!="ALL_DEPT")
	  $DET_LIST=substr(GetChildDeptId($DEPARTMENT1),0,-1);

?>

<!------------------------------------- 加班记录 ------------------------------->


<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("加班记录")?></span>
    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?=_("导出")?>" class="BigButton" onClick="location='export_overtime.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE=<?=$DUTY_TYPE?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&OVERTIME_ID=<?=$OVERTIME_ID?>'" title="<?=_("导出加班记录")?>" name="button">
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

 $query = "SELECT * from ATTENDANCE_OVERTIME,USER,USER_EXT,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID ".$WHERE_STR." and ATTENDANCE_OVERTIME.USER_ID=USER.USER_ID and USER_EXT.USER_ID=USER.USER_ID and USER_EXT.DUTY_TYPE!='99' and ((to_days(START_TIME)>=to_days('$DATE1') and to_days(START_TIME)<=to_days('$DATE2')) or (to_days(END_TIME)>=to_days('$DATE1') and to_days(END_TIME)<=to_days('$DATE2')) or (to_days(START_TIME)<=to_days('$DATE1') and to_days(END_TIME)>=to_days('$DATE2')))and allow in('1','3') order by DEPT_NO,USER_NO,USER_NAME";
 $cursor= exequery(TD::conn(),$query, $connstatus);
 $OVERTIME_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $AGENT_NAME="";
 	$DEPT_ID=$ROW["DEPT_ID"];
    $OVERTIME_ID=$ROW["OVERTIME_ID"];
    $USER_NAME=$ROW["USER_NAME"];    
    $APPROVE_ID=$ROW["APPROVE_ID"];
    $RECORD_TIME=$ROW["RECORD_TIME"];
    $START_TIME=$ROW["START_TIME"];
    $END_TIME=$ROW["END_TIME"];
    $OVERTIME_HOURS=$ROW["OVERTIME_HOURS"];
    $OVERTIME_CONTENT=$ROW["OVERTIME_CONTENT"];
    $CONFIRM_TIME=$ROW["CONFIRM_TIME"];
    $CONFIRM_VIEW=$ROW["CONFIRM_VIEW"];
    $ALLOW=$ROW["ALLOW"];
    $STATUS=$ROW["STATUS"];
    $REGISTER_IP=$ROW["REGISTER_IP"];
    $REASON=$ROW["REASON"];
   $HANDLE_TIME=$ROW["HANDLE_TIME"]=="0000-00-00 00:00:00" ? "" : $ROW["HANDLE_TIME"];
   $AGENT=$ROW["AGENT"];

    $APPROVE_NAME="";
    $query8 = "SELECT * from USER where USER_ID= '$APPROVE_ID' ";
    $cursor8= exequery(TD::conn(),$query8);
    if($ROW8=mysql_fetch_array($cursor8))
       $APPROVE_NAME=$ROW8["USER_NAME"];
       
    if($AGENT != $USER_ID && $AGENT != "")
    {
       $query2 = "SELECT * from USER where USER_ID='$AGENT'";
       $cursor2= exequery(TD::conn(),$query2);
       if($ROW2=mysql_fetch_array($cursor2))
          $AGENT_NAME=$ROW2["USER_NAME"];
    }

   if(!is_dept_priv($DEPT_ID) && $is_manager != 1)
      continue;

   $OVERTIME_COUNT++;

  		if($ALLOW=="0" && $STATUS=="0")
      	 $ALLOW_DESC=_("待审批");
    	else if($ALLOW=="1" && $STATUS=="0")
       	$ALLOW_DESC="<font color=green>"._("已批准")."</font>";
    	else if($ALLOW=="2" && $STATUS=="0")
       	$ALLOW_DESC= "<font color=\"red\">"._("未批准")."</font>";   
    	else if($ALLOW=="3" && $STATUS=="0")
       	$ALLOW_DESC=_("申请确认");
    	else if($ALLOW=="3" && $STATUS=="1")
       	$ALLOW_DESC=_("已确认");	
   $DEPT_ID=intval($DEPT_ID);
   $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
      $USER_DEPT_NAME=$ROW["DEPT_NAME"];

    if($OVERTIME_COUNT==1)
    {
?>

    <table class="TableList" width="95%">

<?
    }

?>
    <tr class="TableData">
      <td nowrap align="center"><?=$USER_DEPT_NAME?></td>
      <td nowrap align="center"><?=$USER_NAME?></td>
      <td nowrap align="center"><?=$RECORD_TIME?></td>      
      <td class="AutoNewline" width="400" align="center">
 <?
      echo $OVERTIME_CONTENT;
      if($CONFIRM_VIEW!="")
      {
         echo "<br>";
         echo _("<font color=blue>确认意见：$CONFIRM_VIEW</font>");
      }
 ?>
      </td>
      <td nowrap align="center"><?=$REGISTER_IP?></td>
      <td nowrap align="center"><?=$START_TIME?></td>
      <td nowrap align="center"><?=$END_TIME?></td>
      <td nowrap align="center"><?=$OVERTIME_HOURS?></td>
      <td nowrap align="center"><?=$AGENT_NAME?></td>
      <td nowrap align="center"><?=$APPROVE_NAME?></td>
      <td nowrap align="center"><?=$HANDLE_TIME?></td>
      <td nowrap align="center"><?=$ALLOW_DESC?>	</td>
      <td nowrap align="center">
<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
      	<a href="delete_overtime.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE=<?=$DUTY_TYPE?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&OVERTIME_ID=<?=$OVERTIME_ID?>"><?=_("删除")?></a>
<?
}
?>
      </td>
    </tr>
<?
 }

 if($OVERTIME_COUNT>0)
 {
?>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("部门")?></td>
      <td nowrap align="center"><?=_("姓名")?></td>
      <td nowrap align="center"><?=_("申请时间")?></td>
      <td nowrap align="center"><?=_("加班内容")?></td>
      <td nowrap align="center"><?=_("登记")?>IP </td>
      <td nowrap align="center"><?=_("开始日期")?></td>
      <td nowrap align="center"><?=_("结束时间")?></td>
      <td nowrap align="center"><?=_("时长")?></td>
      <td nowrap align="center"><?=_("代加班人员")?></td>
      <td nowrap align="center"><?=_("审批人员")?></td>
      <td nowrap align="center"><?=_("审批时间")?></td>
      <td nowrap align="center"><?=_("状态")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </thead>
    </table>
<?
 }
 else
    message("",_("无加班记录"));
?>


</body>
</html>