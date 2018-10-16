<?
include_once ("inc/auth.inc.php");
$HTML_PAGE_TITLE = _("办公用品信息管理");
include_once ("inc/header.inc.php");
?>
<!--<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/style.css" />-->
<link rel="stylesheet" type="text/css" href="/static/modules/office_product/css/style.css" />
<body>
<div id="west">
    <iframe id="PRO_LIST" name="PRO_LIST" frameborder="0" src="pro_list.php"></iframe>
</div>
<div id="center">
    <iframe  name="listall" frameborder="0" src="list.php"></iframe>
</div>
</body>