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
if($REPORT_TIME!="" && !is_date($REPORT_TIME))
{
   Message("",_("�걨ʱ��ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}

if($RECEIVE_TIME!="" && !is_date($RECEIVE_TIME))
{
   Message("",_("��ȡʱ��ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}

if($APPROVE_NEXT_TIME!="" && !is_date($APPROVE_NEXT_TIME))
{
   Message("",_("�´��걨ʱ��ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}
if($START_DATE!="" && !is_date($START_DATE))
{
   Message("",_("Ƹ�ÿ�ʼʱ��ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}
if($END_DATE!="" && !is_date($END_DATE))
{
   Message("",_("Ƹ�ý���ʱ��ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}

$query="UPDATE HR_STAFF_TITLE_EVALUATION SET POST_NAME='$POST_NAME',GET_METHOD='$GET_METHOD',REPORT_TIME='$REPORT_TIME',RECEIVE_TIME='$RECEIVE_TIME',APPROVE_PERSON='$APPROVE_PERSON',APPROVE_NEXT='$APPROVE_NEXT',APPROVE_NEXT_TIME='$APPROVE_NEXT_TIME',REMARK='$REMARK',EMPLOY_POST='$EMPLOY_POST',START_DATE='$START_DATE',END_DATE='$END_DATE',EMPLOY_COMPANY='$EMPLOY_COMPANY',BY_EVALU_STAFFS='$BY_EVALU_STAFFS',LAST_UPDATE_TIME='$CUR_TIME' WHERE EVALUATION_ID = '$EVALUATION_ID'";
exequery(TD::conn(),$query);

Message("",_("�޸ĳɹ���"));
Button_Back();
exit;
?>
</body>
</html>
