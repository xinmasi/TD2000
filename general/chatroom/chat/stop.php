<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("停止聊天室");
include_once("inc/header.inc.php");
?>




<body bgcolor="#F1FAF5" class="small" topmargin="8">

<?
if($_SESSION["LOGIN_USER_PRIV"]!="1")
{
   message(_("提示"),_("非法操作"));
   exit;
}  

$MSG_FILE=MYOA_ATTACH_PATH."chatroom/".$CHAT_ID.".msg";
$STOP_FILE=MYOA_ATTACH_PATH."chatroom/".$CHAT_ID.".stp";
$fp = td_fopen($MSG_FILE,"a+");
flock ($fp,2);
fwrite($fp,"\n"._("[系统消息] - 聊天室已经关闭  "));
fclose($fp);
$fp = td_fopen($STOP_FILE,"w");
fclose($fp);
$query="update CHATROOM set STOP='1' where CHAT_ID='$CHAT_ID'";
exequery(TD::conn(),$query);
?>

<center>
<input type="button" value="<?=_("离开聊天室")?>" class="SmallButton" onclick="parent.location='../'">
</center>
<script>
parent.chat_view.location.reload();
</script>

</body>
</html>
