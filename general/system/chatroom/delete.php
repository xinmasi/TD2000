<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

//-- ɾ���ļ� --
$MSG_FILE="../../chatroom/chat/msg/".$CHAT_ID.".msg";
$STOP_FILE="../../chatroom/chat/msg/".$CHAT_ID.".stp";
$USR_FILE="../../chatroom/chat/msg/".$CHAT_ID.".usr";
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
$query="delete from CHATROOM where CHAT_ID='$CHAT_ID'";
exequery(TD::conn(),$query);

header("location: index.php");
?>
