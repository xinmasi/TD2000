<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("查岗质询信息");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------校验-------------------------------------
if($CHECK_DUTY_TIME!="" && !is_date($CHECK_DUTY_TIME))
{
   Message("",_("查岗时间应为日期型，如：1999-01-01 11:11:11"));
   Button_Back();
   exit;
}

if($CHECK_DUTY_TIME!="" && $CHECK_DUTY_TIME>$CUR_TIME)
{
	Message("",_("查岗时间不应大于登记时间。"));
	Button_Back();
	exit;
}


//------------------- 新增查岗信息 -----------------------
$USER=explode(",",trim($CHECK_USER_ID,","));
for($i=0;$i < count($USER);$i++)
{
	$CHECK_USER_ID=$USER[$i];
	$query="insert into ATTEND_ASK_DUTY(CHECK_USER_ID,CHECK_DUTY_MANAGER,CHECK_DUTY_REMARK,CHECK_DUTY_TIME,CREATE_USER_ID) values ('$CHECK_USER_ID','$CHECK_DUTY_MANAGER','$CHECK_DUTY_REMARK','$CHECK_DUTY_TIME','".$_SESSION["LOGIN_USER_ID"]."')";
  exequery(TD::conn(),$query);
}
Message("",_("查岗登记成功！"));
?>
<br><center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location.href='new.php'"></center>
</body>
</html>
