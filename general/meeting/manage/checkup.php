<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/itask/itask.php");
include_once("inc/header.inc.php");
mysql_set_charset(MYOA_DB_CHARSET, TD::conn());
?>

<body class="bodycolor">

<?
/**
 * ��׼����������ҳ��
 * ����׼�Ļ��������ճ�
 * �����Ļ���ɾ���ճ�
 * ɾ���Ļ��鲻ɾ���ճ�
 * 
 */
//��ȡ��������״̬
if($M_STATUS=="")
{
    $M_STATUS == 0;
}

$query  = "SELECT M_STATUS FROM meeting WHERE M_ID='$M_ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
   $M_STATUS1=$ROW["M_STATUS"];
}
//δ��׼�������� ����״̬�Ͳ���׼ԭ��
if($M_STATUS!="")
{
    $query = "UPDATE meeting set M_STATUS='$M_STATUS',REASON='$REASON' WHERE M_ID='$M_ID'";
    exequery(TD::conn(),$query);
}
$query  = "SELECT * FROM meeting WHERE M_ID='$M_ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
    $M_PROPOSER      = $ROW["M_PROPOSER"];
    $M_ATTENDEE      = $ROW["M_ATTENDEE"];
    $RECORDER        = $ROW["RECORDER"];
    $M_DESC          = $ROW["M_DESC"];
    $SMS_REMIND      = $ROW["SMS_REMIND"];//�������ѱ�ʾ
    $SMS2_REMIND     = $ROW["SMS2_REMIND"];
    $M_NAME          = $ROW["M_NAME"];
    $M_ROOM          = $ROW["M_ROOM"];
    $M_START        = $ROW["M_START"];
    $M_START2        = $M_START = $ROW["M_START"];
    $M_END           = $ROW["M_END"];
    $TO_EMAIL        = $ROW["TO_EMAIL"];//��ȡ�ʼ��������� ����1
    $RESEND_LONG     = $ROW["RESEND_LONG"];
    $RESEND_LONG_FEN = $ROW["RESEND_LONG_FEN"];
    $RESEND_SEVERAL  = $ROW["RESEND_SEVERAL"];//���Ѵ���
    $CALENDAR        = $ROW['CALENDAR'];
    $TO_SCOPE        = $ROW['TO_SCOPE'];
    $SECRET_TO_ID    = $ROW['SECRET_TO_ID']; //�鿴��Ա
    $PRIV_ID         = $ROW['PRIV_ID']; //�鿴��ɫ
    $TO_ID         = $ROW['TO_ID']; //�鿴����
    if(!find_id($M_ATTENDEE,$RECORDER))
    {
        $M_ATTENDEE=$M_ATTENDEE.$RECORDER.",";
    }

   if($RESEND_SEVERAL > 4)
      $RESEND_SEVERAL = 4;

   $query  = "SELECT MR_NAME FROM meeting_room WHERE MR_ID='$M_ROOM'";
   $cursor = exequery(TD::conn(),$query);
   if($ROW = mysql_fetch_array($cursor))
   {
       $MR_NAME = $ROW["MR_NAME"];
   }
   $query  = "SELECT USER_NAME FROM user WHERE USER_ID='$M_PROPOSER'";
   $cursor = exequery(TD::conn(),$query);
   if($ROW = mysql_fetch_array($cursor))
   {
       $M_PROPOSER_NAME = $ROW["USER_NAME"];  
   }
      
}

