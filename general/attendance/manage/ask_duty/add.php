<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("�����ѯ��Ϣ");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------У��-------------------------------------
if($CHECK_DUTY_TIME!="" && !is_date($CHECK_DUTY_TIME))
{
   Message("",_("���ʱ��ӦΪ�����ͣ��磺1999-01-01 11:11:11"));
   Button_Back();
   exit;
}

if($CHECK_DUTY_TIME!="" && $CHECK_DUTY_TIME>$CUR_TIME)
{
	Message("",_("���ʱ�䲻Ӧ���ڵǼ�ʱ�䡣"));
	Button_Back();
	exit;
}


//------------------- ���������Ϣ -----------------------
$USER=explode(",",trim($CHECK_USER_ID,","));
for($i=0;$i < count($USER);$i++)
{
	$CHECK_USER_ID=$USER[$i];
	$query="insert into ATTEND_ASK_DUTY(CHECK_USER_ID,CHECK_DUTY_MANAGER,CHECK_DUTY_REMARK,CHECK_DUTY_TIME,CREATE_USER_ID) values ('$CHECK_USER_ID','$CHECK_DUTY_MANAGER','$CHECK_DUTY_REMARK','$CHECK_DUTY_TIME','".$_SESSION["LOGIN_USER_ID"]."')";
  exequery(TD::conn(),$query);
}
Message("",_("��ڵǼǳɹ���"));
?>
<br><center><input type="button" value="<?=_("����")?>" class="BigButton" onClick="location.href='new.php'"></center>
</body>
</html>
