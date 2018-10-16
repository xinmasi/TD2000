<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("ÈËÊÂ·ÖÎö");
include_once("inc/header.inc.php");
?>



<frameset rows="20%,*" frameborder="NO" border="0" framespacing="0" id="frame1">
  <frame name="select" scrolling="no" noresize src="graphic.php?GROUP_ID=<?=$GROUP_ID ?>&FLOW_ID=<?=$FLOW_ID ?>" frameborder="0" >
  <frame name="tu_main" scrolling="auto" noresize src="analysis.php?GROUP_ID=<?=$GROUP_ID ?>&FLOW_ID=<?=$FLOW_ID ?>&CHOSE=1" frameborder="0" > 
</frameset>
