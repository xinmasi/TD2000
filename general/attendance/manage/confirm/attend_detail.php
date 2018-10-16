<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("考勤查看");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
//ATTEND_ID=4&ATTEND_TYPE=ATTEND_EVECTION
//$ALL_ATTEND=array("all" => _("所有审批"), "ATTEND_OUT" => _("外出审批"), "ATTEND_LEAVE" => _("请假审批"), "ATTEND_EVECTION" => _("出差审批"), "ATTENDANCE_OVERTIME" => _("加班审批"));
//$ALL_ATTEND=array("all" => _("所有审批"), "ATTEND_OUT" => _("外出审批"), "ATTEND_LEAVE" => _("请假审批"), "ATTEND_EVECTION" => _("出差审批"), "ATTENDANCE_OVERTIME" => _("加班审批"));

if($ATTEND_TYPE=="ATTEND_OUT")//外出详情
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=$MYOA_IMG_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("查看外出详情")?></span><br>
        </td>
    </tr>
</table>
<?
$ATTEND_ID=intval($ATTEND_ID);
$query="SELECT * FROM ATTEND_OUT,USER WHERE ATTEND_OUT.OUT_ID='$ATTEND_ID' and USER.USER_ID=ATTEND_OUT.USER_ID";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $OUT_ID=$ROW["OUT_ID"];
    $USER_ID=$ROW["USER_ID"];
    $USER_NAME=$ROW["USER_NAME"];
    $OUT_TYPE=$ROW["OUT_TYPE"];
    $OUT_TIME1=$ROW["OUT_TIME1"];
    $OUT_TIME2=$ROW["OUT_TIME2"];
    $ALLOW=$ROW["ALLOW"];
    $STATUS=$ROW["STATUS"];
    $CREATE_DATE1=$ROW["CREATE_DATE"];
    $DEPT_ID=$ROW["DEPT_ID"];
    $SUBMIT_TIME=$ROW["SUBMIT_TIME"];
    $REGISTER_IP=$ROW["REGISTER_IP"];
    $SUBMIT_TIME1=substr($SUBMIT_TIME,0,10);
    $USER_DEPT_NAME = dept_long_name($DEPT_ID);

    if($ALLOW=="0" && $STATUS=="0")
        $ALLOW_DESC=_("待审批");
    else if($ALLOW=="1" && $STATUS=="0")
        $ALLOW_DESC="<font color=green>"._("已批准")."</font>";
    else if($ALLOW=="2" && $STATUS=="0")
        $ALLOW_DESC="<font color=red>"._("未批准")."</font>";
    else if($ALLOW=="1" && $STATUS=="1")
        $ALLOW_DESC=_("已归来");
?>
<table class="TableBlock" width="90%" align="center">
    <tr>
        <td nowrap class="TableData" width="100"> <?=_("部门：")?></td>
        <td class="TableData"><?=$USER_DEPT_NAME?></td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("外出人：")?></td>
        <td class="TableData"><?=$USER_NAME?></td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("外出原因：")?></td>
        <td class="TableData" colspan="3"> <?=$OUT_TYPE?> </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("外出时间：")?></td>
        <td class="TableData">
            <?=$SUBMIT_TIME1." ".$OUT_TIME1."(".get_week($SUBMIT_TIME1).")"?>
            <?=_("至")?> <?=$SUBMIT_TIME1." ".$OUT_TIME2."(".get_week($SUBMIT_TIME1).")"?><br>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("申请时间：")?></td>
        <td class="TableData"> <?=$CREATE_DATE1?> </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("登记IP：")?></td>
        <td class="TableData"> <?=$REGISTER_IP?> </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("审批状态：")?></td>
        <td class="TableData"> <?=$ALLOW_DESC?> </td>
    </tr>
</table>
<br><div align="center"><input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="javascript: window.close();" ></div>
<?
	}
	else
	{
		message("",_("无外出审批"));
		button_close();
        exit;
	}
}
else if($ATTEND_TYPE=="ATTEND_LEAVE")
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=$MYOA_IMG_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("查看请假详情")?></span><br>
        </td>
    </tr>
