<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");


$HTML_PAGE_TITLE = _("�Ӱ�Ǽ�");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());

//----------- �Ϸ���У�� ---------

if($START_TIME!="")
{
  $TIME_OK=is_date_time($START_TIME);

  if(!$TIME_OK)
  { 
  	Message(_("����"),_("�Ӱ࿪ʼʱ�������⣬���ʵ"));
    Button_Back();
    exit;
  }
}

if($END_TIME!="")
{
  $TIME_OK=is_date_time($END_TIME);

  if(!$TIME_OK)
  { 
  	Message(_("����"),_("�Ӱ����ʱ�������⣬���ʵ"));
    Button_Back();
    exit;
  }
}

if(compare_date_time($START_TIME,$END_TIME)>=0)
{ 
	 Message(_("����"),_("�Ӱ����ʱ��Ӧ���ڼӰ࿪ʼʱ��"));
   Button_Back();
   exit;
}
if($OVERTIME_HOURS>99)
{
    Message(_("����"),_("�Ӱ�ʱ��ֻ������λ����"));
    Button_Back();
    exit;
}
if(!is_numeric($OVERTIME_HOURS) || !is_numeric($OVERTIME_MINUTES))
{
    Message(_("����"),_("�Ӱ�ʱ��Ӧ�������֣�"));
    Button_Back();
    exit;
}
if($OVERTIME_HOURS<=0 || $OVERTIME_MINUTES<0)
{
    Message(_("����"),_("�Ӱ�ʱ���������0��"));
    Button_Back();
    exit;
}
$query="select USER_ID from attendance_overtime  where OVERTIME_ID='$OVERTIME_ID'";
$result=exequery(TD::conn(),$query);
if($ROWS=mysql_fetch_array($result))
{
    $OVERTIME_USER_ID=$ROWS["USER_ID"];
}
//�ж��Ƿ��ظ��Ǽ�
if($START_TIME)
{
    //��ͬһʱ����Ƿ��ظ��ύ�Ӱ�����
    $sql = "select *from attendance_overtime where OVERTIME_ID!='$OVERTIME_ID' AND USER_ID = '".$OVERTIME_USER_ID."' AND ((START_TIME >= '".$START_TIME."' AND START_TIME <= '".$END_TIME."') OR (START_TIME <= '".$START_TIME."' AND END_TIME >= '".$START_TIME."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("����"),_("����ʱ����Ѿ�������Ӱ�"));
        Button_Back();
        exit;
    }
}
//�Ӱ�ʱ��
if($OVERTIME_HOURS=="" && $OVERTIME_MINUTES=="")
{   
   $ALL_HOURS3 = floor((strtotime($END_TIME)-strtotime($START_TIME)) / 3600);
   $HOUR13 = (strtotime($END_TIME)-strtotime($START_TIME)) % 3600;
   $MINITE3 = floor($HOUR13 / 60);
   $OVERTIME_HOURS2 = $ALL_HOURS3._("Сʱ").$MINITE3._("��");
}
else
{
	 $OVERTIME_HOURS=$OVERTIME_HOURS==""?0:$OVERTIME_HOURS;
	 $OVERTIME_MINUTES=$OVERTIME_MINUTES==""?0:$OVERTIME_MINUTES;
   $OVERTIME_HOURS2 = $OVERTIME_HOURS._("Сʱ").$OVERTIME_MINUTES._("��");
}

$query="update ATTENDANCE_OVERTIME set OVERTIME_HOURS='$OVERTIME_HOURS2',ALLOW='0',STATUS='0',START_TIME='$START_TIME',REASON='',OVERTIME_CONTENT='$OVERTIME_CONTENT',END_TIME='$END_TIME',APPROVE_ID='$APPROVE_ID',RECORD_TIME='$CUR_TIME',CONFIRM_TIME='0000-00-00 00:00:00' where OVERTIME_ID='$OVERTIME_ID'";
exequery(TD::conn(),$query);
//---------- �������� ----------
$SMS_CONTENT=$_SESSION["LOGIN_USER__NAME"]._("�ύ�Ӱ����룬����ʾ��");
$REMIND_URL = "attendance/manage/confirm";
if($SMS_REMIND=="on")
   send_sms("",$_SESSION["LOGIN_USER_ID"],$APPROVE_ID,6,$SMS_CONTENT,$REMIND_URL);

if($SMS2_REMIND=="on")
   send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$APPROVE_ID,$SMS_CONTENT,6);

header("location: ./?connstatus=1");
?>

</body>
</html>
