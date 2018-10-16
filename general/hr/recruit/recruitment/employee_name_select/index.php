<?
include_once("inc/auth.inc.php");

echo "<meta http-equiv=X-UA-Compatible content=IE=EmulateIE8>";
$HTML_PAGE_TITLE = _("选择应聘者姓名");
include_once("inc/header.inc.php");
?>



<frameset cols="*"  rows="40,*" frameborder="YES" border="1" framespacing="0" id="bottom">
  <frame name="query" src="query.php" scrolling="NO" frameborder="YES">
  <frame name="employee_name_info" src="employee_name_info.php" frameborder="NO">
 </frameset>
</frameset>

</html>
