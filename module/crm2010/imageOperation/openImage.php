<?
include_once("general/crm/inc/header.php");

$HTML_PAGE_TITLE = _("Í¼Æ¬ä¯ÀÀ");
include_once("inc/header.inc.php");
?>
	


<frameset rows="*,30"  cols="*" frameborder="NO" border="0" framespacing="0" id="frame1">
    <frame name="open_main" scrolling="yes" src="openImageMain.php?FILE_NAME=<?=$FILE_NAME?>" frameborder="0">
    <frame name="open_control" scrolling="no" noresize src="openImageControl.php?FILE_NAME=<?=$FILE_NAME?>" frameborder="0">
</frameset>

</html>
