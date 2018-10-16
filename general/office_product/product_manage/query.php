<?
include_once("inc/auth.inc.php");

include_once("../function_type.php");
$HTML_PAGE_TITLE = _("办公用品查询");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/numberpicker/numberpicker.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery-ui/css/flick/jquery-ui-1.10.3.custom.min.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/numberpicker/numberpicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/ajax.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/mohu_query.js"></script>
<script>
function setexport(id)
{
    if(id==1)
    {
        document.form1.action="export.php?ispirit_export=1";
    }else
    {
        document.form1.action="list.php?action=query";
    }
    document.form1.submit();
}
</script>
<body>
<h3><?=_("办公用品查询")?></h3>
<div class="row-fluid" align="center">
    <div class='span6' style='float:none;'>
        <form method="post" name="form1" action="list.php?action=query">
            <table class="table table-bordered" >
                <tr>
                    <td class="align-right" nowrap><?=_("办公用品库：")?><font style="color: red;padding-left: 1px;"></font></td>
                    <td class='set_only'>
                        <select name="OFFICE_DEPOSITORY" id="OFFICE_DEPOSITORY" class="input-medium" onchange = "depositoryOfTypeOne(this.value,'');">
                            <option value="-1"><?=_("请选择")?></option>
                            <?=get_depository('dept_aut',$_SESSION["LOGIN_DEPT_ID"])?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="align-right" nowrap><?=_("办公用品类别：")?><font style="color: red;padding-left: 1px;"></font></td>
                    <td id="OFFICE_TYPE" class='set_only'>
                        <select name="OFFICE_PROTYPE" id="OFFICE_PROTYPE" class="input-medium" onChange="depositoryOfProductsOne(this.value,'');">
                            <option value="-1"><?=_("请选择")?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="align-right" nowrap><?=_("办公用品：")?><font style="color: red;padding-left: 1px;"></font></td>
                    <td id="OFFICE_PRODUCTS" class='set_only'>
                        <select name="PRO_ID" id="PRO_ID" class="input-medium">
                            <option value="-1"><?=_("请选择")?></option>
                        </select> &nbsp;
                        <input id="TOGGLE_BLUR" type="button" name="SelectPro" title="<?=_("模糊选择")?>" value="<?=_("模糊选择")?>" class="btn btn-small btn-info">
                    </td>
                </tr>
                <tr id="BLURRED" style="display:none">
                    <td class="align-right"><?=_("模糊名称")?>:</td>
                    <td>
                        <input type="hidden" id="mytag" name='mytag' value='1'>
                        <input type="text" id="PRO_NAME" name="PRO_NAME" size="20" maxlength="20" class="input-large" style="margin:0px;" value="">&nbsp;&nbsp;
                        <input type="hidden" id="project-id" name="project-id" value="">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style='text-align:center;'>
                        <input value="<?=_("查询")?>" class="btn btn-small btn-primary" title="<?=_("查询")?>" type="submit" onClick="setexport(0);">
                        <input value="<?=_("导出")?>" class="btn btn-small btn-primary" title="<?=_("导出")?>" type="button" onClick="setexport(1);">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
</body>
</html>