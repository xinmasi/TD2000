<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("������־����");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
//----------- �Ϸ���У�� ---------
if($W_START!="")
{
  $TIME_OK=is_date($W_START);

  if(!$TIME_OK)
  { Message(_("����"),_("��ʼʱ���ʽ���ԣ�Ӧ���� 1999-1-2"));
    Button_Back();
    exit;
  }
}

if($W_END!="")
{
   $TIME_OK=is_date($W_END);

   if(!$TIME_OK)
   { Message(_("����"),_("����ʱ���ʽ���ԣ�Ӧ���� 1999-1-2"));
     Button_Back();
     exit;
   }
}

if($W_START!="" && $W_END!="" && compare_date_time($W_END,$W_START)<=0)
{
   Message(_("����"),_("��ʼʱ�䲻�ܴ��ڽ���ʱ�䣡"));
   Button_Back();
   exit;
}
if($DAYS!="" && !is_numeric($DAYS))
{
   Message(_("����"),_("������Χ����Ϊ������"));
   Button_Back();
   exit;
}
$PARA_VALUE=$W_START.",".$W_END.",".intval(abs($DAYS));

if(!isset($LOCK_SHARE)){
   $LOCK_SHARE = 0;
}

if(!isset($IS_COMMENTS)){
   $IS_COMMENTS = 0;
}

if(!isset($ALL_SHARE)){
   $ALL_SHARE = 0;
}
set_sys_para(array("LOCK_TIME" =>"$PARA_VALUE","IS_COMMENTS"=>"$IS_COMMENTS","LOCK_SHARE"=>"$LOCK_SHARE","ALL_SHARE"=>"$ALL_SHARE","DIARY_WORK_LOG_FORMAT"=>"$DIARY_WORK_LOG_FORMAT"));
Message(_("��ʾ"),_("���óɹ���"));
Button_Back();
?>

</body>
</html>
