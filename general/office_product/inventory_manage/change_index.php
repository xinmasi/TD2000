<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("µ÷²¦");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/style.css" />
<body>
    <div id="west">
        <iframe id="PRO_LIST" name="PRO_LIST" frameborder="0" src="pro_list.php" ></iframe>
    </div>
    <div id="center">
        <iframe id="list" name="list" frameborder="0"  src="change.php"></iframe>
    </div>
</body>
</html>