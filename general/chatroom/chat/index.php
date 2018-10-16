<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("聊天室");
include_once("inc/header.inc.php");
?>



<?
$STOP_FILE=MYOA_ATTACH_PATH."chatroom/".$CHAT_ID.".stp";
$MSG_FILE=MYOA_ATTACH_PATH."chatroom/".$CHAT_ID.".msg";

if(!file_exists($STOP_FILE) && $USER_NAME!="")
{
   $fp = td_fopen($MSG_FILE,  "a+");
   $MSG1 = sprintf(_("[系统消息] - %s进入聊天室"), $USER_NAME);
   fputs($fp,$MSG1."\n");
   fclose($fp);
}
?>

<frameset rows="*" cols="85%,15%" FRAMEBORDER="1" FRAMESPACING="1">
  <frameset rows="7%,0,78%,15%" cols="*" FRAMEBORDER="yes">
    <frame src="title.php?CHAT_ID=<?=$CHAT_ID?>&SUBJECT=<?=$SUBJECT?>" name="chat_title" scrolling="no" FRAMEBORDER="no">
    <frame src="view.php?CHAT_ID=<?=$CHAT_ID?>" name="chat_view" scrolling="yes" noresize="noresize">
    <frame src="view_area.php" id="chat_view_area"  name="chat_view_area" scrolling="auto" noresize="noresize">
    <frame name="chat_input" src="input.php?CHAT_ID=<?=$CHAT_ID?>&USER_NAME=<?=$USER_NAME?>&USER_IP=<?=$USER_IP?>" scrolling="no" FRAMEBORDER="no" noresize="noresize">
  </frameset>
  <frameset rows="85%,15%" cols="*">
    <frame src="user.php?CHAT_ID=<?=$CHAT_ID?>&USER_NAME=<?=$USER_NAME?>&USER_IP=<?=$USER_IP?>" id="chat_user" name="chat_user" scrolling="auto" FRAMEBORDER="no" noresize="noresize">
    <frame src="control.php?CHAT_ID=<?=$CHAT_ID?>" name="chat_control" FRAMEBORDER="no" scrolling="no" noresize="noresize"> 
  </frameset>

</frameset>

<noframes>
<body bgcolor="#cccccc">
</body></noframes>
</html>
