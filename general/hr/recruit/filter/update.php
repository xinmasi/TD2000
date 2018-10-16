<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("招聘筛选");
include_once("inc/header.inc.php");
?>


<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
$query="update HR_RECRUIT_FILTER set
  EXPERT_ID='$EXPERT_ID',
  EMPLOYEE_NAME='$EMPLOYEE_NAME',
  PLAN_NO='$PLAN_NAME',
  POSITION='$POSITION',
  EMPLOYEE_MAJOR='$EMPLOYEE_MAJOR',
  EMPLOYEE_PHONE='$EMPLOYEE_PHONE',
  NEXT_TRANSA_STEP='$NEXT_TRANSA_STEP',
  NEXT_DATE_TIME='$NEXT_DATE_TIME'  
   where FILTER_ID='$FILTER_ID'";
exequery(TD::conn(),$query);

$REMIND_URL1="1:hr/recruit/filter/index1.php";
if($SMS_REMIND1=="on" && $NEXT_TRANSA_STEP!="")
   send_sms("",$_SESSION["LOGIN_USER_ID"],$NEXT_TRANSA_STEP,65,$_SESSION["LOGIN_USER_NAME"]._("向您提交招聘筛选，请办理！"),$REMIND_URL1);

if($SMS2_REMIND1=="on" && $NEXT_TRANSA_STEP!="")
   send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$NEXT_TRANSA_STEP,$_SESSION["LOGIN_USER_NAME"]._("向您提交招聘筛选，请办理！"),65);

header("location:index1.php?FILTER_ID=$FILTER_ID&start=$start&connstatus=1");
?>
</body>
</html>