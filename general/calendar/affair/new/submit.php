<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("�½�����");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
//------------------- ���� -----------------------
$CUR_DATE_TIME=date("Y-m-d H:i:s",time());
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("H:i:s",time());

$CUR_TIME1=date("H:i:00",time());
$CUR_END_TIME=date("H:i:00",time()+3600);

if($BEGIN_TIME!="" && !is_date($BEGIN_TIME))
{
   Message("",_("��ʼ����ӦΪ�����ͣ��磺1999-01-01 "));
   Button_Back();
   exit;
}
else if($BEGIN_TIME=="")
   $BEGIN_TIME=$CUR_DATE_TIME;
if($BEGIN_TIME!="") 
   $BEGIN_TIME=strtotime($BEGIN_TIME);
//��ʼʱ��Ĭ��Ϊ��ǰʱ��
if($BEGIN_TIME_TIME!="" && !is_time($BEGIN_TIME_TIME))
{
   Message("",_("��ʼʱ��ӦΪʱ���ͣ��磺10:20:10"));
   Button_Back();
   exit;
}
	
if($END_TIME!="" && !is_date($END_TIME))
{
   Message("",_("��������ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}
if($END_TIME_TIME!="" && !is_time($END_TIME_TIME))
{
   Message("",_("����ʱ��ӦΪʱ���ͣ��磺10:20:10"));
   Button_Back();
   exit;
}
if($END_TIME!="")
   $END_TIME=strtotime($END_TIME);

$CONTENT = trim($CONTENT);
if($CONTENT == "")
{
   Message("",_("���ݲ���Ϊ��"));
   Button_Back();
   exit;
}

$DATE_VAR="REMIND_DATE".$TYPE;
$TIME_VAR="REMIND_TIME".$TYPE;

$REMIND_DATE=$$DATE_VAR;
$REMIND_TIME=$$TIME_VAR;

if($TYPE=="5")
   $REMIND_DATE=$REMIND_DATE5_MON."-".$REMIND_DATE5_DAY;

if($SMS_REMIND=="on")
  $SMS_REMIND='1';
else
  $SMS_REMIND='';

if($SMS2_REMIND=="on")
  $SMS2_REMIND='1';
else
  $SMS2_REMIND='';
if($REMIND_TIME!="")
{
   if(!is_time($REMIND_TIME))
   {
      Message("",_("����ʱ��ӦΪʱ���ͣ��磺10:20:10"));
      Button_Back();
      exit;
   }
}
else
   $REMIND_TIME=$CUR_TIME;
$ADD_TIME=date("Y-m-d H:i:s");

$query="insert into AFFAIR(USER_ID,BEGIN_TIME,END_TIME,TYPE,REMIND_DATE,REMIND_TIME,CONTENT,SMS_REMIND,SMS2_REMIND,CAL_TYPE,ADD_TIME,TAKER,BEGIN_TIME_TIME,END_TIME_TIME) values ('".$_SESSION["LOGIN_USER_ID"]."','$BEGIN_TIME','$END_TIME','$TYPE','$REMIND_DATE','$REMIND_TIME','$CONTENT','$SMS_REMIND','$SMS2_REMIND','$CAL_TYPE','$ADD_TIME','$TAKER','$BEGIN_TIME_TIME','$END_TIME_TIME')";
exequery(TD::conn(),$query);
affair_sms();

header("location: ../?IS_MAIN=1")
?>

</body>
</html>
