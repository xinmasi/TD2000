<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("�½�����Ǽ�");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
//----------- �Ϸ���У�� ---------
if($EVECTION_DATE1!="")
{
  $TIME_OK=is_date($EVECTION_DATE1);

  if(!$TIME_OK)
  { Message(_("����"),_("���ʼ���ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
    Button_Back();
    exit;
  }
}

if($EVECTION_DATE2!="")
{
  $TIME_OK=is_date($EVECTION_DATE2);

  if(!$TIME_OK)
  { Message(_("����"),_("����������ڸ�ʽ���ԣ�Ӧ���� 1999-1-2"));
    Button_Back();
    exit;
  }
}

if(compare_date($EVECTION_DATE1,$EVECTION_DATE2)==1)
{ Message(_("����"),_("���ʼ���ڲ������ڳ����������"));
  Button_Back();
  exit;
}
$query="select USER_ID from attend_evection  where EVECTION_ID='$EVECTION_ID'";
$result=exequery(TD::conn(),$query);
if($ROWS=mysql_fetch_array($result))
{
    $EVECTION_USER_ID=$ROWS["USER_ID"];
}  
if($EVECTION_DATE1)
{
    // �����ʱ�����Ƚ�
    $sql = "select *from ATTEND_OUT where  USER_ID = '".$EVECTION_USER_ID."' AND  cast(SUBMIT_TIME as date) <= '".$EVECTION_DATE2."' AND cast(SUBMIT_TIME as date) >= '".$EVECTION_DATE1."'";
    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("����"),_("����������ʱ������ʱ���г�ͻ"));
        Button_Back();
        exit;
    }

    //�����ʱ�����Ƚ�
    $sql = "select *from ATTEND_LEAVE where  USER_ID = '".$EVECTION_USER_ID."' AND (cast(LEAVE_DATE1 as date) >= '".$EVECTION_DATE1."' AND cast(LEAVE_DATE1 as date) <= '".$EVECTION_DATE2."' OR (cast(LEAVE_DATE1 as date) <= '".$EVECTION_DATE1."' AND cast(LEAVE_DATE2 as date) >= '".$EVECTION_DATE1."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("����"),_("����������ʱ������ʱ���г�ͻ"));
        Button_Back();
        exit;
    }
    
    //��ͬһʱ����Ƿ��ظ��ύ��������
    $sql = "select *from ATTEND_EVECTION where EVECTION_ID!='$EVECTION_ID' AND USER_ID = '".$EVECTION_USER_ID."' AND ((EVECTION_DATE1 >= '".$EVECTION_DATE1."' AND EVECTION_DATE1 <= '".$EVECTION_DATE2."') OR (EVECTION_DATE1 <= '".$EVECTION_DATE1."' AND EVECTION_DATE2 >= '".$EVECTION_DATE1."'))";

    $cursor = exequery(TD::conn(),$sql);

    if($ROW=mysql_fetch_array($cursor)){

        Message(_("����"),_("����ʱ����Ѿ����������"));
        Button_Back();
        exit;
    }
}
$query="update ATTEND_EVECTION set ALLOW='0',STATUS='1',EVECTION_DEST='$EVECTION_DEST',REASON='$REASON',EVECTION_DATE1='$EVECTION_DATE1',EVECTION_DATE2='$EVECTION_DATE2',LEADER_ID='$LEADER_ID' where EVECTION_ID='$EVECTION_ID'";
exequery(TD::conn(),$query);

//---------- �������� ----------
$SMS_CONTENT=$_SESSION["LOGIN_USER_NAME"]._("�ύ�������룬����ʾ��");
$REMIND_URL="attendance/manage/confirm";
if($SMS_REMIND=="on")
   send_sms("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,6,$SMS_CONTENT,$REMIND_URL);  
if($SMS2_REMIND=="on")
  send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,$SMS_CONTENT,6);
  
header("location: ./?connstatus=1");
?>

</body>
</html>
