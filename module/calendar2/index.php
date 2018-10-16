<?
include_once("inc/auth.inc.php");
$HTML_PAGE_TITLE = _("ÍòÄêÀú");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/jquery-1.10.2/lunar_calendar/lunar_calendar.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>        
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/lunar_calendar/lunar_calendar.js"></script>
<script>
$(function(){
    $('#calendar').lunarCalendar();
});
</script>
<body>
<div id="calendar"></div>
</body>
</html>
