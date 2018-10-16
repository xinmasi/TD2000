<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("修改出差记录");
include_once("inc/header.inc.php");
?>


<script src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>


<body class="bodycolor">
<?
//----------- 合法性校验 ---------
if($EVECTION_DATE1!="")
{
  $TIME_OK=is_date($EVECTION_DATE1);

  if(!$TIME_OK)
  { Message(_("错误"),_("出差开始日期格式不对，应形如 1999-1-2"));
    Button_Back();
    exit;
  }
}

if($EVECTION_DATE2!="")
{
  $TIME_OK=is_date($EVECTION_DATE2);

  if(!$TIME_OK)
  { Message(_("错误"),_("出差结束日期格式不对，应形如 1999-1-2"));
    Button_Back();
    exit;
  }
}

if(compare_date($EVECTION_DATE1,$EVECTION_DATE2)==1)
{ Message(_("错误"),_("出差开始日期不能晚于出差结束日期"));
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
