<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms2.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("�����ֻ�����");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/mobile_sms.gif" WIDTH="19" HEIGHT="17"><span class="big3"> <?=_("�ֻ����ŷ������")?></span>
    </td>
  </tr>
</table>

<br>

<?
if($SEND_TIME!="" && !is_date_time($SEND_TIME))
{
    Message(_("����"),_("����ʱ���ʽ���ԣ�Ӧ���� 1999-1-2 08:00:00"));
    Button_Back();
    exit;
}

if($TO_ID!="")
   send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$TO_ID,$CONTENT,0);

//if(find_id($TO_ID,$_SESSION["LOGIN_USER_ID"])&&$_SESSION["LOGIN_USER_PRIV"]!=1)
//   Message("",_("��OA����Ա���ֻ����Ų��ܷ����Լ�"));

$TO_ID1=str_replace("\r\n",",",$TO_ID1);
$TO_ID1=str_replace("��",",",$TO_ID1);
$TO_ID1=trim($TO_ID1);
if($TO_ID1!="")
{
   //�������
   $CENSOR=censor($CONTENT,"2");
   if($CENSOR == "BANNED")
   {
      $CENSOR_FLAG=1;
      Button_Back();
      exit;
   }
   else if($CENSOR == "MOD")
   {
      $CENSOR_FLAG=1;
      $CENSOR_DATA=array();
      $CENSOR_DATA["FROM_ID"]=$_SESSION["LOGIN_USER_ID"];
      $CENSOR_DATA["PHONE1"]=$TO_ID1;
      $CENSOR_DATA["SEND_TIME"]=$SEND_TIME;
      $CENSOR_DATA["CONTENT"]=$CONTENT;
      censor_data("2", $CENSOR_DATA);
   }
   else
   {
      $CONTENT=$CENSOR;
     // send_mobile_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$TO_ID1,$CONTENT);
      $ERROR_FLAG=send_mobile_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$TO_ID1,$CONTENT);
   }
}

if($CENSOR_FLAG!=1 && $ERROR_FLAG=="" )
{
   Message("",_("�������ύ�����ŷ����������ں�̨���з��ͣ������Լ���������������"));
}


$BEGIN_DATE=date("Y-m-d 00:00:00",time());
?>

<br>
<div align="center">
  <input type="button" name="button1" value="<?=_("�������ֻ�����")?>" class="BigButton" onClick="form1.submit();">&nbsp;
  <input type="button" name="button1" value="<?=_("�鿴����״̬")?>" class="BigButton" onClick="location='../send_manage/search.php?SEND_FLAG=ALL&BEGIN_DATE=<?=$BEGIN_DATE?>&END_DATE=&DELETE_FLAG=0'">
</div>

<form action="index.php" method="post" name="form1">
  <input type="hidden" id="TO_ID" name="TO_ID" value="<?=$TO_ID?>">
  <input type="hidden" id="TO_ID1" name="TO_ID1" value="<?=$TO_ID1?>">
  <input type="hidden" id="TO_NAME" name="TO_NAME" value="<?=$TO_NAME?>">
  <input type="hidden" id="CONTENT" name="CONTENT" value="<?=$CONTENT?>">
</form>
</body>
</html>
