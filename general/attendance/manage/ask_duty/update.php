<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("ְ��������Ϣ�޸ı���");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?

$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------�Ϸ���У��-------------------------------------
if($RECORD_TIME=="")
{
	Message("",_("�Ǽ�ʱ�䲻��Ϊ��"));
	Button_Back();
	exit;
}
if($RECORD_TIME!="" && !is_date($RECORD_TIME))
{
   Message("",_("�Ǽ�ʱ��ӦΪ�����ͣ��磺1999-01-01 11:11:11"));
   Button_Back();
   exit;
}
if($CHECK_DUTY_TIME!="" && $CHECK_DUTY_TIME>$RECORD_TIME)
{
	Message("",_("���ʱ�䲻Ӧ���ڵǼ�ʱ�䡣"));
	Button_Back();
	exit;
}

$query="UPDATE ATTEND_ASK_DUTY SET CHECK_USER_ID='$CHECK_USER_ID',CHECK_DUTY_MANAGER='$CHECK_DUTY_MANAGER',CHECK_DUTY_TIME='$CHECK_DUTY_TIME',RECORD_TIME='$RECORD_TIME',CHECK_DUTY_REMARK='$CHECK_DUTY_REMARK',EXPLANATION='$EXPLANATION' where ASK_DUTY_ID='$ASK_DUTY_ID'";
exequery(TD::conn(),$query);

header("location: index1.php?PAGE_START=$PAGE_START&connstatus=1");

?>
</body>
</html>