//�鿴��Χ������)����  
if($TO_SCOPE == '1' && ($SECRET_TO_ID != "" || $PRIV_ID != "" || $TO_ID != "") && $M_STATUS == '1'){   //&& $M_STATUS == '1'��׼��ʱ��
    
    if($PRIV_ID != ""){
        $PRIV_NAMES = td_trim(GetPrivNameById($PRIV_ID));
        $query="select USER_ID from user where find_in_set(USER_PRIV_NAME,'$PRIV_NAMES')";;
        $cursor = exequery(TD::conn(),$query);
        while($ROW = mysql_fetch_array($cursor))
        {
           $USER_IDS.= $ROW["USER_ID"].',';  
        }
        $PRIV_ID_ARRAY = explode(",",$PRIV_ID);
        array_pop($PRIV_ID_ARRAY);
        foreach($PRIV_ID_ARRAY as $V)
        {
            $query="select USER_ID from user where USER_PRIV_OTHER like '%$V%'";
            $cursor = exequery(TD::conn(),$query);
            while($ROW = mysql_fetch_array($cursor))
            {
               $USER_IDS.= $ROW["USER_ID"].',';  
            }
        } 
       
    }
    if($TO_ID != ""){
        $TO_ID = td_trim($TO_ID);
        
        $query="select USER_ID from user where find_in_set(DEPT_ID,'$TO_ID')";
       
        $cursor = exequery(TD::conn(),$query);
        while($ROW = mysql_fetch_array($cursor))
        {
           $USER_IDS.= $ROW["USER_ID"].',';  
        }
        $TO_ID_ARRAY = explode(",",$TO_ID);
        
        foreach($TO_ID_ARRAY as $V)
        {
            $query="select USER_ID from user where DEPT_ID_OTHER like '%$V%'";
            $cursor = exequery(TD::conn(),$query);
            while($ROW = mysql_fetch_array($cursor))
            {
               $USER_IDS.= $ROW["USER_ID"].',';  
            }
        } 
        
    }
    if($SECRET_TO_ID != ""){
        $USER_IDS.= $SECRET_TO_ID;
        
    }
    if($TO_ID == 'ALL_DEPT'){
        $query="select USER_ID from user where DEPT_ID != 0";   //��ְ��Ա��������������
        $cursor = exequery(TD::conn(),$query);
        while($ROW = mysql_fetch_array($cursor))
        {
           $USER_IDS.= $ROW["USER_ID"].',';  
        }
    }
    
    $USER_ID_REPETITION = explode(",",$USER_IDS);
    array_pop($USER_ID_REPETITION);
    $USER_ID_REPETITION = array_unique($USER_ID_REPETITION);
    $USER_ID = implode(",",$USER_ID_REPETITION);
    
    $REMIND_URL1="1:meeting/query/meeting_detail.php?M_ID=".$M_ID;
   
    $CONTENT = sprintf(_("%s��%s���ᣬ�������ƣ�%s���������ģ�"),$M_START,$MR_NAME,$M_NAME);
    send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID,"8_1",$M_PROPOSER_NAME.$CONTENT,$REMIND_URL1,$M_ID);
    
    send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$USER_ID,$CONTENT,'8_1');
    
    //�����ʼ�����
    //$M_PROPOSER  = $_SESSION["LOGIN_USER_ID"];
    $TIME    = date("Y-m-d H:i:s");
    $SUBJECT = _("������ģ�").$M_NAME;
    $CONTENT = sprintf(_("%s��%s���ᣬ�������ƣ�%s���������ģ�"),$M_START,$MR_NAME,$M_NAME);
    $CONTENT          = gbk_stripslashes($CONTENT);
    $CONTENT_STRIP    = strip_tags($CONTENT); //����Ǵ洢�������Ѿ�����html��ǩ��
    $COMPRESS_CONTENT = bin2hex(gzcompress($CONTENT));
    //�洢���ݡ�ѹ�����ݴ洢 edit  2012-05-29.....
    $CONTENT_SIZE          = strlen($CONTENT);
    $CONTENT_SIZE1         = strlen($CONTENT_STRIP);  
    $COMPRESS_CONTENT_SIZE = strlen($COMPRESS_CONTENT);
    if($CONTENT_SIZE<($CONTENT_SIZE1+$COMPRESS_CONTENT_SIZE)) //������Ĵ��ڹ��˵�html��ǩ��ѹ������֮��
    {
        $CONTENT_STRIP    = addslashes($CONTENT);
        $COMPRESS_CONTENT = "''";
    }
    else
    {
        $CONTENT_STRIP    = addslashes($CONTENT_STRIP);
        $COMPRESS_CONTENT = '0x'.$COMPRESS_CONTENT;
    }
    $query="INSERT INTO email_body (FROM_ID,TO_ID2,SUBJECT,CONTENT,SEND_FLAG,SEND_TIME,COMPRESS_CONTENT) values ('$M_PROPOSER','$USER_ID','$SUBJECT','$CONTENT_STRIP','1','".strtotime($TIME)."',$COMPRESS_CONTENT)";
    exequery(TD::conn(),$query);
    $ROW_ID = mysql_insert_id();
    if($TO_EMAIL){
        $USER_ID    = td_trim($USER_ID);
        $MY_ARRAY    = explode(",",$USER_ID);
        $ARRAY_COUNT = sizeof($MY_ARRAY);
        for($I=0;$I< $ARRAY_COUNT;$I++)
        {
            $query = "INSERT INTO email(TO_ID,READ_FLAG,DELETE_FLAG,BOX_ID,BODY_ID,RECEIPT) values ('$MY_ARRAY[$I]','0','0','0','$ROW_ID','0')";
            exequery(TD::conn(),$query);
            $ROW_EMAIL_ID = mysql_insert_id();
            $REMIND_URL="email/inbox/read_email/read_email.php?BOX_ID=0&BTN_CLOSE=1&FROM=1&EMAIL_ID=".$ROW_EMAIL_ID."&BODY_ID=".$ROW_ID;
            $SMS_CONTENT=_("������ҵ��ʼ���")."\n"._("���⣺").csubstr($SUBJECT,0,100);
            send_sms("",$_SESSION["LOGIN_USER_ID"],$MY_ARRAY[$I],"2",$SMS_CONTENT,$REMIND_URL,$ROW_EMAIL_ID);
        }
    }
}

