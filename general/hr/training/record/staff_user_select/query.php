<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>
<style>
#center iframe{
    width: 100%;
    height: 100%;
    display: block;
    position: absolute;
    top:30px;
    bottom:0;
    left:0;
    right:0;
}
</style>
<script>
function iframeinfo()
{
	var srcinfo=document.form1.KEY_WORD.value;
	document.getElementById("user_info").src='staff_user_select.php?KEY_WORD='+srcinfo;
}
</script>


<body bgcolor="#E8E8E8" topmargin="5">

<center>

 <form method="post" action="staff_user_select.php" target="staff_user_select" name="form1">
  <?=_("ÅàÑµÈË£º")?>
  <input type="text" name="KEY_WORD" size="10" class="BigInput">
  <input type="button" name="button" value="<?=_("Ä£ºý²éÑ¯")?>" class="BigButton" onclick="iframeinfo()">
 </form>
     <div id="center">
    <iframe name="record_select" src="staff_user_select.php" frameborder="NO" id="user_info"></iframe>
    </div>
</center>

</body>
</html>
