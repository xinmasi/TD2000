<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");


$HTML_PAGE_TITLE = _("����Ǽ�");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());

//----------- �Ϸ���У�� ---------

if($OUT_TIME1!="")
{
	$OUT_TIME11="1999-01-02 ".$OUT_TIME1.":02";
  $TIME_OK=is_date_time($OUT_TIME11);

  if(!$TIME_OK)
  { 
  	Message(_("����"),_("ʱ�������⣬���ʵ"));
    Button_Back();
    exit;
  }
}

if($OUT_TIME2!="")
{
	$OUT_TIME22="1999-01-02 ".$OUT_TIME2.":02";
  $TIME_OK=is_date_time($OUT_TIME22);

  if(!$TIME_OK)
  { 
  	Message(_("����"),_("ʱ�������⣬���ʵ"));
    Button_Back();
    exit;
  }
}

if(compare_date_time($OUT_TIME11,$OUT_TIME22)>=0)
{ 
	 Message(_("����"),_("�������ʱ��Ӧ���������ʼʱ��"));
   Button_Back();
   exit;
}
$query="select USER_ID from ATTEND_OUT  where OUT_ID='$OUT_ID'";
$result=exequery(TD::conn(),$query);
if($ROWS=mysql_fetch_array($result))
{
    $OUT_USER_ID=$ROWS["USER_ID"];
}
if($OUT_DATE != "")
{
    
    //�����ʱ�����Ƚ�
    $b_time=$OUT_DATE." ".$OUT_TIME1;//��ʼʱ��
    $e_time=$OUT_DATE." ".$OUT_TIME2;//����ʱ��
    $sql = "select *from ATTEND_LEAVE where  USER_ID = '".$OUT_USER_ID."' AND ((LEAVE_DATE1 >= '".$b_time."' AND LEAVE_DATE1 <= '".$e_time."') OR (LEAVE_DATE1 <= '".$b_time."'AND LEAVE_DATE2 >= '".$b_time."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("����"),_("�����������ʱ������ʱ���г�ͻ"));
        Button_Back();
        exit;
    }

    //�ͳ���ʱ�����Ƚ�
    $sql = "select *from ATTEND_EVECTION where  USER_ID = '".$OUT_USER_ID."' AND (EVECTION_DATE1 <= '".$OUT_DATE."' AND EVECTION_DATE2 >= '".$OUT_DATE."')";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("����"),_("�����������ʱ��ͳ���ʱ���г�ͻ"));
        Button_Back();
        exit;
    }
    
    //��ͬһʱ����Ƿ��ظ��ύ�������
    $sql = "select * from ATTEND_OUT where OUT_ID!='$OUT_ID' and USER_ID = '".$OUT_USER_ID."' AND ((concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) >= '".str_replace(' ','',$b_time)."' AND concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) <= '".str_replace(' ','',$e_time)."') OR (concat(LEFT (SUBMIT_TIME ,10),OUT_TIME2) >= '".str_replace(' ','',$b_time)."' AND concat(LEFT (SUBMIT_TIME ,10),OUT_TIME1) <= '".str_replace(' ','',$b_time)."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("����"),_("����ʱ����Ѿ���������"));
        Button_Back();
        exit;
    }
}
$SUBMIT_TIME=$OUT_DATE." ".$OUT_TIME1;
$query="update ATTEND_OUT set ALLOW='0',STATUS='0',SUBMIT_TIME='$SUBMIT_TIME',REASON='',OUT_TYPE='$OUT_TYPE',OUT_TIME1='$OUT_TIME1',OUT_TIME2='$OUT_TIME2',LEADER_ID='$LEADER_ID' where OUT_ID='$OUT_ID'";
exequery(TD::conn(),$query);
//---------- �������� ----------
$SMS_CONTENT=$_SESSION["LOGIN_USER__NAME"]._("�ύ������룬����ʾ��");
$REMIND_URL="attendance/manage/confirm";
if($SMS_REMIND=="on")
   send_sms("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,6,$SMS_CONTENT,$REMIND_URL);

if($SMS2_REMIND=="on")
   send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,$SMS_CONTENT,6);

header("location: ./");
?>

</body>
</html>
