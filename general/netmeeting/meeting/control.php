<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("����������");
include_once("inc/header.inc.php");
?>



<script>
function stop_meet()
{
 msg='<?=_("ȷ��Ҫ����������")?>\n<?=_("������������ۺϹ����е������������м����û���")?>';
 if(window.confirm(msg))
 {
  URL="stop.php?MEET_ID=<?=$MEET_ID?>";
  window.location=URL;
 }
}
</script>


<body bgcolor="#F1FAF5" topmargin="8">
<center>
  <input type="button" value="<?=_("�뿪�᳡")?>" class="SmallButton" onclick="parent.location='../'">
  <br>

<?
 $query = "SELECT * from NETMEETING where FROM_ID='".$_SESSION["LOGIN_USER_ID"]."' and MEET_ID='$MEET_ID'";
 $cursor= exequery(TD::conn(),$query);

 if($ROW=mysql_fetch_array($cursor))
 {
?>

  <input type="button" value="<?=_("��������")?>" class="SmallButton" onclick="stop_meet();">
<?
 }
?>
</center>
</body>
</html>
