<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("�޸������¼");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>


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

$SUBMIT_TIME=$OUT_DATE." ".$OUT_TIME1;
$query="update ATTEND_OUT set SUBMIT_TIME='$SUBMIT_TIME',OUT_TYPE='$OUT_TYPE',OUT_TIME1='$OUT_TIME1',OUT_TIME2='$OUT_TIME2' where OUT_ID='$OUT_ID'";
exequery(TD::conn(),$query);

Message(_("��ʾ"),_("�޸ĳɹ�"));
?>
<center>
	<input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="javascript:window.close();">
</center>	
</body>
</html>
