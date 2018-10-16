<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("工作安排查询");
include_once("inc/header.inc.php");
?>




<frameset rows="250,*" cols="*" frameborder="NO" border="0" framespacing="0">
  <frame name="calendar" scrolling="auto" noresize src="calendar.php?USER_ID=<?=$USER_ID?>" >
  <frame name="user_list" scrolling="auto" noresize src="blank.php?USER_ID=<?=$USER_ID?>">
</frameset>

</html>
