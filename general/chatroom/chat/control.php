<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("聊天室控制");
include_once("inc/header.inc.php");
?>



<script>
function stop_chat()
{
	msg='<?=_("确认要关闭聊天室吗？")?>\n<?=_("关闭后可以在系统管理中的聊天室设置中再开放该聊天室")?>';
	if(window.confirm(msg))
	{
 		URL="stop.php?CHAT_ID=<?=$CHAT_ID?>";
  		window.location=URL;
	}
}
</script>

<body bgcolor="#F1FAF5" class="small" topmargin="8">
<center>
  <input type="button" value="<?=_("离开聊天室")?>" class="SmallButton" onclick="parent.location='../'">

<?
 if($_SESSION["LOGIN_USER_PRIV"]=="1")
 {
?>

  <input type="button" value="<?=_("关闭聊天室")?>" class="SmallButton" onclick="stop_chat();">
<?
 }
?>
</center>
</body>
</html>