if($M_STATUS=="0")
   $CONTENT=sprintf(_("���Ļ��������ѱ��������������ƣ�%s��"),$M_NAME);
else if($M_STATUS=="1")
   $CONTENT=sprintf(_("���Ļ��������ѱ���׼���������ƣ�%s��"),$M_NAME);
else if($M_STATUS=="3")
   $CONTENT=sprintf(_("���Ļ�������δ����׼���������ƣ�%s��"),$M_NAME);

//�������������δ��׼���鼶��ɾ����������
if($M_STATUS=="0"||$M_STATUS=="3")
{
   $query  = "SELECT M_START,M_ROOM,M_NAME FROM meeting WHERE M_ID='$M_ID'";
   $cursor = exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
        $M_START = $ROW["M_START"];
        $M_ROOM  = $ROW["M_ROOM"];
        $M_NAME  = $ROW["M_NAME"];

      $query1  = "SELECT MR_NAME FROM meeting_room WHERE MR_ID='$M_ROOM'";
      $cursor1 = exequery(TD::conn(),$query1);
      if($ROW1=mysql_fetch_array($cursor1))
         $MR_NAME=$ROW1["MR_NAME"];

      $M_START2   = substr($M_START,0,-3);
      $SRARCH_STR = sprintf(_("%s��%s����"),$M_START2,$MR_NAME);
      //$SRARCH_STR = $M_START2._("��").$MR_NAME._("����");
      $query1  = "SELECT BODY_ID FROM SMS_BODY WHERE CONTENT like '%$SRARCH_STR%'";
      $cursor1 = exequery(TD::conn(),$query1);
      while($ROW1=mysql_fetch_array($cursor1))
      {
         $BODY_ID = $ROW1["BODY_ID"];

         $query2  = "delete FROM SMS_BODY WHERE BODY_ID='$BODY_ID'";
         exequery(TD::conn(),$query2);
         $query2  = "delete FROM SMS WHERE BODY_ID='$BODY_ID'";
         exequery(TD::conn(),$query2);
      }
      //������������
      if($RESEND_LONG_FEN>0 || $RESEND_LONG>0)
      {
          $sql = "SELECT MR_NAME FROM meeting_room WHERE MR_ID=".$M_ROOM;
          $cur = exequery(TD::conn(),$sql);
          if($arr = mysql_fetch_array($cur))
          {
              $MR_NAME = $arr['MR_NAME'];
          }
          $sql1 = "SELECT SMS_ID FROM sms2 WHERE CONTENT like '%$SRARCH_STR%' and unix_timestamp(SEND_TIME)<unix_timestamp('$M_START')";
          $cur1 = exequery(TD::conn(),$sql1);
          while($arr1 = mysql_fetch_array($cur1))
          {
              $SMS_ID = $arr1["SMS_ID"];
              $sql2   = "delete FROM sms2 WHERE SMS_ID='$SMS_ID'";
              exequery(TD::conn(),$sql2); 
          }
      } 
      //���ݻ������idɾ���ճ�
//       $CAL_TIME =strtotime($M_START);
//       $CALENDER_CONTENT =_("����").$M_ID;
//       $query1="SELECT CAL_ID FROM CALENDAR WHERE CONTENT like '$CALENDER_CONTENT%'";
//       $cursor1=exequery(TD::conn(),$query1);
//       while($ROW1=mysql_fetch_array($cursor1))
//       {
//          $CAL_ID=$ROW1["CAL_ID"];
//          $query2="delete FROM CALENDAR WHERE CAL_ID='$CAL_ID'";
//          exequery(TD::conn(),$query2);
//       }
      // ɾ��ԭ�ճ� wrj 
      $query2="DELETE FROM calendar WHERE M_ID='$M_ID' and FROM_MODULE='2'";
      exequery(TD::conn(),$query2);
   }
}
$SYS_PARA_ARRAY = get_sys_para("SMS_REMIND");
$PARA_VALUE     = $SYS_PARA_ARRAY["SMS_REMIND"];   
$SMS_REMIND1    = substr($PARA_VALUE,0,strpos($PARA_VALUE,"|"));
$SMS2_REMIND1   = substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1); 
$SMS2_REMIND1   = substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1,strpos($SMS2_REMIND1,"|"));

