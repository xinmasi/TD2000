<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("新建网络会议");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$BEGIN_TIME=$MEET_YEAR."-".$MEET_MON."-".$MEET_DAY." ".$MEET_HOUR.":".$MEET_MIN.":00";

$SUBJECT1=$SUBJECT;

//------------------- 新建网络会议 -----------------------
$query="insert into NETMEETING(FROM_ID,TO_ID,SUBJECT,BEGIN_TIME,STOP) values ('".$_SESSION["LOGIN_USER_ID"]."','$TO_ID','$SUBJECT','$BEGIN_TIME','0')";
exequery(TD::conn(),$query);
$ROW_ID=mysql_insert_id();

$USR_FILE=MYOA_ATTACH_PATH."netmeeting/".$ROW_ID.".usr";
$STOP_FILE=MYOA_ATTACH_PATH."netmeeting/".$ROW_ID.".stp";
$MSG_FILE=MYOA_ATTACH_PATH."netmeeting/".$ROW_ID.".msg";

if(file_exists($STOP_FILE))
   td_rename($STOP_FILE,$STOP_FILE.".bak");
   
if(file_exists($MSG_FILE))
   td_rename($MSG_FILE,$MSG_FILE.".bak");
   
if(file_exists($USR_FILE))
   td_rename($USR_FILE,$USR_FILE.".bak");

//------- 事务提醒 --------
$SMS_CONTENT=_("请出席网络会议！")."\n"._("议题：").csubstr($SUBJECT1,0,100);

if($SMS_REMIND=="on")
   send_sms($BEGIN_TIME,$_SESSION["LOGIN_USER_ID"],$TO_ID,3,$SMS_CONTENT,$REMIND_URL);

if($SMS2_REMIND=="on")
   send_mobile_sms_user($BEGIN_TIME,$_SESSION["LOGIN_USER_ID"],$TO_ID,$SMS_CONTENT,3);

header("location: ../");
?>

</body>
</html>
