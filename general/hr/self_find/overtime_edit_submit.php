<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("�Ӱ�ȷ��");
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

$query="update ATTENDANCE_OVERTIME set OVERTIME_HOURS='$OVERTIME_HOURS2',CONFIRM_TIME='$CUR_TIME',CONFIRM_VIEW='$CONFIRM_VIEW',START_TIME='$START_TIME',END_TIME='$END_TIME',OVERTIME_CONTENT='$OVERTIME_CONTENT' where OVERTIME_ID='$OVERTIME_ID'";
exequery(TD::conn(),$query);
Message(_("��ʾ"),_("�����ɹ�"));
?>
<center>
	<input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="javascript:window.close();">
</center>	
</body>
</html>
