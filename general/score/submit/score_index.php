<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("¿¼ºËÊý¾ÝÂ¼Èë");
include_once("inc/header.inc.php");
?>




<frameset rows="*" cols="150,*" frameborder="NO" border="0" framespacing="0">
  <frame name="user_list" scrolling="YES" frameborder="NO" noresize src="user_list.php?FLOW_ID=<?=$FLOW_ID?>" >
  <frame name="hrms" scrolling="YES" noresize src="blank.php?FLOW_ID=<?=$FLOW_ID?>">
</frameset>

</html>
