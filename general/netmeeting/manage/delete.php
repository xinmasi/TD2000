<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
  //-- ɾ���ļ� --
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


  //-- ɾ�����ݿ��¼ --
  $query="select * from NETMEETING where MEET_ID='$MEET_ID'";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
  {
    $BEGIN_TIME=$ROW["BEGIN_TIME"];
    $SUBJECT=$ROW["SUBJECT"];

    $SMS_CONTENT=_("���ϯ������飡")."\n"._("���⣺").csubstr($SUBJECT,0,100);
  }

  $query="delete from NETMEETING where MEET_ID='$MEET_ID'";
  exequery(TD::conn(),$query);

  delete_remind_sms(3, $_SESSION["LOGIN_USER_ID"], $SMS_CONTENT, $BEGIN_TIME);

  header("location: index.php");
?>

</body>
</html>
