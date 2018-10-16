<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_calendar.php");


while(list($KEY, $VALUE) = each($_POST))
{
    $$KEY = trim($VALUE);
}
$TASK_ID = td_trim($TASK_ID_STR);
$SUBJECT1 = _("任务:").csubstr(strip_tags($SUBJECT),0,38);
$taskinfo = array('id' => $TASK_ID,'title' => $SUBJECT1,'start' => $BEGIN_DATE,'end' => $END_DATE,'type' =>'task');

$newData = array (); 
foreach ( $taskinfo as $key => $value )
{ 
    $newData [$key] = urlencode ( $value );
}
$taskinfo = urldecode ( json_encode ( $newData ) ); 

$REMIND_TIME = date("Y-m-d H:i:s",strtotime($REMIND_TIME." ".$REMIND_TIME_TIME));
$FINISH_TIME = date("Y-m-d H:i:s",strtotime($FINISH_TIME." ".$FINISH_TIME_TIME));

$HTML_PAGE_TITLE = _("编辑保存");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">

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
//批量编辑任务
$TASK_ID_ARRAY=explode(",",$TASK_ID_STR);
for($I=0;$I< count($TASK_ID_ARRAY);$I++)
{
	if ($TASK_ID_ARRAY[$I]=='')
		continue;
	$query="UPDATE TASK SET TASK_NO='$TASK_NO',TASK_TYPE='$TASK_TYPE',TASK_STATUS='$TASK_STATUS',COLOR='$COLOR',IMPORTANT='$IMPORTANT',SUBJECT='$SUBJECT',EDIT_TIME='$EDIT_TIME',BEGIN_DATE='$BEGIN_DATE',END_DATE='$END_DATE',CONTENT='$CONTENT',RATE='$RATE',FINISH_TIME='$FINISH_TIME',TOTAL_TIME='$TOTAL_TIME',USE_TIME='$USE_TIME',CAL_ID='$CAL_ID',ADD_TIME='$ADD_TIME' WHERE TASK_ID = '$TASK_ID_ARRAY[$I]' ";
	exequery(TD::conn(),$query);
	if($TASK_STATUS==3)
	{
	   delete_taskcenter('TASK',$TASK_ID_ARRAY[$I]);
   }
	$sql="select WORK_PLAN_ID,USER_ID from TASK where TASK_ID = '$TASK_ID_ARRAY[$I]' ";//and USER_ID = '".$_SESSION["LOGIN_USER_ID"]."'
	$re=exequery(TD::conn(),$sql);
	if ($ROW=mysql_fetch_array($re))
	{
		$WORK_PLAN_ID=$ROW['WORK_PLAN_ID'];
		$USER_ID1=$ROW['USER_ID'];
	}
	if ($WORK_PLAN_ID!='')
	{
		$sql4="select * from WORK_DETAIL where PLAN_ID='$WORK_PLAN_ID' and WRITER='$USER_ID1'";
		$re3=exequery(TD::conn(),$sql4);
		if ($ROW3=mysql_fetch_array($re3))
		{
			$sql2="select MAX(PERCENT) as PERCENT from WORK_DETAIL where PLAN_ID='$WORK_PLAN_ID' and WRITER='$USER_ID1'";
			$re1=exequery(TD::conn(),$sql2);
			if ($ROW1=mysql_fetch_array($re1))
			{
				$PERCENT=$ROW1['PERCENT'];
			}
			$sql1="update WORK_DETAIL set PROGRESS='$CONTENT',PERCENT='$RATE',WRITE_TIME='$CUR_TIME' WHERE PLAN_ID='$WORK_PLAN_ID' AND WRITER='$USER_ID1' and PERCENT='$PERCENT'";
			exequery(TD::conn(),$sql1);
		}else
		{
			$sql5="insert into WORK_DETAIL (PLAN_ID,WRITE_TIME,PROGRESS,PERCENT,TYPE_FLAG,WRITER,ATTACHMENT_ID,ATTACHMENT_NAME) 
			values ('$WORK_PLAN_ID','$CUR_TIME','','$RATE','0','$USER_ID1','','')";
			exequery(TD::conn(),$sql5);
		}
	}
	$querys="select USER_ID from TASK where TASK_ID='$TASK_ID_ARRAY[$I]'";
	$curson=exequery(TD::conn(),$querys);
	if($ROWS=mysql_fetch_array($curson))
	$USER_ID=$ROWS['USER_ID'];
	if($SMS_REMIND=="on")
	{
		$REMIND_URL="1:calendar/task/note.php?TASK_ID=".$TASK_ID_ARRAY[$I];
			
		$MSG = sprintf(_("请查看%s安排的任务！"), $_SESSION["LOGIN_USER_NAME"]);
		$SMS_CONTENT=$MSG."\n"._("标题：").csubstr($SUBJECT,0,50);
		send_sms($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID,5,$SMS_CONTENT,$REMIND_URL,$TASK_ID_ARRAY[$I]);

	}
	//------- 手机短信提醒 --------
	if($SMS2_REMIND=="on")
	{
		$SMS_CONTENT=_("OA任务:").$SUBJECT;
		send_mobile_sms_user($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID,$SMS_CONTENT,5);
	}
	
}

if($FROM==1)
{
?>
<script Language="JavaScript">
    window.close();
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
</script>     	    
<?    
}
else if($FROM==2)
{
?>
<script Language="JavaScript">
    alert("<?=_('任务修改成功！')?>");
    var task = <?=($taskinfo); ?>;
    
    window.opener.calendar.removeEvents(task.id);
    
    window.opener.calendar.renderEvent(task ,true);
    //window.opener.console.log('update');    
    window.close(); 
</script>   
<?    
}
else 
{
    Message("",_("保存成功"));
?>
<center><button type="button" class="btn" onClick="parent.close();"><?=_("关闭")?></button></center>
	<script Language="JavaScript">
//window.opener.location.reload();

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
</script>
<?
}

?>
</body>
</html>
