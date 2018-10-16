<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("导入数据");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.css">
<link rel="stylesheet" type="text/css"  href="<?=MYOA_JS_SERVER?>/static/modules/address/new_add.css"/>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>

<body style="background:#fff;">

<script Language="JavaScript">
function CheckForm()
{
    if(document.form1.EXCEL_FILE.value == "")
    {
        alert("<?=_("请选择要导入的文件！")?>");
        return (false);
    }
    
    if (document.form1.EXCEL_FILE.value != "")
    {
        var file_temp=document.form1.EXCEL_FILE.value,file_name;
        var Pos;
        Pos=file_temp.lastIndexOf("\\");
        file_name=file_temp.substring(Pos+1,file_temp.length);
        document.form1.FILE_NAME.value=file_name;
    }
    
	document.getElementById('form1').submit();
}

function hide_dialog()
{
    parent.document.getElementById('hide_import').click();
}
function file_value(file_val)
{
    document.form1.show_file.value = file_val;
}

$(document).ready(function(){
    $('#select_file').click(function(){
        $('#excel_file').click();
    })
});
</script>
<style>
input[readonly] {
  cursor: auto;
  background-color: #fff;
}
</style>
    <div class="leadin">
        <form name="form1" id="form1" method="post" action="import_change.php" enctype="multipart/form-data" onSubmit="return CheckForm();">
            <div class="form-horizontal" id="dr">
                <div class="control-group">
                    <label class="control-label" for="appendedInput" id="drtext"><?=_("请选择要导入的文件：")?></label>
                    <div class="input-append">
                        <input type="file" id="excel_file" name="EXCEL_FILE" style="cursor:pointer; position:absolute; top:0; right:180px; height:30px; filter:alpha(opacity:0);opacity: 0;width:40px" size='1'  hideFocus='' onChange="file_value(this.value);"/>
                        <input type="text" id="show_file" name="show_file" />
                        <span class="add-on" style="cursor:pointer;font-size:13px;" id="select_file"><?=_("浏览")?></span>
                    </div>
                </div>
            </div>
            <p id="gs" class="help-block">*<?=_("支持导入的格式为")?>.foxmail、outlook</p>
            <input type="hidden" name="FILE_NAME">
        </form>
    </div>

</body>
</html>
