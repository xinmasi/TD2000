<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_sms1.php");
include_once("inc/header.inc.php");
?>
<script type="text/javascript" src="/static/js/utility.js"></script>
<script>

function close_this_new()
{
  TJF_window_close();
  //window.opener.document.location.reload();
}
</script>
<body class="bodycolor">
<?

$M_ID=intval($M_ID);
$query="update MEETING set SUMMARY_STATUS='2' where M_ID='$M_ID'";
exequery(TD::conn(),$query);
$M_ID=intval($M_ID);
$query1="select M_NAME,M_TYPE from MEETING where M_ID='$M_ID'";
$cursor= exequery(TD::conn(),$query1);
if($ROW=mysql_fetch_array($cursor))
{
   $M_NAME=$ROW["M_NAME"];
   $M_TYPE=$ROW["M_TYPE"];
}
$query = "select M_ATTENDEE from MEETING where M_ID='$M_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $M_ATTENDEE=td_trim($ROW["M_ATTENDEE"]);
}


$M_ATTENDEE_ARRAY = explode(',',$M_ATTENDEE);
foreach($M_ATTENDEE_ARRAY as $value)
{
   if(find_id($COPY_TO_ID,$value))
      continue;
   else
      $COPY_TO_ID.= $value.",";
}

$COPY_TO_ID = td_trim($COPY_TO_ID);
if($SMS_REMIND=="on")
{
    $REMIND_URL1 = "1:/meeting/apply/review.php?M_ID=$M_ID";
    send_sms("",$_SESSION["LOGIN_USER_ID"],$COPY_TO_ID,804,sprintf(_("%s会议的会议纪要！"),$M_NAME),$REMIND_URL1,$M_ID);
}
if($SMS2_REMIND=="on")
   send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$COPY_TO_ID,sprintf(_("%s会议的会议纪要！"),$M_NAME),804);
//会议纪要需要审批
$SUMMARY_APPROVE_ARRAY = get_sys_para("SUMMARY_APPROVE",false);
if($M_TYPE == "1" && $SUMMARY_APPROVE_ARRAY["SUMMARY_APPROVE"] == "1"){
    include_once ("../manage/meeting_funcs.class.php");
    //给查阅人员发送事务提醒以及电子邮件提醒
    $Meeting = new MeetingFuncs($M_ID);
    $Meeting->EmailToAllowUserIds($M_ID,$_SESSION["LOGIN_USER_ID"]);
}
Message("",_("会议纪要发布成功!"));
?>

	 <center><input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="close_this_new();"></center>

</body>
</html>
