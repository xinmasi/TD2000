<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("招聘筛选");
include_once("inc/header.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
?>
<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------校验-------------------------------------
if($NEXT_DATE_TIME!="")
{
  $TIME_OK=is_date_time($NEXT_DATE_TIME);

  if(!$TIME_OK)
  { Message(_("错误"),_("起始时间格式不对，应形如 1999-1-2 09:30:00"));
    Button_Back();
    exit;
  }
}


 $query="insert into HR_RECRUIT_FILTER(
  CREATE_USER_ID,
  CREATE_DEPT_ID,
  EXPERT_ID,
  EMPLOYEE_NAME,
  PLAN_NO,
  POSITION,
  EMPLOYEE_MAJOR,
  EMPLOYEE_PHONE,
  TRANSACTOR_STEP,
  NEXT_TRANSA_STEP,
  NEXT_DATE_TIME,
  STEP_FLAG,
  NEW_TIME
  )values( 
  '".$_SESSION["LOGIN_USER_ID"]."',
	'".$_SESSION["LOGIN_DEPT_ID"]."',
	'$EXPERT_ID',	
	'$EMPLOYEE_NAME',
  '$PLAN_NAME',
  '$POSITION',
  '$EMPLOYEE_MAJOR',
  '$EMPLOYEE_PHONE',
  '$TRANSACTOR_STEP',
  '$NEXT_TRANSA_STEP',
  '$NEXT_DATE_TIME',
  '1',
  '$CUR_TIME'
)";
exequery(TD::conn(),$query);
$FILTER_ID = mysql_insert_id();

$REMIND_URL1="1:hr/recruit/filter/index1.php";
if($SMS_REMIND=="on" && $NEXT_TRANSA_STEP!="")
   send_sms("",$_SESSION["LOGIN_USER_ID"],$NEXT_TRANSA_STEP,65,$_SESSION["LOGIN_USER_NAME"]._("向您提交招聘筛选，请办理！"),$REMIND_URL1);

if($SMS2_REMIND=="on" && $NEXT_TRANSA_STEP!="")
   send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$NEXT_TRANSA_STEP,$_SESSION["LOGIN_USER_NAME"]._("向您提交招聘筛选，请办理！"),65);


header("location:index1.php?FILTER_ID=$FILTER_ID");
?>
	</body>
	</html>