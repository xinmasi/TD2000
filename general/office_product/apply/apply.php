<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("办公用品申领");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/style.css" />
<style>

body,html{
    width: 100%;
    height: 100%;
}
#center #list{
    width:100%;
}

</style>
<body>
    <div id="west" style="border-right: 1px solid #e3e3e3;height:100%;float:left;">
        <iframe id="PRO_LIST" name="PRO_LIST" frameborder="0" src="pro_list.php" height="100%" ></iframe>
    </div>
    <div id="center"">
        <iframe id="list"name="list" frameborder="0"  src="apply_one.php" height="100%"></iframe>
    </div>
</body>
</html>