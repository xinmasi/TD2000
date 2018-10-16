<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("网络会议控制");
include_once("inc/header.inc.php");
?>



<script>
function stop_meet()
{
 msg='<?=_("确认要结束会议吗？")?>\n<?=_("结束后可以在综合管理中的网络会议管理中继续该会议")?>';
 if(window.confirm(msg))
 {
  URL="stop.php?MEET_ID=<?=$MEET_ID?>";
  window.location=URL;
 }
}
</script>


<body bgcolor="#F1FAF5" topmargin="8">
<center>
  <input type="button" value="<?=_("离开会场")?>" class="SmallButton" onclick="parent.location='../'">
  <br>

<?
 $query = "SELECT * from NETMEETING where FROM_ID='".$_SESSION["LOGIN_USER_ID"]."' and MEET_ID='$MEET_ID'";
 $cursor= exequery(TD::conn(),$query);

 if($ROW=mysql_fetch_array($cursor))
 {
?>

  <input type="button" value="<?=_("结束会议")?>" class="SmallButton" onclick="stop_meet();">
<?
 }
?>
</center>
</body>
</html>
