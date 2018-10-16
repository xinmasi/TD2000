<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("工作日志设置");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
//----------- 合法性校验 ---------
if($W_START!="")
{
  $TIME_OK=is_date($W_START);

  if(!$TIME_OK)
  { Message(_("错误"),_("开始时间格式不对，应形如 1999-1-2"));
    Button_Back();
    exit;
  }
}

if($W_END!="")
{
   $TIME_OK=is_date($W_END);

   if(!$TIME_OK)
   { Message(_("错误"),_("结束时间格式不对，应形如 1999-1-2"));
     Button_Back();
     exit;
   }
}

if($W_START!="" && $W_END!="" && compare_date_time($W_END,$W_START)<=0)
{
   Message(_("错误"),_("开始时间不能大于结束时间！"));
   Button_Back();
   exit;
}
if($DAYS!="" && !is_numeric($DAYS))
{
   Message(_("错误"),_("天数范围必须为整数！"));
   Button_Back();
   exit;
}
$PARA_VALUE=$W_START.",".$W_END.",".intval(abs($DAYS));

if(!isset($LOCK_SHARE)){
   $LOCK_SHARE = 0;
}

if(!isset($IS_COMMENTS)){
   $IS_COMMENTS = 0;
}

if(!isset($ALL_SHARE)){
   $ALL_SHARE = 0;
}
set_sys_para(array("LOCK_TIME" =>"$PARA_VALUE","IS_COMMENTS"=>"$IS_COMMENTS","LOCK_SHARE"=>"$LOCK_SHARE","ALL_SHARE"=>"$ALL_SHARE","DIARY_WORK_LOG_FORMAT"=>"$DIARY_WORK_LOG_FORMAT"));
Message(_("提示"),_("设置成功！"));
Button_Back();
?>

</body>
</html>
