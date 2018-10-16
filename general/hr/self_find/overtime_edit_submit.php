<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("加班确认");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());

//----------- 合法性校验 ---------
if($START_TIME!="")
{
  $TIME_OK=is_date_time($START_TIME);

  if(!$TIME_OK)
  { 
  	Message(_("错误"),_("加班开始时间有问题，请核实"));
    Button_Back();
    exit;
  }
}

if($END_TIME!="")
{
  $TIME_OK=is_date_time($END_TIME);

  if(!$TIME_OK)
  { 
  	Message(_("错误"),_("加班结束时间有问题，请核实"));
    Button_Back();
    exit;
  }
}

if(compare_date_time($START_TIME,$END_TIME)>=0)
{ 
	 Message(_("错误"),_("加班结束时间应晚于加班开始时间"));
   Button_Back();
   exit;
}

//加班时长
if($OVERTIME_HOURS=="" && $OVERTIME_MINUTES=="")
{   
   $ALL_HOURS3 = floor((strtotime($END_TIME)-strtotime($START_TIME)) / 3600);
   $HOUR13 = (strtotime($END_TIME)-strtotime($START_TIME)) % 3600;
   $MINITE3 = floor($HOUR13 / 60);
   $OVERTIME_HOURS2 = $ALL_HOURS3._("小时").$MINITE3._("分");
}
else
{
	 $OVERTIME_HOURS=$OVERTIME_HOURS==""?0:$OVERTIME_HOURS;
	 $OVERTIME_MINUTES=$OVERTIME_MINUTES==""?0:$OVERTIME_MINUTES;
   $OVERTIME_HOURS2 = $OVERTIME_HOURS._("小时").$OVERTIME_MINUTES._("分");
}	

$query="update ATTENDANCE_OVERTIME set OVERTIME_HOURS='$OVERTIME_HOURS2',CONFIRM_TIME='$CUR_TIME',CONFIRM_VIEW='$CONFIRM_VIEW',START_TIME='$START_TIME',END_TIME='$END_TIME',OVERTIME_CONTENT='$OVERTIME_CONTENT' where OVERTIME_ID='$OVERTIME_ID'";
exequery(TD::conn(),$query);
Message(_("提示"),_("操作成功"));
?>
<center>
	<input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="javascript:window.close();">
</center>	
</body>
</html>
