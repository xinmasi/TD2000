<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("�������");
include_once("inc/header.inc.php");
?>


<?
$query = "SELECT * from NETMEETING where MEET_ID='$MEET_ID' and (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TO_ID) or FROM_ID='".$_SESSION["LOGIN_USER_ID"]."')";
$cursor= exequery(TD::conn(),$query);
if(!$ROW=mysql_fetch_array($cursor))
{ 
	message(_("��ʾ"),_("�Ƿ�����"));
	exit;
}

$USER_NAME=$_SESSION["LOGIN_USER_NAME"];
$USER_IP=get_client_ip();
$STOP_FILE=MYOA_ATTACH_PATH."netmeeting/".$MEET_ID.".stp";
$MSG_FILE=MYOA_ATTACH_PATH."netmeeting/".$MEET_ID.".msg";
if(!file_exists($STOP_FILE))
{
	if($fp = td_fopen($MSG_FILE,  "a+"))
	{
	   fputs($fp,sprintf("\n"._("[ϵͳ��Ϣ] - %s���������  "),$USER_NAME));
	   fclose($fp);
	}
}
?>

<frameset rows="*" cols="*,15%" FRAMEBORDER="no" FRAMESPACING="1">
  <frameset rows="7%,0,*,15%" cols="*" FRAMEBORDER="yes">
    <frame src="title.php?MEET_ID=<?=$MEET_ID?>&SUBJECT=<?=$SUBJECT?>" name="chat_title" scrolling="no" FRAMEBORDER="no" noresize="noresize">
    <frame src="view.php?MEET_ID=<?=$MEET_ID?>&FIRST_VIEW=1" name="chat_view" scrolling="yes" noresize="noresize">
    <frame src="view_area.php" name="chat_view_area" scrolling="auto" noresize="noresize">
    <frame name="chat_input" src="input.php?MEET_ID=<?=$MEET_ID?>&USER_NAME=<?=$USER_NAME?>" scrolling="no" FRAMEBORDER="no" noresize="noresize">
  </frameset>

  <frameset rows="*,15%" cols="*">
    <frame src="user.php?MEET_ID=<?=$MEET_ID?>&USER_NAME=<?=$USER_NAME?>&USER_IP=<?=$USER_IP?>" name="chat_user" scrolling="auto" FRAMEBORDER="no" noresize="noresize">
    <frame src="control.php?MEET_ID=<?=$MEET_ID?>" name="chat_control" FRAMEBORDER="no" noresize="noresize">
  </frameset>

</frameset>
<noframes>
<body bgcolor="#cccccc">
</body></noframes>
</html>