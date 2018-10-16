<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("职称评定信息修改保存");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?

$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------合法性校验-------------------------------------
if($RECORD_TIME=="")
{
	Message("",_("登记时间不能为空"));
	Button_Back();
	exit;
}
if($RECORD_TIME!="" && !is_date($RECORD_TIME))
{
   Message("",_("登记时间应为日期型，如：1999-01-01 11:11:11"));
   Button_Back();
   exit;
}
if($CHECK_DUTY_TIME!="" && $CHECK_DUTY_TIME>$RECORD_TIME)
{
	Message("",_("查岗时间不应大于登记时间。"));
	Button_Back();
	exit;
}

$query="UPDATE ATTEND_ASK_DUTY SET CHECK_USER_ID='$CHECK_USER_ID',CHECK_DUTY_MANAGER='$CHECK_DUTY_MANAGER',CHECK_DUTY_TIME='$CHECK_DUTY_TIME',RECORD_TIME='$RECORD_TIME',CHECK_DUTY_REMARK='$CHECK_DUTY_REMARK',EXPLANATION='$EXPLANATION' where ASK_DUTY_ID='$ASK_DUTY_ID'";
exequery(TD::conn(),$query);

header("location: index1.php?PAGE_START=$PAGE_START&connstatus=1");

?>
</body>
</html>
