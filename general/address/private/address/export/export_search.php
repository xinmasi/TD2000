<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("导出查询数据");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/address/new_add.css" />
<script>

function CheckForm()
{
	document.form1.submit();
}

</script>
</head>

<body>
<div class="dc" style="width: 100%;height:400px">
    <div class="dc_r" style="width: 100%;height: 100%">
        <form action="export_search_info.php" name="form1">
            <div name="type" id="type" style="width:400px; height:100%;position:relative; ">
                <label class="radio" style="width:150px; position:absolute; top:100px;left:250px;font-size:14px;"><input type="radio" name="daoc" checked="checked" value="1"/> <?=_("导出为")?>Foxmail</label>
                <label class="radio" style="width:150px; position:absolute; top:130px;left:250px;font-size:14px;"><input type="radio" name="daoc" value="2"/><?=_("导出为")?>outlook</label>
                <!--
                <label class="radio" style="width:150px; position:absolute; top:160px;left:250px;font-size:14px;"><input type="radio" name="daoc" value="3"/><?=_("导出为")?>csv</label>
                <label class="radio" style="width:150px; position:absolute; top:190px;left:250px;font-size:14px;"><input type="radio" name="daoc" value="4"/><?=_("导出为")?>vcard</label>
                -->
            </div>
            <input type="hidden" name="keyword" value="<?=$keyword?>">
        </form>
    </div>
</div>

</body>
</html>
