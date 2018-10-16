<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_cache.php");
function alert($MSG)
{
   echo '<script>parent.cal_alert("'.str_replace('"', '\"', $MSG).'");</script>';
   exit;
}

$HTML_PAGE_TITLE = _("编辑事务");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$CUR_TIME=date("Y-m-d H:i:s",time());
/*if($CAL_TIME!="")
   $CAL_TIME=substr($CAL_TIME,0,16).":00";
if($END_TIME!="")
   $END_TIME=substr($END_TIME,0,16).":59";*/
$CAL_TIME = date("Y-m-d H:i:s",strtotime($START_DATE." ".$START_TIME));
$END_TIME = date("Y-m-d H:i:s",strtotime($FINISHI_DATE." ".$FINISHI_TIME)); 

//------------------- 保存 -----------------------
if($CAL_TIME=="" || !is_date_time($CAL_TIME))
{
   alert(_("起始时间格式不对，应形如 $CUR_TIME"));
}
if($END_TIME=="" || !is_date_time($END_TIME))
{
   alert(_("结束时间格式不对，应形如 $CUR_TIME"));
}

if($CAL_TIME >= $END_TIME)
{
   alert(_("开始时间晚于结束时间！"));
}

if($BEFORE_DAY != "" && !is_numeric($BEFORE_DAY))
{
   alert(_("提前提醒天数应为整数！"));
}

if($BEFORE_HOUR != "" && !is_numeric($BEFORE_HOUR))
{
   alert(_("提前提醒小时应为整数！"));
}

if($BEFORE_MIN != "" && !is_numeric($BEFORE_MIN))
{
   alert(_("提前提醒分钟应为整数！"));
}

$CONTENT = trim($CONTENT);
if($CONTENT == "")
{
   alert(_("内容不能为空"));
}

//------------------- 保存 -----------------------
$query="select * from CALENDAR where (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OWNER) or USER_ID='".$_SESSION["LOGIN_USER_ID"]."') and CAL_ID='$CAL_ID'";
$cursor= exequery(TD::conn(),$query,true);
if($ROW=mysql_fetch_array($cursor))
{
  $CAL_TIME1=$ROW["CAL_TIME"];
  $CONTENT1=$ROW["CONTENT"];
  $SMS_CONTENT1=csubstr($CONTENT1,0,100);
  $SMS_CONTENT2=$CONTENT1;
  $OVER_STATUS=$ROW["OVER_STATUS"];
}

/*$query="delete from CALENDAR where CAL_ID='$CAL_ID' and (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OWNER) or USER_ID='".$_SESSION["LOGIN_USER_ID"]."')";
exequery(TD::conn(),$query);

delete_remind_sms(5, $_SESSION["LOGIN_USER_ID"], $SMS_CONTENT1, $CAL_TIME1);

$query="delete from SMS2 where SEND_TIME='$CAL_TIME1' and CONTENT like '%$SMS_CONTENT2%' and SEND_FLAG='0'";
exequery(TD::conn(),$query);*/

$BEFORE_DAY=intval($BEFORE_DAY);
$BEFORE_HOUR=intval($BEFORE_HOUR);
$BEFORE_MIN=intval($BEFORE_MIN)==0 ? 10 : intval($BEFORE_MIN);

$BEFORE_REMAIND=$BEFORE_DAY."|".$BEFORE_HOUR."|".$BEFORE_MIN;

//$REMIND_TIME=date("Y-m-d H:i:s",strtotime("- $BEFORE_DAY days - $BEFORE_HOUR hours - $BEFORE_MIN minutes",strtotime($CAL_TIME)));

$REMIND_TIME = date("Y-m-d H:i:s",$ROW["CAL_TIME"]-$BEFORE_DAY*24*3600-$BEFORE_HOUR*3600-$BEFORE_MIN*60);


if($REMIND_TIME < $CUR_TIME)
   $REMIND_TIME = $CUR_TIME;

//..转成时间戳
if($CAL_TIME!="")
   $CAL_TIME=strtotime($CAL_TIME);
if($END_TIME!="")
   $END_TIME=strtotime($END_TIME);
$query = "update CALENDAR set CAL_TIME='$CAL_TIME',END_TIME='$END_TIME',CAL_TYPE='$CAL_TYPE',CAL_LEVEL='$CAL_LEVEL',CONTENT='$CONTENT',OVER_STATUS='$OVER_STATUS',BEFORE_REMAIND='$BEFORE_REMAIND',TAKER='$TAKER',OWNER='$OWNER' where CAL_ID='$CAL_ID'";   
//$query="insert into CALENDAR(USER_ID,CAL_TIME,END_TIME,CAL_TYPE,CAL_LEVEL,CONTENT,OVER_STATUS,BEFORE_REMAIND,TAKER,OWNER) values ('".$_SESSION["LOGIN_USER_ID"]."','$CAL_TIME','$END_TIME','$CAL_TYPE','$CAL_LEVEL','$CONTENT','$OVER_STATUS','$BEFORE_REMAIND','$TAKER','$OWNER')";
exequery(TD::conn(),$query);
//$CAL_ID=mysql_insert_id();
$REMIND_USER=$_SESSION["LOGIN_USER_ID"].",".$OWNER.$TAKER;
//------- 事务提醒 --------
if($SMS_REMIND=="on")
{
  $REMIND_URL="1:calendar/arrange/note.php?CAL_ID=".$CAL_ID;
  $SMS_CONTENT=_("请查看日程安排！")."\n"._("内容：").csubstr($CONTENT,0,100);
  send_sms($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMIND_USER,5,$SMS_CONTENT,$REMIND_URL,$CAL_ID);
}
  include_once("inc/itask/itask.php");
  mobile_push_notification(UserId2Uid($REMIND_USER), $_SESSION["LOGIN_USER_NAME"]._("：")._("请查看日程安排！")._("内容：").csubstr($CONTENT,0,10), "calendar");
if($SMS2_REMIND=="on")
{
   $SMS_CONTENT=_("OA日程安排:").$CONTENT;
   send_mobile_sms_user($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$REMIND_USER,$SMS_CONTENT,5);
}
?>
<?
if($FROM==1)
{
?>
  <script>
  	
var url_ole=window.parent.location.href;
var url_search=window.parent.location.search;

if(url_ole.indexOf("?IS_MAIN=1")>0 || url_ole.indexOf("&IS_MAIN=1")>0)
{
	window.close();
	window.parent.location.reload();
}
else
{  
	window.close();
	if(url_search=="")
		window.parent.location.href=url_ole+"?IS_MAIN=1";
	else
		window.parent.location.href=url_ole+"&IS_MAIN=1";
}
   //window.opener.location.reload();
  //window.close();
 </script>
<?
}else
{
?>
<script>
var url_ole=window.parent.location.href;
if(url_ole.indexOf("?IS_MAIN=1")>0)
{
	window.parent.location.reload();
}
else
{  
	window.parent.location.href=url_ole+"&IS_MAIN=1";
}
//window.parent.location.reload();
</script>
<? }?>
</body>
</html>
