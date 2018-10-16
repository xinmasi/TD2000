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
if($REPORT_TIME!="" && !is_date($REPORT_TIME))
{
   Message("",_("申报时间应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}

if($RECEIVE_TIME!="" && !is_date($RECEIVE_TIME))
{
   Message("",_("获取时间应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}

if($APPROVE_NEXT_TIME!="" && !is_date($APPROVE_NEXT_TIME))
{
   Message("",_("下次申报时间应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}
if($START_DATE!="" && !is_date($START_DATE))
{
   Message("",_("聘用开始时间应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}
if($END_DATE!="" && !is_date($END_DATE))
{
   Message("",_("聘用结束时间应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}

$query="UPDATE HR_STAFF_TITLE_EVALUATION SET POST_NAME='$POST_NAME',GET_METHOD='$GET_METHOD',REPORT_TIME='$REPORT_TIME',RECEIVE_TIME='$RECEIVE_TIME',APPROVE_PERSON='$APPROVE_PERSON',APPROVE_NEXT='$APPROVE_NEXT',APPROVE_NEXT_TIME='$APPROVE_NEXT_TIME',REMARK='$REMARK',EMPLOY_POST='$EMPLOY_POST',START_DATE='$START_DATE',END_DATE='$END_DATE',EMPLOY_COMPANY='$EMPLOY_COMPANY',BY_EVALU_STAFFS='$BY_EVALU_STAFFS',LAST_UPDATE_TIME='$CUR_TIME' WHERE EVALUATION_ID = '$EVALUATION_ID'";
exequery(TD::conn(),$query);

Message("",_("修改成功！"));
Button_Back();
exit;
?>
</body>
</html>
