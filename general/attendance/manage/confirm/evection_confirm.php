<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("������ʾ");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$CUR_DATE=date("Y-m-d H:i:s",time());
if($CONFIRM==2)
   $query="update ATTEND_EVECTION set ALLOW='$CONFIRM',NOT_REASON='$NOT_REASON',HANDLE_TIME='$CUR_DATE' where EVECTION_ID='$EVECTION_ID'";
else
   $query="update ATTEND_EVECTION set ALLOW='$CONFIRM',HANDLE_TIME='$CUR_DATE' where EVECTION_ID='$EVECTION_ID'";
exequery(TD::conn(),$query);

//---------- �������� ----------
$query="select * from SYS_PARA where PARA_NAME='SMS_REMIND'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $PARA_VALUE=$ROW["PARA_VALUE"];
$SMS_REMIND1=substr($PARA_VALUE,0,strpos($PARA_VALUE,"|"));
//$SMS2_REMIND1=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);
$SMS2_REMIND1_TMP=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);
$SMS2_REMIND1=substr($SMS2_REMIND1_TMP,0,strpos($SMS2_REMIND1_TMP,"|"));

if($CONFIRM==1)
   $SMS_CONTENT=_("���ĳ��������ѱ���׼��");
else
   $SMS_CONTENT=_("���ĳ�������δ����׼��");

$REMIND_URL="attendance/personal/evection";
if(find_id($SMS_REMIND1,6))
   send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID,6,$SMS_CONTENT,$REMIND_URL);

if($MOBILE_FLAG=="1")
{ 
   if($CONFIRM==1)
      $SMS_CONTENT=_("���ĳ��������ѱ���׼��");
   else
      $SMS_CONTENT=_("���ĳ�������δ����׼��");
      
   if(find_id($SMS2_REMIND1,6))         
      send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$USER_ID,$SMS_CONTENT,6);
}

if($CONFIRM==2)
{
	 Message(_("��ʾ"),_("�����ɹ�"));
?>
<br>
<script>
   	if(opener.location.href.indexOf("connstatus") < 0 ){
		opener.location.href = opener.location.href+"?connstatus=1";
	}else{
		opener.location.reload();
	}
</script>
<center>
	<input type="button" class="BigButton" value="<?=_("�ر�")?>" onClick="window.close();" title="<?=_("�رմ���")?>">
</center>
<?
}
else
   header("location: index.php?connstatus=1");
?>

</body>
</html>
