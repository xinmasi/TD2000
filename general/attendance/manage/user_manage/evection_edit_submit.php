<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("�޸ĳ����¼");
include_once("inc/header.inc.php");
?>


<script src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>


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

$query="update ATTEND_EVECTION set REASON='$REASON',EVECTION_DEST='$EVECTION_DEST',EVECTION_DATE1='$EVECTION_DATE1',EVECTION_DATE2='$EVECTION_DATE2' where EVECTION_ID='$EVECTION_ID'";
$cursor = exequery(TD::conn(),$query);
if($cursor != false) {	
?>
<script>
	TJF_window_close();
//	window.opener.location.reload();
</script>	
<?
}
?>
</body>
</html>
