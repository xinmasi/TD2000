<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("�޸���ٵǼ�");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
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
$query="select USER_ID,ANNUAL_LEAVE from ATTEND_LEAVE  where LEAVE_ID='$LEAVE_ID'";
$result=exequery(TD::conn(),$query);
if($ROWS=mysql_fetch_array($result))
{
    $LEAVE_USER_ID=$ROWS["USER_ID"];
    $LEAVE=$ROWS["ANNUAL_LEAVE"];
}
if($LEAVE_DATE1)
{
    $leave_date_start = substr($LEAVE_DATE1,0,10);
    $leave_date_end = substr($LEAVE_DATE2,0,10);

    // �����ʱ�����Ƚ�
    $sql = "select *from ATTEND_OUT where  USER_ID = '".$LEAVE_USER_ID."' AND ((concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) >= '".str_replace(' ', '',$LEAVE_DATE1)."' AND concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) <= '".str_replace(' ', '',$LEAVE_DATE2)."') OR (concat(LEFT (SUBMIT_TIME ,10),OUT_TIME2) >= '".str_replace(' ', '',$LEAVE_DATE1)."' AND concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) <= '".str_replace(' ', '',$LEAVE_DATE1)."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("����"),_("��������ٵ�ʱ������ʱ���г�ͻ"));
        Button_Back();
        exit;
    }

    //�ͳ���ʱ�����Ƚ�
    $sql = "select *from ATTEND_EVECTION where  USER_ID = '".$LEAVE_USER_ID."' AND ((EVECTION_DATE1 >= '".$leave_date_start."' AND EVECTION_DATE1 <= '".$leave_date_end."') OR (EVECTION_DATE1 <= '".$leave_date_start."' AND EVECTION_DATE2 >= '".$leave_date_start."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("����"),_("��������ٵ�ʱ��ͳ���ʱ���г�ͻ"));
        Button_Back();
        exit;
    }
    
    //��ͬһʱ����Ƿ��ظ��ύ�������
    $sql = "select *from ATTEND_LEAVE where LEAVE_ID!='$LEAVE_ID' and USER_ID = '".$LEAVE_USER_ID."' AND ((LEAVE_DATE1 >= '".$LEAVE_DATE1."' AND LEAVE_DATE1 <= '".$LEAVE_DATE2."') OR (LEAVE_DATE1 <= '".$LEAVE_DATE1."' AND LEAVE_DATE2 >= '".$LEAVE_DATE1."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("����"),_("����ʱ����Ѿ���������"));
        Button_Back();
        exit;
    }
}
//----------------��ȡ���ʣ������-----------------
include_once("new/get_ann_func.php");
//$ANNUAL_LEAVE_LEFT=get_ann($LEAVE_USER_ID);
$DAY_DIFF= DateDiff_("d",$LEAVE_DATE1,$LEAVE_DATE2);
if($DAY_DIFF< $ANNUAL_LEAVE)
    $ANNUAL_LEAVE=$DAY_DIFF;
if($LEAVE_TYPE2 == 3 && !is_numeric($ANNUAL_LEAVE))
{
    Message(_("����"),_("ʹ�����ݼ�Ӧ�������֣�"));
    Button_Back();
    exit;
}
if($LEAVE_TYPE2 == 3 && !preg_match('/^[0-9]\d*([.][0|5])?$/', $ANNUAL_LEAVE))
{
    Message(_("����"),_("ʹ�����ݼ�ֻ֧��1���0.5�죡"));
    Button_Back();
    exit;
}
if($LEAVE_TYPE2 == 3 && $ANNUAL_LEAVE > $ANNUAL_LEAVE_LEFT)
{
    Message(_("����"),_("ʹ�����ݼ�����ӦС�ڻ�������ݼ�ʣ��������"));
    Button_Back();
    exit;
}


$CUR_TIME=date("Y-m-d H:i:s",time());
if(compare_date_time($CUR_TIME,$LEAVE_DATE2)>=0 && !strstr($LEAVE_TYPE,_("����")))
    $LEAVE_TYPE = _("���٣�").$LEAVE_TYPE;

$query="update ATTEND_LEAVE set ALLOW='0',STATUS='1',DESTROY_TIME='0000-00-00 00:00:00',REASON='',LEAVE_TYPE='$LEAVE_TYPE',LEAVE_DATE1='$LEAVE_DATE1',LEAVE_DATE2='$LEAVE_DATE2',ANNUAL_LEAVE='$ANNUAL_LEAVE',LEADER_ID='$LEADER_ID',LEAVE_TYPE2='$LEAVE_TYPE2' where LEAVE_ID='$LEAVE_ID'";
exequery(TD::conn(),$query);

//---------- �������� ----------
$SMS_CONTENT=$_SESSION["LOGIN_USER_NAME"]._("�ύ������룬����ʾ��");
$REMIND_URL="attendance/manage/confirm";
if($SMS_REMIND=="on")
    send_sms("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,6,$SMS_CONTENT,$REMIND_URL);
if($SMS2_REMIND=="on")
    send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,$SMS_CONTENT,6);

header("location: ./?connstatus=1");
?>

</body>
</html>