</table>
<?
$ATTEND_ID=intval($ATTEND_ID);
$query="SELECT * FROM ATTEND_LEAVE,USER WHERE ATTEND_LEAVE.USER_ID=USER.USER_ID and ATTEND_LEAVE.LEAVE_ID='$ATTEND_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $USER_NAME=$ROW["USER_NAME"];
    $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
    $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
    $ANNUAL_LEAVE=$ROW["ANNUAL_LEAVE"];
    $LEAVE_TYPE=$ROW["LEAVE_TYPE"];
    $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
    $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");
    $REGISTER_IP=$ROW["REGISTER_IP"];
    $RECORD_TIME=$ROW["RECORD_TIME"];
    $DESTROY_TIME=$ROW["DESTROY_TIME"];
    if($DESTROY_TIME=="0000-00-00 00:00:00")
       $DESTROY_TIME="";

    $ALLOW=$ROW["ALLOW"];
    $STATUS=$ROW["STATUS"];
    $DEPT_ID=$ROW["DEPT_ID"];
    $USER_DEPT_NAME = dept_long_name($DEPT_ID);

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
?>
<table class="TableBlock" width="90%" align="center">
    <tr>
        <td nowrap class="TableData" width="100"> <?=_("部门：")?></td>
        <td class="TableData"><?=$USER_DEPT_NAME?></td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("请假人：")?></td>
        <td class="TableData"><?=$USER_NAME?></td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("请假类型：")?></td>
        <td class="TableData"> <?=$LEAVE_TYPE2?> </td>
    </tr>
    <tr>
    <td nowrap class="TableData"> <?=_("请假时间：")?></td>
        <td class="TableData">
            <?=$LEAVE_DATE1."(".get_week($LEAVE_DATE1).")"?>
            <?=_("至")?> <?=$LEAVE_DATE2."(".get_week($LEAVE_DATE2).")"?><br>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("请假原因：")?></td>
        <td class="TableData"> <?=$LEAVE_TYPE?> </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("申请时间：")?></td>
        <td class="TableData"> <?=$RECORD_TIME?> </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("登记IP：")?></td>
        <td class="TableData"> <?=$REGISTER_IP?> </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("审批状态：")?></td>
        <td class="TableData"> <?=$ALLOW_DESC?> </td>
    </tr>
</table>
<br><div align="center"><input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="javascript: window.close();" ></div>
<?
	}
	else
	{
		message("",_("无请假审批"));
		button_close();
        exit;
	}
}
else if($ATTEND_TYPE=="ATTEND_EVECTION")
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
<tr>
    <td class="Big"><img src="<?=$MYOA_IMG_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("查看出差详情")?></span><br>
    </td>
</tr>
</table>
<?
$query="SELECT * FROM ATTEND_EVECTION,USER WHERE ATTEND_EVECTION.USER_ID=USER.USER_ID and ATTEND_EVECTION.EVECTION_ID='$ATTEND_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   	$USER_NAME=$ROW["USER_NAME"];
   	$REASON=$ROW["REASON"];
   	$REGISTER_IP=$ROW["REGISTER_IP"];
   	$EVECTION_DATE1=$ROW["EVECTION_DATE1"];
   	$EVECTION_DATE1=strtok($EVECTION_DATE1," ");
   	$EVECTION_DATE2=$ROW["EVECTION_DATE2"];
   	$RECORD_TIME=$ROW["RECORD_TIME"];
   	$EVECTION_DATE2=strtok($EVECTION_DATE2," ");
   	$EVECTION_DEST=$ROW["EVECTION_DEST"];
    $ALLOW=$ROW["ALLOW"];
    $STATUS=$ROW["STATUS"];
   	$DEPT_ID=$ROW["DEPT_ID"];
    $USER_DEPT_NAME = dept_long_name($DEPT_ID);
    $time1 = strtotime($EVECTION_DATE2)-strtotime($EVECTION_DATE1);
    $day1 = round($time1/86400)+1;
  	if($ALLOW=="0" && $STATUS=="1")
     	$ALLOW_DESC=_("待审批");
  	else if($ALLOW=="1" && $STATUS=="1")
     	$ALLOW_DESC="<font color=green>"._("已批准")."</font>";
  	else if($ALLOW=="2" && $STATUS=="1")
     	$ALLOW_DESC="<font color=red>"._("未批准")."</font>";
  	else if($ALLOW=="1" && $STATUS=="2")
     	$ALLOW_DESC=_("已归来");