$REMIND_URL1="1:meeting/query/meeting_detail.php?M_ID=".$M_ID;
if(($M_STATUS==0 || $M_STATUS==1 || $M_STATUS==3) && find_id($SMS_REMIND1,8) && $M_PROPOSER!="" && $FLAG!=1)
{
    send_sms("",$_SESSION["LOGIN_USER_ID"],$M_PROPOSER,"8_1",$CONTENT,$REMIND_URL1,$M_ID);
}
  
$M_START = substr($M_START,0,16);
//$CONTENT=_("֪ͨ����").$M_START._("��").$MR_NAME._("���ᣬ�������ƣ�").$M_NAME;
$CONTENT = sprintf(_("֪ͨ����%s��%s���ᣬ�������ƣ�%s"),$M_START,$MR_NAME,$M_NAME);
if($M_STATUS=="1" && ($SMS_REMIND=="1" && find_id($SMS_REMIND1,"8_1")) && $M_ATTENDEE!="" && $IS_APPROVE!="2")
{
    send_sms("",$_SESSION["LOGIN_USER_ID"],$M_ATTENDEE,"8_1",$M_PROPOSER_NAME.$CONTENT,$REMIND_URL1,$M_ID);
    //send_sms("",$M_PROPOSER,$M_ATTENDEE,8,$M_PROPOSER_NAME.$CONTENT,$REMIND_URL1);
     /*$WX_OPTIONS = array(
                            "module" => "meeting",
                            "module_action" => "meeting.read",
                            "user" => $M_ATTENDEE,
                            "description" =>strip_tags($M_DESC),
                            "content" =>$CONTENT,
                            "params" => array(
                                "meeting_id" => $M_ID
                            )
                           );
                        WXQY_MEETING($WX_OPTIONS);*/
}

