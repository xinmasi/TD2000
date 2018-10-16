<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("考勤情况查询");
include_once("inc/header.inc.php");
?>


<script>
function out_edit(OUT_ID)
{
 URL="out_edit.php?OUT_ID="+OUT_ID;
 myleft=(screen.availWidth-780)/2;
 mytop=100;
 mywidth=650;
 myheight=400;
 window.open(URL,"out_edit","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
function overtime_edit(OVERTIME_ID)
{
 URL="overtime_edit.php?OVERTIME_ID="+OVERTIME_ID;
 myleft=(screen.availWidth-780)/2;
 mytop=100;
 mywidth=650;
 myheight=400;
 window.open(URL,"overtime_edit","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
function leave_edit(LEAVE_ID)
{
 URL="leave_edit.php?LEAVE_ID="+LEAVE_ID;
 myleft=(screen.availWidth-780)/2;
 mytop=100;
 mywidth=650;
 myheight=400;
 window.open(URL,"leave_edit","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
function evection_edit(EVECTION_ID)
{
 URL="evection_edit.php?EVECTION_ID="+EVECTION_ID;
 myleft=(screen.availWidth-780)/2;
 mytop=100;
 mywidth=650;
 myheight=400;
 window.open(URL,"evection_edit","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}


</script>


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
    
$MSG = sprintf(_("共 %d 天"), $DAY_TOTAL);
?>

<!------------------------------------- 上下班 ------------------------------->
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("上下班统计")?>
    (<?=$USER_NAME?> <?=_("从")?> <?=format_date($DATE1)?> <?=_("至")?> <?=format_date($DATE2)?> <?=$MSG?>)
    </span><br>
    </td>
  </tr>
</table>
<?
$query5 = "select count(*) from ATTEND_DUTY_SHIFT where USER_ID='$USER_ID' and to_days(REGISTER_TIME)>=to_days('$DATE1') and to_days(REGISTER_TIME)<=to_days('$DATE2')";
$cursor5 = exequery(TD::conn(),$query5);
$DJCS=mysql_fetch_row($cursor5);
?>

<table class="TableList"  width="100%">
  <tr class="TableHeader">
    <td nowrap align="center"><?=_("登记次数")?></td>
  </tr>
  <tr class="TableData">
    <td nowrap align="center"><?=$DJCS[0]?></td>
  </tr>
  <tr class="TableControl">
    <td align="center" colspan=7>
    	<input type="button"  value="<?=_("查看上下班登记详情")?>" class="SmallButton" onClick="location='user_shift.php?USER_ID=<?=$USER_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'">
    </td>
  </tr>
</table>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<!------------------------------------- 外出记录 ------------------------------->

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("外出记录")?></span><br>
    </td>
  </tr>
</table>

<?
 $query = "SELECT * from ATTEND_OUT where USER_ID='$USER_ID' and to_days(SUBMIT_TIME)>=to_days('$DATE1') and to_days(SUBMIT_TIME)<=to_days('$DATE2') order by SUBMIT_TIME";
 $cursor= exequery(TD::conn(),$query);
 $OUT_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $OUT_COUNT++;
   
   $OUT_ID=$ROW["OUT_ID"];   
   $OUT_TYPE=$ROW["OUT_TYPE"];
   $SUBMIT_TIME=$ROW["SUBMIT_TIME"];
   $CREATE_DATE=$ROW["CREATE_DATE"];   
   $SUBMIT_DATE=substr($SUBMIT_TIME,0,10);
   $OUT_TIME1=$ROW["OUT_TIME1"];
   $OUT_TIME2=$ROW["OUT_TIME2"];
   $ALLOW=$ROW["ALLOW"];
   $STATUS=$ROW["STATUS"];
   $REGISTER_IP=$ROW["REGISTER_IP"];
   $LEADER_ID=$ROW["LEADER_ID"];

    $LEADER_NAME="";
    $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
       $LEADER_NAME=$ROW["USER_NAME"];
      
		if($ALLOW=="0" && $STATUS=="0")
    	$STATUS_DESC=_("待审批");
  	else if($ALLOW=="1" && $STATUS=="0")
    	$STATUS_DESC="<font color=green>"._("已批准")."</font>";
  	else if($ALLOW=="2" && $STATUS=="0")
    	$STATUS_DESC="<font color=red>"._("未批准")."</font>";
  	else if($ALLOW=="1" && $STATUS=="1")
    	$STATUS_DESC=_("已归来"); 
      	
    if($OUT_COUNT==1)
    {
?>

    <table class="TableList"  width="100%">

<?
    }
?>
    <tr class="TableData">
    	<td nowrap align="center"><?=$CREATE_DATE?></td>
      <td width="400" align="center"><?=$OUT_TYPE?></td>
      <td nowrap align="center"><?=$REGISTER_IP?></td>
      <td nowrap align="center"><?=$SUBMIT_DATE?></td>
      <td nowrap align="center"><?=$OUT_TIME1?></td>
      <td nowrap align="center"><?=$OUT_TIME2?></td>
      <td nowrap align="center"><?=$LEADER_NAME?></td>
      <td nowrap align="center"><?=$STATUS_DESC?></td>
      <td nowrap align="center">
<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
        <a href=javascript:out_edit('<?=$OUT_ID?>'); title="<?=_("仅OA管理员可以修改")?>"><?=_("修改")?></a>
      	<a href="delete_out.php?DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&USER_ID=<?=$USER_ID?>&SUBMIT_TIME=<?=urlencode($SUBMIT_TIME)?>"><?=_("删除")?></a>
<?
}
?>
      </td>
    </tr>
<?
 }

 if($OUT_COUNT>0)
 {
?>
    <thead class="TableHeader">
    	<td nowrap align="center"><?=_("申请时间")?></td>
      <td nowrap align="center"><?=_("外出原因")?></td>
      <td nowrap align="center"><?=_("登记")?>IP</td>
      <td nowrap align="center"><?=_("外出日期")?></td>
      <td nowrap align="center"><?=_("外出时间")?></td>
      <td nowrap align="center"><?=_("归来时间")?></td>
      <td nowrap align="center"><?=_("审批人员")?></td>
      <td nowrap align="center"><?=_("状态")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </thead>
    </table>
<?
 }
 else
    Message("",_("无外出记录"));
?>

<br>

<table width="100%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>
<!------------------------------------- 请假记录 ------------------------------->


<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("请假记录")?></span><br>
    </td>
  </tr>
</table>

<?
 $query = "SELECT * from ATTEND_LEAVE where USER_ID='$USER_ID' and ((to_days(LEAVE_DATE1)>=to_days('$DATE1') and to_days(LEAVE_DATE1)<=to_days('$DATE2')) or (to_days(LEAVE_DATE2)>=to_days('$DATE1') and to_days(LEAVE_DATE2)<=to_days('$DATE2')) or (to_days(LEAVE_DATE1)<=to_days('$DATE1') and to_days(LEAVE_DATE2)>=to_days('$DATE2'))) and allow in('1','3')";
 $cursor= exequery(TD::conn(),$query);
 $LEAVE_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $LEAVE_COUNT++;

   $LEAVE_ID=$ROW["LEAVE_ID"];
   $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
   $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
   $ANNUAL_LEAVE=$ROW["ANNUAL_LEAVE"];
   $LEAVE_TYPE=$ROW["LEAVE_TYPE"];
   $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
   $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");
   $LEAVE_TYPE=str_replace("<","&lt",$LEAVE_TYPE);
   $LEAVE_TYPE=str_replace(">","&gt",$LEAVE_TYPE);
   $LEAVE_TYPE=stripslashes($LEAVE_TYPE);

   $RECORD_TIME=$ROW["RECORD_TIME"];
   $ALLOW=$ROW["ALLOW"];
   $STATUS=$ROW["STATUS"];
   $REGISTER_IP=$ROW["REGISTER_IP"];
   $LEADER_ID=$ROW["LEADER_ID"];

    $LEADER_NAME="";
    $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
       $LEADER_NAME=$ROW["USER_NAME"];

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

    if($LEAVE_COUNT==1)
    {
?>

    <table class="TableList" width="100%">

<?
    }
?>
    <tr class="TableData">
      <td width="400" align="center"><?=$LEAVE_TYPE?></td>
      <td nowrap align="center"><?=$LEAVE_TYPE2?></td>
      <td nowrap align="center"><?=$RECORD_TIME?></td>
      <td align="center"><?=$ANNUAL_LEAVE?><?=_("天")?></td>
      <td nowrap align="center"><?=$REGISTER_IP?></td>
      <td nowrap align="center"><?=$LEAVE_DATE1?></td>
      <td nowrap align="center"><?=$LEAVE_DATE2?></td>
      <td nowrap align="center"><?=$LEADER_NAME?></td>
      <td nowrap align="center"><?=$ALLOW_DESC?></td>
      <td nowrap align="center">
<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
        <a href=javascript:leave_edit('<?=$LEAVE_ID?>'); title="<?=_("仅OA管理员可以修改")?>"><?=_("修改")?></a>
      	<a href="delete_leave.php?DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&USER_ID=<?=$USER_ID?>&LEAVE_ID=<?=$LEAVE_ID?>"><?=_("删除")?></a>
<?
}
?>
      	</td>
    </tr>
<?
 }

 if($LEAVE_COUNT>0)
 {
?>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("请假原因")?></td>
      <td nowrap align="center"><?=_("请假类型")?></td>      
      <td nowrap align="center"><?=_("申请时间")?></td>
      <td nowrap align="center"><?=_("占年休假")?></td>
      <td nowrap align="center"><?=_("登记")?>IP</td>
      <td nowrap align="center"><?=_("开始日期")?></td>
      <td nowrap align="center"><?=_("结束日期")?></td>
      <td nowrap align="center"><?=_("审批人员")?></td>
      <td nowrap align="center"><?=_("状态")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </thead>
    </table>
<?
 }
 else
    Message("",_("无请假记录"));
?>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>


<!------------------------------------- 出差记录 ------------------------------->


<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("出差记录")?></span><br>
    </td>
  </tr>
</table>


<?
 $query = "SELECT * from ATTEND_EVECTION where USER_ID='$USER_ID' and ((to_days(EVECTION_DATE1)>=to_days('$DATE1') and to_days(EVECTION_DATE1)<=to_days('$DATE2')) or (to_days(EVECTION_DATE2)>=to_days('$DATE1') and to_days(EVECTION_DATE2)<=to_days('$DATE2')) or (to_days(EVECTION_DATE1)<=to_days('$DATE1') and to_days(EVECTION_DATE2)>=to_days('$DATE2'))) and ALLOW='1'";
 $cursor= exequery(TD::conn(),$query);
 $EVECTION_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $EVECTION_COUNT++;

   $REGISTER_IP=$ROW["REGISTER_IP"];
   $EVECTION_ID=$ROW["EVECTION_ID"];
   $EVECTION_DATE1=$ROW["EVECTION_DATE1"];
   $EVECTION_DATE1=strtok($EVECTION_DATE1," ");
   $EVECTION_DATE2=$ROW["EVECTION_DATE2"];
   $EVECTION_DATE2=strtok($EVECTION_DATE2," ");
   $EVECTION_DEST=$ROW["EVECTION_DEST"];
   $LEADER_ID=$ROW["LEADER_ID"];
   $STATUS=$ROW["STATUS"];
   $ALLOW=$ROW["ALLOW"];
   $REASON=$ROW["REASON"];
   $RECORD_TIME=$ROW["RECORD_TIME"]=="0000-00-00 00:00:00" ? $EVECTION_DATE1 : $ROW["RECORD_TIME"];
   
    $LEADER_NAME="";
    $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
       $LEADER_NAME=$ROW["USER_NAME"];

  	if($ALLOW=="0" && $STATUS=="1")
     	$ALLOW_DESC=_("待审批");
  	else if($ALLOW=="1" && $STATUS=="1")
     	$ALLOW_DESC="<font color=green>"._("已批准")."</font>";
  	else if($ALLOW=="2" && $STATUS=="1")
     	$ALLOW_DESC="<font color=red>"._("未批准")."</font>";
  	else if($ALLOW=="1" && $STATUS=="2")
     	$ALLOW_DESC=_("已归来");

    if($EVECTION_COUNT==1)
    {
?>

    <table class="TableList"  width="100%">

<?
    }
?>
    <tr class="TableData">
      <td nowrap align="center"><?=$RECORD_TIME?></td>
      <td nowrap align="center"><?=$EVECTION_DEST?></td>      
      <td width="400" align="center"><?=$REASON?></td>
      <td nowrap align="center"><?=$REGISTER_IP?></td>
      <td nowrap align="center"><?=$EVECTION_DATE1?></td>
      <td nowrap align="center"><?=$EVECTION_DATE2?></td>
      <td nowrap align="center"><?=$LEADER_NAME?></td>
      <td nowrap align="center"><?=$ALLOW_DESC?></td>
      <td nowrap align="center">
<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
        <a href=javascript:evection_edit('<?=$EVECTION_ID?>'); title="<?=_("仅OA管理员可以修改")?>"><?=_("修改")?></a>
      	<a href="delete_evection.php?DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&USER_ID=<?=$USER_ID?>&EVECTION_ID=<?=$EVECTION_ID?>"><?=_("删除")?></a>
<?
}
?>
     </td>
    </tr>
<?
 }

 if($EVECTION_COUNT>0)
 {
?>
    <thead class="TableHeader">
      <td nowrap align="center"><?=_("申请时间")?></td>
      <td nowrap align="center"><?=_("出差地点")?></td>
      <td nowrap align="center"><?=_("出差原因")?></td>
      <td nowrap align="center"><?=_("登记")?>IP</td>
      <td nowrap align="center"><?=_("开始日期")?></td>
      <td nowrap align="center"><?=_("结束日期")?></td>
      <td nowrap align="center"><?=_("审批人员")?></td>
      <td nowrap align="center"><?=_("状态")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </thead>
    </table>
<?
 }
 else
    Message("",_("无出差记录"));
?>

<br>

<table width="100%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
 </tr>
</table>

<!------------------------------------- 加班记录 ------------------------------->

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("加班记录")?></span><br>
    </td>
  </tr>
</table>

<?
 $query = "SELECT * from ATTENDANCE_OVERTIME where USER_ID='$USER_ID' and ((to_days(START_TIME)>=to_days('$DATE1') and to_days(START_TIME)<=to_days('$DATE2')) or (to_days(END_TIME)>=to_days('$DATE1') and to_days(END_TIME)<=to_days('$DATE2')) or (to_days(START_TIME)<=to_days('$DATE1') and to_days(END_TIME)>=to_days('$DATE2')))and allow in('1','3') order by RECORD_TIME desc";
 $cursor= exequery(TD::conn(),$query);
 $OVERTIME_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
   $OVERTIME_COUNT++;
   
    $OVERTIME_ID=$ROW["OVERTIME_ID"];
    $USER_ID=$ROW["USER_ID"];    
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

    $APPROVE_NAME="";
    $query = "SELECT * from USER where USER_ID='$APPROVE_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
       $APPROVE_NAME=$ROW["USER_NAME"];

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

    if($OVERTIME_COUNT==1)
    {
?>
    <table class="TableList"  width="100%">
<?
    }
?>
    <tr class="TableData">
    	<td nowrap align="center"><?=$RECORD_TIME?></td>
      <td width="400" align="center">
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
      <td nowrap align="center"><?=$APPROVE_NAME?></td>
      <td nowrap align="center"><?=$ALLOW_DESC?>	 </td>
      <td nowrap align="center">
<?
if($_SESSION["LOGIN_USER_PRIV"]==1)
{
?>
        <a href=javascript:overtime_edit('<?=$OVERTIME_ID?>'); title="<?=_("仅OA管理员可以修改")?>"><?=_("修改")?></a>
      	<a href="delete_overtime.php?DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&USER_ID=<?=$USER_ID?>&RECORD_TIME=<?=urlencode($RECORD_TIME)?>"><?=_("删除")?></a>
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
    	<td nowrap align="center"><?=_("申请时间")?></td>
      <td nowrap align="center"><?=_("加班内容")?></td>
      <td nowrap align="center"><?=_("登记")?>IP</td>
      <td nowrap align="center"><?=_("加班开始时间")?></td>
      <td nowrap align="center"><?=_("加班结束时间")?></td>
      <td nowrap align="center"><?=_("时长")?></td>
      <td nowrap align="center"><?=_("审批人员")?></td>
      <td nowrap align="center"><?=_("状态")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </thead>
    </table>
<?
 }
 else
    Message("",_("无加班记录"));
?>

<br>

<div align="center">
  <input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='user.php?USER_ID=<?=$USER_ID?>';">
</div>
</body>
</html>
