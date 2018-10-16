<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
  //-- 删除文件 --
  $MSG_FILE=MYOA_ATTACH_PATH."netmeeting/".$MEET_ID.".msg";
  $STOP_FILE=MYOA_ATTACH_PATH."netmeeting/".$MEET_ID.".stp";
  $USR_FILE=MYOA_ATTACH_PATH."netmeeting/".$MEET_ID.".usr";
  if(file_exists($MSG_FILE))
  {
	unlink($MSG_FILE);
  }
  if(file_exists($STOP_FILE))
  {
	unlink($STOP_FILE);
  }
  if(file_exists($USR_FILE))
  {
	unlink($USR_FILE);
  }


  //-- 删除数据库记录 --
  $query="select * from NETMEETING where MEET_ID='$MEET_ID'";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
  {
    $BEGIN_TIME=$ROW["BEGIN_TIME"];
    $SUBJECT=$ROW["SUBJECT"];

    $SMS_CONTENT=_("请出席网络会议！")."\n"._("议题：").csubstr($SUBJECT,0,100);
  }

  $query="delete from NETMEETING where MEET_ID='$MEET_ID'";
  exequery(TD::conn(),$query);

  delete_remind_sms(3, $_SESSION["LOGIN_USER_ID"], $SMS_CONTENT, $BEGIN_TIME);

  header("location: index.php");
?>

</body>
</html>
