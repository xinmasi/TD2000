<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("ֹͣ������");
include_once("inc/header.inc.php");
?>




<body bgcolor="#F1FAF5" class="small" topmargin="8">

<?
if($_SESSION["LOGIN_USER_PRIV"]!="1")
{
   message(_("��ʾ"),_("�Ƿ�����"));
   exit;
}  

$MSG_FILE=MYOA_ATTACH_PATH."chatroom/".$CHAT_ID.".msg";
$STOP_FILE=MYOA_ATTACH_PATH."chatroom/".$CHAT_ID.".stp";
$fp = td_fopen($MSG_FILE,"a+");
flock ($fp,2);
fwrite($fp,"\n"._("[ϵͳ��Ϣ] - �������Ѿ��ر�  "));
fclose($fp);
$fp = td_fopen($STOP_FILE,"w");
fclose($fp);
$query="update CHATROOM set STOP='1' where CHAT_ID='$CHAT_ID'";
exequery(TD::conn(),$query);
?>

<center>
<input type="button" value="<?=_("�뿪������")?>" class="SmallButton" onclick="parent.location='../'">
</center>
<script>
parent.chat_view.location.reload();
</script>

</body>
</html>
