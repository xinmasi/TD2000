<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�����ҿ���");
include_once("inc/header.inc.php");
?>



<script>
function stop_chat()
{
	msg='<?=_("ȷ��Ҫ�ر���������")?>\n<?=_("�رպ������ϵͳ�����е��������������ٿ��Ÿ�������")?>';
	if(window.confirm(msg))
	{
 		URL="stop.php?CHAT_ID=<?=$CHAT_ID?>";
  		window.location=URL;
	}
}
</script>

<body bgcolor="#F1FAF5" class="small" topmargin="8">
<center>
  <input type="button" value="<?=_("�뿪������")?>" class="SmallButton" onclick="parent.location='../'">

<?
 if($_SESSION["LOGIN_USER_PRIV"]=="1")
 {
?>

  <input type="button" value="<?=_("�ر�������")?>" class="SmallButton" onclick="stop_chat();">
<?
 }
?>
</center>
</body>
</html>