if($M_STATUS=="1" && ($SMS2_REMIND=="1" && find_id($SMS2_REMIND1,"8_1")) && $M_ATTENDEE!="")
{
    
    send_mobile_sms_user("",$M_PROPOSER,$M_ATTENDEE,$M_PROPOSER_NAME.$CONTENT,"8_1");
   
}
   
if($M_STATUS=="0" && $M_ATTENDEE!="")
{
    $REMIND_URL2 = "1:meeting/query/meeting_detail.php?flag=1&M_ID=".$M_ID;
     $msg=sprintf(_("����%s�Ѿ���ȡ����"),$M_NAME);
     if(find_id($SMS_REMIND1,"8_1"))
      send_sms("",$M_PROPOSER,$M_ATTENDEE,"8_1",$msg,$REMIND_URL2,$M_ID);
     if(find_id($SMS2_REMIND1,8) && $SMS2_REMIND == 1)
      send_mobile_sms_user("",$M_PROPOSER,$M_ATTENDEE,$msg,8);
}
//�������� �����ճ�
// if($M_STATUS=="0")
// {
//       $CALENDER_CONTENT =_("����").$M_ID;
//       $query1="SELECT CAL_ID FROM CALENDAR WHERE CONTENT like '$CALENDER_CONTENT%'";
//       $cursor1=exequery(TD::conn(),$query1);
//       while($ROW1=mysql_fetch_array($cursor1))
//       {
//          $CAL_ID2=$ROW1["CAL_ID"];   
//          $query2="delete FROM CALENDAR WHERE M_ID='$M_ID'";
//          exequery(TD::conn(),$query2);
//       }
// }

if(($RESEND_LONG > 0 || $RESEND_LONG_FEN > 0 )&& $RESEND_SEVERAL > 0 && $M_START2!="NULL" && $M_ATTENDEE!="" && $M_STATUS=="1")
{
    $NUM=($RESEND_LONG*60+$RESEND_LONG_FEN)/$RESEND_SEVERAL;
    for($I=0;$I < $RESEND_SEVERAL;$I++)
    {
        $SEND_TIME    = strtotime($M_START2) - $RESEND_LONG*3600 - $RESEND_LONG_FEN*60 + $I*$NUM*60;
        $SEND_TIME    = date("Y-m-d H:i:s",$SEND_TIME);
        $M_START2_TEM = date("Y-m-d H:i",strtotime($M_START2));
        $msg1         = sprintf(_("%s��%s���ᣬ�밴ʱ�μӡ�"),$M_START2_TEM,$MR_NAME);
        
        send_sms($SEND_TIME,$M_PROPOSER,$M_ATTENDEE,"8_1",$msg1,$REMIND_URL1,$M_ID);
        if(find_id($SMS2_REMIND1,"8_1") && $SMS2_REMIND == 1)
              send_mobile_sms_user($SEND_TIME,$M_PROPOSER,$M_ATTENDEE,$msg1,"8_1"); 
   }
}
$MY_ARRAY    = td_trim($M_ATTENDEE);
$MY_ARRAY    = explode(",",$MY_ARRAY);
if($CALENDAR!="" && $M_STATUS=="1")
{
   $CONTENT     = _("����:").$M_NAME;//$M_ID._("��")
   $ARRAY_COUNT = sizeof($MY_ARRAY);
   $URL         = '/general/meeting/query/meeting_detail.php?M_ID='.$M_ID;
   for($I=0;$I<$ARRAY_COUNT;$I++)
   {
      $query = "INSERT INTO calendar(USER_ID,CAL_TIME,END_TIME,CAL_TYPE,CAL_LEVEL,CONTENT,OVER_STATUS,FROM_MODULE,URL,M_ID) values ('$MY_ARRAY[$I]','".strtotime($M_START2)."','".strtotime($M_END)."','1','1','$CONTENT','0','2','$URL','$M_ID')";
      exequery(TD::conn(),$query);
   }
}

