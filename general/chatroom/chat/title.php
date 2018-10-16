<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("聊天室名称");
include_once("inc/header.inc.php");
?>




<?
$query = "SELECT * from CHATROOM where CHAT_ID='$CHAT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$SUBJECT=$ROW["SUBJECT"];
   $SUBJECT=str_replace("<","&lt",$SUBJECT);
   $SUBJECT=str_replace(">","&gt",$SUBJECT);
   $SUBJECT=stripslashes($SUBJECT);
}
?>
<body  bgcolor="#F1FAF5" class="small" topmargin="6" onunload="window.status='';">
	
<div class="big1"><b><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/chatroom.gif" WIDTH="22" HEIGHT="20" align="absmiddle"> <?=sprintf(_("欢迎进入%s聊天室："),$SUBJECT)?>&nbsp;&nbsp;&nbsp;</div>

</body>
</html>
