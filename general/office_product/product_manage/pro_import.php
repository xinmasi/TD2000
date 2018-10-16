<?
include_once ("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("导入办公用品信息" );
include_once ("inc/header.inc.php");
?>
<script Language="JavaScript">
function CheckForm2()
{
    if(document.form2.EXCEL_FILE.value=="")
    {
        alert("<?=_("请选择要导入的文件！")?>");
        return false;
    }
    if(document.form2.EXCEL_FILE.value!="")
    {
        var file_temp=document.form2.EXCEL_FILE.value,file_name;
        var Pos;
        Pos=file_temp.lastIndexOf("\\");
        file_name=file_temp.substring(Pos+1,file_temp.length);
        document.form2.FILE_NAME.value=file_name;
    }
    return true;
}

</script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/product.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.js"></script>

<body class="bodycolor" style="background: white;">
<div class="product_main_div">
    <div class="wrap_top_info">
        <h3><?=_("办公用品信息导入")?></h3>
    </div>
    <div align="center">
        <div style="margin: 0 auto; width: 300px;">
            <span style="color: green; font-size: 14px; font-weight: bold; padding: 0px;"><?=_("请指定用于导入的Excel文件")?></span>
        </div>
        <div>
            <form name="form2" method="post" action="import.php" enctype="multipart/form-data" onSubmit="return CheckForm2();">
                <input type="file" name="EXCEL_FILE" class="BigInput" size="30">
                <input type="hidden" name="FILE_NAME">
                <input type="hidden" name="GROUP_ID" value="<?=$GROUP_ID?>">
                <input type="submit" value="<?=_("导入")?>" class="btn btn-info">
            </form>
        </div>
        <div>
            <span style="color: green;"><?=_("请使用办公用品信息模板导入数据！")?></span>
            <a href="#" onClick="window.location='templet_export.php'">
                <?=_("办公用品信息模板下载")?>
            </a>
        </div>
    </div>
    <div class="alert clear" style="margin: 0 auto; width: 320px; margin-top: 15px;">
        <button type="button" class="close" data-dismiss="alert" title='<?=_('关闭')?>'>&times;</button>
        <strong style="color: rgb(52, 52, 87); font-size: 16px; font-weight: bold;"><?=_("说明:")?></strong><br />
        <p style="color: green;">
            <span>
                <?=_("1、模板中的登记权限(用户)和审批人、创建人项如果不为空，应该填的是真实姓名，注意保证姓名没有重复；")?><br>
                <?=_("2、模板中办公用品库、办公用品类别名必须在办公用品类别中存在；")?><br>
                <?=_("3、模板中办公用品名称、规格/型号、用品库、用品类别、价格与库内办公用品重复时将会更新库内办公用品信息，请谨慎填写；")?>
            </span>
        </p>
    </div>
</div>
</body>
</html>