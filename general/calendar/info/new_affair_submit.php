<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
if($AFF_ID=="")
   $WIN_TITLE=_("新建周期性事务");
else
   $WIN_TITLE=_("编辑周期性事务");
//echo $AFF_ID;

while(list($KEY, $VALUE) = each($_POST))
{
    $$KEY = trim($VALUE);
}

$BEGIN_TIME_TIME = date("H:i:s",strtotime($BEGIN_TIME_TIME));
$END_TIME_TIME = date("H:i:s",strtotime($END_TIME_TIME));
$REMIND_TIME2 = date("H:i:s",strtotime($REMIND_TIME2));
$REMIND_TIME3 = date("H:i:s",strtotime($REMIND_TIME3));
$REMIND_TIME4 = date("H:i:s",strtotime($REMIND_TIME4));
$REMIND_TIME5 = date("H:i:s",strtotime($REMIND_TIME5));
$REMIND_TIME6 = date("H:i:s",strtotime($REMIND_TIME6));

if($all_day_check == 'on')
{
    $BEGIN_TIME_TIME = '00:00:00';
    $END_TIME_TIME = '23:59:59';
}
if($end_day_check != 'on')
{
    $END_TIME = '';
    $END_TIME_TIME = '';
}

$HTML_PAGE_TITLE = $WIN_TITLE;
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
$CUR_DATE_TIME=date("Y-m-d H:i:s",time());
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("H:i:s",time());
$BEGIN_TIME=$CAL_TIME;

//------------------- 保存 -----------------------
if($BEGIN_TIME!="" && !is_date($BEGIN_TIME))
{
   Message("",_("起始日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}

if($END_TIME!="" && !is_date($END_TIME))
{
   Message("",_("结束日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}

if($END_TIME!=""&&$CAL_TIME >= $END_TIME)
{
   Message(_("错误"),_("开始日期晚于结束日期！"));
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
      Message("",_("提醒时间应为时间型，如：10:20:10"));
      Button_Back();
      exit;
   }
}
else
   $REMIND_TIME=$CUR_TIME;
$ADD_TIME=date("Y-m-d H:i:s"); 
$BEGIN_TIME=strtotime($BEGIN_TIME);
$END_TIME=strtotime($END_TIME);
if($AFF_ID=="")
{      
   $USER_ARRAY=explode(",",$USER_ID);
   foreach($USER_ARRAY as $USER_ID)
   {
      if($USER_ID=="")
         continue;
     
      $query="insert into AFFAIR(USER_ID,BEGIN_TIME,END_TIME,TYPE,REMIND_DATE,REMIND_TIME,CONTENT,SMS_REMIND,SMS2_REMIND,MANAGER_ID,CAL_TYPE,ADD_TIME,TAKER,BEGIN_TIME_TIME,END_TIME_TIME) values ('$USER_ID','$BEGIN_TIME','$END_TIME','$TYPE','$REMIND_DATE','$REMIND_TIME','$CONTENT','$SMS_REMIND','$SMS2_REMIND','".$_SESSION["LOGIN_USER_ID"]."','1','$ADD_TIME','$TAKER','$BEGIN_TIME_TIME','$END_TIME_TIME')";
      exequery(TD::conn(),$query);
   }
}
else
{
	 $AFF_ID_ARRAY=explode(",",$AFF_ID_STR);
	 foreach($AFF_ID_ARRAY as $AFF_ID_STR)
   {
      if($AFF_ID_STR=="")
         continue;
      $query="update AFFAIR set BEGIN_TIME='$BEGIN_TIME',END_TIME='$END_TIME',TYPE='$TYPE',REMIND_DATE='$REMIND_DATE',REMIND_TIME='$REMIND_TIME',CONTENT='$CONTENT',SMS_REMIND='$SMS_REMIND',SMS2_REMIND='$SMS2_REMIND',LAST_REMIND='',MANAGER_ID='".$_SESSION["LOGIN_USER_ID"]."',CAL_TYPE='1',ADD_TIME='$ADD_TIME',TAKER='$TAKER',BEGIN_TIME_TIME='$BEGIN_TIME_TIME',END_TIME_TIME='$END_TIME_TIME' where AFF_ID='$AFF_ID_STR'";
      exequery(TD::conn(),$query);
  }
}
affair_sms();

if($FROM!=1)
{
	Message("",_("保存成功"));

?>
<center><input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="parent.close();"></center>
<script Language="JavaScript">
<? 
	if($AFF_ID=="")
	{
	   //echo "window.parent.opener.location.reload();"; 
?>
	   var url_ole=window.parent.opener.location.href;
    	var url_search=window.parent.opener.location.search;
		if(url_ole.indexOf("?IS_MAIN=1")>0 || url_ole.indexOf("&IS_MAIN=1")>0)
			window.parent.opener.location.reload();
		else
		{
			if(url_search=="")
				window.parent.opener.location.href=url_ole+"?IS_MAIN=1";
			else
				window.parent.opener.location.href=url_ole+"&IS_MAIN=1"; 
		} 	 
	<?
	}
	else
	{
	?>
	  var url_ole=window.opener.location.href;
  	  var url_search=window.opener.location.search;
	  if(url_ole.indexOf("?IS_MAIN=1")>0 || url_ole.indexOf("&IS_MAIN=1")>0)
		 window.opener.location.reload();
	 else
	 {
		if(url_search=="")
			window.opener.location.href=url_ole+"?IS_MAIN=1";
		else
			window.opener.location.href=url_ole+"&IS_MAIN=1"; 
	 } 	
<?	}?>
</script>
<?
} 
else{
?>
<script Language="JavaScript">
<? 
if($AFF_ID!="")
{
	echo "window.close();";
?>
 var url_ole=window.opener.location.href;
  	var url_search=window.opener.location.search;
	if(url_ole.indexOf("?IS_MAIN=1")>0 || url_ole.indexOf("&IS_MAIN=1")>0)
		window.opener.location.reload();
	else
	{
		if(url_search=="")
			window.opener.location.href=url_ole+"?IS_MAIN=1";
		else
			window.opener.location.href=url_ole+"&IS_MAIN=1"; 
	} 	
//	echo "window.opener.location.reload();";
  // echo "window.opener.opener.location.reload();";

<?}
?>
</script>

<?
}
?>
</body>
</html>
