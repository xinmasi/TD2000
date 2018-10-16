<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("导出数据");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/address/new_add.css" />
<script>
function show_export(export_id)
{
    var url;
    if(export_id == '2')
    {
        url = "export/export_group.php";
    }
    else if(export_id == '3')
    {
        url = "export/export_search.php?group_id=<?=$GROUP_ID?>&keyword=<?=$keyword?>";
    }
    else if(export_id == '4')
    {
        url = "export/export_add.php";
    }
    else
    {
        url = "export/export_all.php";
    }
    document.getElementById('export_show').src = url;
}

function export_submit()
{
    document.getElementById('export_show').contentWindow.CheckForm();
}
</script>
<body style="overflow: hidden;">
<div class="dc" style="padding-left:0px">
    <div class="dc_l" style="padding-left:0px; margin-left:0px;">
        <ul>
            <li onClick="show_export('1');"><a style="padding-left:0px; margin-left:0px;"><span class="dc_img" style="padding-left:0px; margin-left:0px;*+top:17px;"><img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/export_all1.png" /></span><?=_("导出全部")?></a></li>
            <li onClick="show_export('2');"><a style="padding-left:0px; margin-left:0px;"><span class="dc_img" style="padding-left:0px; margin-left:0px;*+top:17px;"><img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/export_group1.png" /></span><?=_("导出分组")?></a></li>
            <li onClick="show_export('3');"><a style="padding-left:0px; margin-left:0px;"><span class="dc_img" style="padding-left:0px; margin-left:0px;*+top:17px;"><img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/export_search1.png" /></span><?=_("导出搜索结果")?></a></li>
            <li onClick="show_export('4');"><a style="padding-left:0px; margin-left:0px;"><span class="dc_img" style="padding-left:0px; margin-left:0px;*+top:17px;"><img src="<?=MYOA_JS_SERVER?>/static/modules/address/images/export_el1.png" /></span><?=_("导出指定联系人")?></a></li>
        </ul>
    </div>
    <div class="dc_r">
        <iframe width="100%" height="100%" style="overflow:hidden; background-color:#fff;" id="export_show" name="export_show" frameborder="0" src="export/export_all.php?keyword=<?=$keyword?>">
        </iframe>
    </div>

</div>
</body>