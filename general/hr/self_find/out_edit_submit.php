<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("修改外出记录");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>


<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());

//----------- 合法性校验 ---------
if($OUT_TIME1!="")
{
	$OUT_TIME11="1999-01-02 ".$OUT_TIME1.":02";
  $TIME_OK=is_date_time($OUT_TIME11);

  if(!$TIME_OK)
  { 
  	Message(_("错误"),_("时间有问题，请核实"));
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
  	Message(_("错误"),_("时间有问题，请核实"));
    Button_Back();
    exit;
  }
}

if(compare_date_time($OUT_TIME11,$OUT_TIME22)>=0)
{ 
	 Message(_("错误"),_("外出结束时间应晚于外出开始时间"));
   Button_Back();
   exit;
}

$SUBMIT_TIME=$OUT_DATE." ".$OUT_TIME1;
$query="update ATTEND_OUT set SUBMIT_TIME='$SUBMIT_TIME',OUT_TYPE='$OUT_TYPE',OUT_TIME1='$OUT_TIME1',OUT_TIME2='$OUT_TIME2' where OUT_ID='$OUT_ID'";
exequery(TD::conn(),$query);

Message(_("提示"),_("修改成功"));
?>
<center>
	<input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="javascript:window.close();">
</center>	
</body>
</html>
