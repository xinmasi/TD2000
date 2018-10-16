<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_org.php");
include_once("inc/flow_hook.php");

$HTML_PAGE_TITLE = _("�½���ٵǼ�");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//----------- �Ϸ���У�� ---------
if($LEAVE_DATE1!="")
{
    $TIME_OK=is_date_time($LEAVE_DATE1);

    if(!$TIME_OK)
    {
        Message(_("����"),_("��ٿ�ʼʱ���ʽ���ԣ�Ӧ���� 1999-01-01 12:12:12"));
        Button_Back();
        exit;
    }
}

if($LEAVE_DATE2!="")
{
    $TIME_OK=is_date_time($LEAVE_DATE2);

    if(!$TIME_OK)
    {
        Message(_("����"),_("��ٽ���ʱ���ʽ���ԣ�Ӧ���� 1999-01-01 12:12:12"));
        Button_Back();
        exit;
    }
}

if(compare_date_time($LEAVE_DATE1,$LEAVE_DATE2)>=0)
{
    Message(_("����"),_("��ٽ���ʱ��Ӧ������ٿ�ʼʱ��"));
    Button_Back();
    exit;
}


if($TO_ID!="")
    $LEAVE_USER_ID=$TO_ID;
else
    $LEAVE_USER_ID=$_SESSION["LOGIN_USER_ID"];

//----------------��ȡ���ʣ������-----------------

include_once("get_ann_func.php");
//$ANNUAL_LEAVE_LEFT=get_ann(LEAVE_USER_ID);
$DAY_DIFF= DateDiff_("d",$LEAVE_DATE1,$LEAVE_DATE2);
if($DAY_DIFF< $ANNUAL_LEAVE)
    $ANNUAL_LEAVE=$DAY_DIFF;
if(!is_numeric($ANNUAL_LEAVE))
{
    Message(_("����"),_("ʹ�����ݼ�Ӧ�������֣�"));
    Button_Back();
    exit;
}
if($LEAVE_TYPE2 == "3" && $ANNUAL_LEAVE<=0)
{
    Message(_("����"),_("ʹ�����ݼ������������0��"));
    Button_Back();
    exit;
}
if(!preg_match('/^[0-9]\d*([.][0|5])?$/', $ANNUAL_LEAVE))
{
    Message(_("����"),_("ʹ�����ݼ�ֻ֧�������0.5�죡"));
    Button_Back();
    exit;
}
//echo $ANNUAL_LEAVE."</br>".$ANNUAL_LEAVE_LEFT;
//exit();
if($ANNUAL_LEAVE > $ANNUAL_LEAVE_LEFT)
{
    Message(_("����"),_("ʹ�����ݼ�����ӦС�ڻ�������ݼ�ʣ��������"));
    Button_Back();
    exit;
}

$CUR_TIME=date("Y-m-d H:i:s",time());
if(compare_date_time($CUR_TIME,$LEAVE_DATE2)>=0)
    $LEAVE_TYPE = _("���٣�").$LEAVE_TYPE;

$query="insert into ATTEND_LEAVE(USER_ID,LEADER_ID,LEAVE_TYPE,LEAVE_DATE1,LEAVE_DATE2,ANNUAL_LEAVE,STATUS,ALLOW,REGISTER_IP,RECORD_TIME,LEAVE_TYPE2,AGENT) values ('$LEAVE_USER_ID','$LEADER_ID','$LEAVE_TYPE','$LEAVE_DATE1','$LEAVE_DATE2','$ANNUAL_LEAVE','1','0','".get_client_ip()."','$CUR_TIME','$LEAVE_TYPE2','".$_SESSION["LOGIN_USER_ID"]."')";
exequery(TD::conn(),$query);
$ROW_ID=mysql_insert_id();
$LEAVE_USER_NAME=td_trim(GetUserNameById($LEAVE_USER_ID));
$LEADER_NAME = td_trim(GetUserNameById($LEADER_ID));
$data_array=array("KEY"=>"$ROW_ID","field"=>"LEAVE_ID","USER_ID"=>"$LEAVE_USER_ID","USER_NAME"=>"$LEAVE_USER_NAME","LEAVE_TYPE"=>"$LEAVE_TYPE","LEAVE_DATE1"=>"$LEAVE_DATE1","LEAVE_DATE2"=>"$LEAVE_DATE2","ANNUAL_LEAVE"=>"$ANNUAL_LEAVE","LEADER_ID"=>"$LEADER_ID","LEADER_NAME"=>"$LEADER_NAME");
$config= array("module"=>"attend_leave");
$status=0;
run_hook($data_array,$config);
if($status==0)
{
    //---------- �������� ----------
    $SMS_CONTENT=$_SESSION["LOGIN_USER_NAME"]._("�ύ������룬����ʾ��");
    $REMIND_URL="attendance/manage/confirm";
    if($SMS_REMIND=="on")
        send_sms("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,6,$SMS_CONTENT,$REMIND_URL);
    if($SMS2_REMIND=="on")
        send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,$SMS_CONTENT,6);
    header("location: ../?connstatus=1");
}
?>

</body>
</html>
