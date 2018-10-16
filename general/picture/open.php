<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$FILE_NAME = urlencode($URL_FILE_NAME);
$FILE_NAME = td_iconv($FILE_NAME, "UTF-8", MYOA_CHARSET);
$HTML_PAGE_TITLE = _("Í¼Æ¬ä¯ÀÀ");
include_once("inc/header.inc.php");
?>
<frameset rows="*,50"  cols="*" frameborder="NO" border="0" framespacing="0" id="frame1">
    <frame name="open_main" scrolling="yes" src="open_main.php" frameborder="0">
    <frame name="open_control" scrolling="no" noresize src="open_control.php?PIC_ID=<?=$PIC_ID?>&SUB_DIR=<?=$SUB_DIR?>&FILE_NAME=<?=$FILE_NAME?>&VIEW_TYPE=<?=$VIEW_TYPE?>&ASC_DESC=<?=$ASC_DESC?>" frameborder="0">
</frameset>
</html>
