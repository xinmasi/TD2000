<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");

while(list($KEY, $VALUE) = each($_POST))
{
    $$KEY = trim($VALUE);
}
$REMIND_TIME = date("Y-m-d H:i:s",strtotime($REMIND_TIME." ".$REMIND_TIME_TIME));
$FINISH_TIME = date("Y-m-d H:i:s",strtotime($FINISH_TIME." ".$FINISH_TIME_TIME));

$HTML_PAGE_TITLE = _("新建保存");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
//------------------- 保存 -----------------------
$CUR_TIME=date("Y-m-d H:i:s",time());



if($BEGIN_DATE!="" && !is_date($BEGIN_DATE))
{
   Message("",_("起始日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}

if($END_DATE!="" && !is_date($END_DATE))
{
   Message("",_("结束日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}

if($FINISH_TIME!="" && !is_date_time($FINISH_TIME))
{
   Message("",_("完成时间应为时间型，如：1999-01-01 10:08:10"));
   Button_Back();
   exit;
}

if($REMIND_TIME!="" && !is_date_time($REMIND_TIME))
{
   Message("",_("提醒时间应为时间型，如：1999-01-01 10:08:10"));
   Button_Back();
   exit;
}


$ADD_TIME=date("Y-m-d H:i:s");
$CAL_ID=1;
$USER_ARRAY=explode(",",$USER_ID);
foreach($USER_ARRAY as $USER_ID)
{
   if($USER_ID=="")
      continue;
   $query="INSERT INTO TASK(USER_ID,TASK_NO,TASK_TYPE,TASK_STATUS,COLOR,IMPORTANT,SUBJECT,EDIT_TIME,BEGIN_DATE,END_DATE,CONTENT,RATE,FINISH_TIME,TOTAL_TIME,USE_TIME,CAL_ID,MANAGER_ID,ADD_TIME)
   VALUES ('$USER_ID', '$TASK_NO', '$TASK_TYPE', '$TASK_STATUS', '$COLOR', '$IMPORTANT', '$SUBJECT', '$CUR_TIME', '$BEGIN_DATE', '$END_DATE', '$CONTENT', '$RATE', '$FINISH_TIME', '$TOTAL_TIME', '$USE_TIME', '$CAL_ID', '".$_SESSION["LOGIN_USER_ID"]."','$ADD_TIME');";
   exequery(TD::conn(),$query);
   $TASK_ID=mysql_insert_id();

   //------- 事务提醒 --------
   if($REMIND_TIME!="" && $SMS_REMIND=="on")
   {
     $REMIND_URL="1:calendar/task/note.php?TASK_ID=".$TASK_ID;
     
     $MSG = sprintf(_("请查看%s安排的任务！"), $_SESSION["LOGIN_USER_NAME"]);
     $SMS_CONTENT=$MSG."\n"._("标题：").csubstr($SUBJECT,0,50);
     send_sms($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID,5,$SMS_CONTENT,$REMIND_URL,$TASK_ID);
   }
   
   if($REMIND_TIME!="" && $SMS2_REMIND=="on")
   {
      $SMS_CONTENT=_("OA任务:").$SUBJECT;
      send_mobile_sms_user($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID,$SMS_CONTENT,5);
   }
}
Message("",_("保存成功"));
//Button_Back();
?>
<center><input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="parent.close();"></center>
<script Language="JavaScript">
//window.parent.opener.location.reload();
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

</script>
</body>
</html>
