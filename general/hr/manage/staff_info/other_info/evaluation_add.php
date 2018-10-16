<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("职称评定信息");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------校验-------------------------------------
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
//------------------- 新增职称评定信息 -----------------------
$query="insert into HR_STAFF_TITLE_EVALUATION(CREATE_USER_ID,CREATE_DEPT_ID,BY_EVALU_STAFFS,APPROVE_PERSON,POST_NAME,GET_METHOD,REPORT_TIME,RECEIVE_TIME,APPROVE_NEXT,APPROVE_NEXT_TIME,EMPLOY_POST,EMPLOY_COMPANY,START_DATE,END_DATE,REMARK,ADD_TIME,LAST_UPDATE_TIME) 
																			values ('".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$BY_EVALU_STAFFS','$APPROVE_PERSON','$POST_NAME','$GET_METHOD','$REPORT_TIME','$RECEIVE_TIME','$APPROVE_NEXT','$APPROVE_NEXT_TIME','$EMPLOY_POST','$EMPLOY_COMPANY','$START_DATE','$END_DATE','$REMARK','$CUR_TIME','$CUR_TIME')";
exequery(TD::conn(),$query);

Message("",_("成功增加职称评定信息！"));

?>
<br><center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location.href='evaluation_new.php'"></center>
</body>
</html>