//2011-06-09 lp �����׼ͨ����֪ͨEMIAL
if($M_STATUS=="1" && $TO_EMAIL=="1")
{
    $TIME    = date("Y-m-d H:i:s");
    $SUBJECT = _("���飺").$M_NAME;
    $CONTENT = sprintf(_("֪ͨ����%s��%s���ᣬ�������ƣ�%s"),$M_START,$MR_NAME,$M_NAME);
    //$CONTENT=_("֪ͨ����").$M_START._("��").$MR_NAME._("���ᣬ�������ƣ�").$M_NAME;
    $CONTENT          = gbk_stripslashes($CONTENT);
    $CONTENT_STRIP    = strip_tags($CONTENT); //����Ǵ洢�������Ѿ�����html��ǩ��
    $COMPRESS_CONTENT = bin2hex(gzcompress($CONTENT));
    //�洢���ݡ�ѹ�����ݴ洢 edit  2012-05-29.....
    $CONTENT_SIZE          = strlen($CONTENT);
    $CONTENT_SIZE1         = strlen($CONTENT_STRIP);  
    $COMPRESS_CONTENT_SIZE = strlen($COMPRESS_CONTENT);
    if($CONTENT_SIZE<($CONTENT_SIZE1+$COMPRESS_CONTENT_SIZE)) //������Ĵ��ڹ��˵�html��ǩ��ѹ������֮��
    {
        $CONTENT_STRIP    = addslashes($CONTENT);
        $COMPRESS_CONTENT = "''";
    }
    else
    {
        $CONTENT_STRIP    = addslashes($CONTENT_STRIP);
        $COMPRESS_CONTENT = '0x'.$COMPRESS_CONTENT;
    }
    $query="INSERT INTO email_body (FROM_ID,TO_ID2,SUBJECT,CONTENT,SEND_FLAG,SEND_TIME,COMPRESS_CONTENT) values ('$M_PROPOSER','$M_ATTENDEE','$SUBJECT','$CONTENT_STRIP','1','".strtotime($TIME)."',$COMPRESS_CONTENT)";
    exequery(TD::conn(),$query);
    $ROW_ID      = mysql_insert_id();
    $MY_ARRAYS    = td_trim($M_ATTENDEE);
    $MY_ARRAYS    = explode(",",$MY_ARRAYS);
    $ARRAY_COUNT = sizeof($MY_ARRAYS);
    
    for($I=0;$I< $ARRAY_COUNT;$I++)
    {
         $query = "INSERT INTO email(TO_ID,READ_FLAG,DELETE_FLAG,BOX_ID,BODY_ID,RECEIPT) values ('$MY_ARRAYS[$I]','0','0','0','$ROW_ID','0')";
         exequery(TD::conn(),$query);
         $ROW_EMAIL_ID = mysql_insert_id();
         $REMIND_URL="email/inbox/read_email/read_email.php?BOX_ID=0&BTN_CLOSE=1&FROM=1&EMAIL_ID=".$ROW_EMAIL_ID."&BODY_ID=".$ROW_ID;
         $SMS_CONTENT=_("������ҵ��ʼ���")."\n"._("���⣺").csubstr($SUBJECT,0,100);      
         send_sms("",$_SESSION["LOGIN_USER_ID"],$MY_ARRAYS[$I],"2",$SMS_CONTENT,$REMIND_URL,$ROW_EMAIL_ID);
    }
      
}

if($IS_APPROVE=="2")
{
    Message(_("��ʾ"),_("�������뱣��ɹ���"));
    Button_Back();
}
else
header("location: manage.php?M_STATUS=$M_STATUS1");
?>
</body>
</html>
