<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_org.php");

include_once("inc/flow_hook.php");

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
//�ж��Ƿ��ظ��Ǽ�
if($START_TIME)
{
    if((empty($TO_ID) && $batch!="on") || (empty($COPY_TO_ID) && $batch=="on"))
    {
        $TO_ID = $_SESSION["LOGIN_USER_ID"];
    }
    //��ͬһʱ����Ƿ��ظ��ύ�Ӱ�����
    $sql = "select *from attendance_overtime where  USER_ID = '".$TO_ID."' AND ((START_TIME >= '".$START_TIME."' AND START_TIME <= '".$END_TIME."') OR (START_TIME <= '".$START_TIME."' AND END_TIME >= '".$START_TIME."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("����"),_("����ʱ����Ѿ�������Ӱ�"));
        Button_Back();
        exit;
    }
    if($COPY_TO_ID!='' && $batch=="on")
    {
        $OVERTIME_USER_ID=trim($COPY_TO_ID,',');
        $OVERTIME_USER_ID_ARRAY= explode(',', $OVERTIME_USER_ID);
        $overtime_count=0;
        $overtime_user='';
        for($i=0;$i<count($OVERTIME_USER_ID_ARRAY);$i++)
        {
            $OVERTIME_USER_ID1=$OVERTIME_USER_ID_ARRAY[$i];
            
            //��ͬһʱ����Ƿ��ظ��ύ�Ӱ�����
            $sql = "select *from attendance_overtime where  USER_ID = '".$OVERTIME_USER_ID1."' AND ((START_TIME >= '".$START_TIME."' AND START_TIME <= '".$END_TIME."') OR (START_TIME <= '".$START_TIME."' AND END_TIME >= '".$START_TIME."'))";

            $cursor = exequery(TD::conn(),$sql);

            if($ROW=mysql_fetch_array($cursor)){

                $overtime_count++;
                $overtime_user .=GetUserNameByUserId($OVERTIME_USER_ID1);
            }
        }
        if($overtime_count>0)
        {
            $overtime_user = td_trim($overtime_user);
            Message(_("����"),$overtime_user._("��ʱ����Ѿ�������Ӱ�"));
            Button_Back();
            exit;
        }
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

//��֤��ѡ���ŵ���Ա�Ƿ��ڹ���Χ��
include_once("general/attendance/personal/attend_leave.php");

if($batch=="on")
{
    if($COPY_TO_ID!="")
    {
        $OVERTIME_USER_ID=trim($COPY_TO_ID,',');
        $OVERTIME_USER_ID_ARRAY= explode(',', $OVERTIME_USER_ID);
        for($i=0;$i<count($OVERTIME_USER_ID_ARRAY);$i++)
        {
            $OVERTIME_USER_ID1=$OVERTIME_USER_ID_ARRAY[$i];
            $query="insert into ATTENDANCE_OVERTIME(USER_ID,APPROVE_ID,OVERTIME_CONTENT,START_TIME,END_TIME,RECORD_TIME,OVERTIME_HOURS,ALLOW,REGISTER_IP,STATUS,AGENT) values ('$OVERTIME_USER_ID1','$APPROVE_ID','$OVERTIME_CONTENT','$START_TIME','$END_TIME','$CUR_TIME','$OVERTIME_HOURS2','0','".get_client_ip()."','0','".$_SESSION["LOGIN_USER_ID"]."')";
            exequery(TD::conn(),$query);
        }
    }
    else
    {
        $OVERTIME_USER_ID=$_SESSION["LOGIN_USER_ID"];
        $query="insert into ATTENDANCE_OVERTIME(USER_ID,APPROVE_ID,OVERTIME_CONTENT,START_TIME,END_TIME,RECORD_TIME,OVERTIME_HOURS,ALLOW,REGISTER_IP,STATUS,AGENT) values ('$OVERTIME_USER_ID','$APPROVE_ID','$OVERTIME_CONTENT','$START_TIME','$END_TIME','$CUR_TIME','$OVERTIME_HOURS2','0','".get_client_ip()."','0','".$_SESSION["LOGIN_USER_ID"]."')";
        exequery(TD::conn(),$query);
    }
    $status=0;
}
else
{
    if($TO_ID!="")
        $OVERTIME_USER_ID=$TO_ID;
    else
        $OVERTIME_USER_ID=$_SESSION["LOGIN_USER_ID"];
    $query="insert into ATTENDANCE_OVERTIME(USER_ID,APPROVE_ID,OVERTIME_CONTENT,START_TIME,END_TIME,RECORD_TIME,OVERTIME_HOURS,ALLOW,REGISTER_IP,STATUS,AGENT) values ('$OVERTIME_USER_ID','$APPROVE_ID','$OVERTIME_CONTENT','$START_TIME','$END_TIME','$CUR_TIME','$OVERTIME_HOURS2','0','".get_client_ip()."','0','".$_SESSION["LOGIN_USER_ID"]."')";
    exequery(TD::conn(),$query);
    $ROW_ID = mysql_insert_id();
    $OVERTIME_USER_NAME=getUserNamebyId($OVERTIME_USER_ID);
    $APPROVE_NAME = td_trim(GetUserNameById($APPROVE_ID));
    $data_array=array("KEY"=>"$ROW_ID","field"=>"OVERTIME_ID","USER_ID"=>"$OVERTIME_USER_ID","USER_NAME"=>"$OVERTIME_USER_NAME","APPROVE_ID"=>"$APPROVE_ID","APPROVE_NAME"=>"$APPROVE_NAME","RECORD_TIME"=>"$CUR_TIME","OVERTIME_CONTENT"=>"$OVERTIME_CONTENT","START_TIME"=>"$START_TIME","END_TIME"=>"$END_TIME","REASON"=>"$REASON","OVERTIME_HOURS"=>"$OVERTIME_HOURS2");
    $config= array("module"=>"attendance_overtime");
    $status=0;
    run_hook($data_array,$config);
}

if($status==0)
{
    //---------- �������� ----------
    $SMS_CONTENT=$_SESSION["LOGIN_USER_NAME"]._("�ύ�Ӱ����룬����ʾ��");
    $REMIND_URL = "attendance/manage/confirm";
    if($SMS_REMIND=="on")
        send_sms("",$_SESSION["LOGIN_USER_ID"],$APPROVE_ID,6,$SMS_CONTENT,$REMIND_URL);

    if($SMS2_REMIND=="on")
        send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$APPROVE_ID,$SMS_CONTENT,6);

    header("location: ../index.php");
}
?>

</body>
</html>