?>
<table class="TableBlock" width="90%" align="center">
    <tr>
        <td nowrap class="TableData" width="100"> <?=_("部门：")?></td>
        <td class="TableData"><?=$USER_DEPT_NAME?></td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("出差人：")?></td>
        <td class="TableData"><?=$USER_NAME?></td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("出差地点：")?></td>
        <td class="TableData"> <?=$EVECTION_DEST?> </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("出差原因：")?></td>
        <td class="TableData"> <?=$REASON?> </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("出差时间：")?></td>
        <td class="TableData">
          <?=$EVECTION_DATE1."(".get_week($EVECTION_DATE1).")"?>
          <?=_("至")?> <?=$EVECTION_DATE2."(".get_week($EVECTION_DATE2).")"?>
          <?=_("共")?> <?=$day1._(" 天")?>
          <br>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("申请时间：")?></td>
        <td class="TableData"> <?=$RECORD_TIME=="0000-00-00 00:00:00" ? $EVECTION_DATE1:$RECORD_TIME?> </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("登记IP：")?></td>
        <td class="TableData"> <?=$REGISTER_IP?> </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("审批状态：")?></td>
        <td class="TableData"> <?=$ALLOW_DESC?></td>
    </tr>
</table>
<br><div align="center"><input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="javascript: window.close();" ></div>
<?
	}
	else
	{
		message("",_("无出差审批"));
		button_close();
        exit;
	}
}
else if($ATTEND_TYPE=="ATTENDANCE_OVERTIME")
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=$MYOA_IMG_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("查看加班详情")?></span><br>
        </td>
    </tr>
</table>
<?
$ATTEND_ID=intval($ATTEND_ID);
$query="SELECT * FROM ATTENDANCE_OVERTIME,USER WHERE ATTENDANCE_OVERTIME.USER_ID=USER.USER_ID and ATTENDANCE_OVERTIME.OVERTIME_ID='$ATTEND_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $USER_NAME=$ROW["USER_NAME"];
    $APPROVE_ID=$ROW["APPROVE_ID"];
    $RECORD_TIME=$ROW["RECORD_TIME"];
    $START_TIME=$ROW["START_TIME"];
    $END_TIME=$ROW["END_TIME"];
    $OVERTIME_HOURS=$ROW["OVERTIME_HOURS"];
    $OVERTIME_CONTENT=$ROW["OVERTIME_CONTENT"];
    $ALLOW=$ROW["ALLOW"];
    $STATUS=$ROW["STATUS"];
    $REGISTER_IP=$ROW["REGISTER_IP"];

   	$DEPT_ID=$ROW["DEPT_ID"];
    $USER_DEPT_NAME = dept_long_name($DEPT_ID);

	if($ALLOW=="0" && $STATUS=="0")
    	 $ALLOW_DESC=_("待审批");
  	else if($ALLOW=="1" && $STATUS=="0")
     	$ALLOW_DESC="<font color=green>"._("已批准")."</font>";
  	else if($ALLOW=="2" && $STATUS=="0")
     	$ALLOW_DESC= "<font color=red>"._("未批准")."</font>";
  	else if($ALLOW=="3" && $STATUS=="0")
     	$ALLOW_DESC=_("申请确认");
  	else if($ALLOW=="3" && $STATUS=="1")
     	$ALLOW_DESC=_("已确认");
?>
<table class="TableBlock" width="90%" align="center">
    <tr>
        <td nowrap class="TableData" width="100"> <?=_("部门：")?></td>
        <td class="TableData"><?=$USER_DEPT_NAME?></td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("加班人：")?></td>
        <td class="TableData"><?=$USER_NAME?></td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("加班内容：")?></td>
        <td class="TableData"> <?=$OVERTIME_CONTENT?> </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("开始时间：")?></td>
        <td class="TableData"> <?=$START_TIME?> </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("结束时间：")?></td>
        <td class="TableData"><?=$END_TIME?></td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("加班时长：")?></td>
        <td class="TableData"> <?=$OVERTIME_HOURS?> </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("申请时间：")?></td>
        <td class="TableData"> <?=$RECORD_TIME?> </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("登记IP：")?></td>
        <td class="TableData"> <?=$REGISTER_IP?> </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("审批状态：")?></td>
        <td class="TableData"> <?=$ALLOW_DESC?> </td>
    </tr>
</table>
<br><div align="center"><input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="javascript: window.close();" ></div>
<?
	}
}
?>
</body>
</html>