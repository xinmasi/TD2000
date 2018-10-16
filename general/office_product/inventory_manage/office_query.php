<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("../function_type.php");
include_once ("inc/utility_all.php");

$HTML_PAGE_TITLE = _("办公用品查询");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/numberpicker/numberpicker.css" />
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery-ui/css/flick/jquery-ui-1.10.3.custom.min.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js"></script><script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/numberpicker/numberpicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/ajax.js"></script>
<script type="text/javascript" src="/module/DatePicker/WdatePicker.js"></script>
<script>
$(document).ready(function(){
    tag = '1';
    $("#TOGGLE_BLUR").click(function(){
       if(tag=='1'){
            $('#OFFICE_DEPOSITORY').val('-1');
            $('#OFFICE_PROTYPE').val('-1');
            $('#PRO_ID').val('-1');
            $("#BLURRED").show();
            $("#mytag").val('');
            $("#mytag").val('0');
            $(".set_only select").attr("disabled",true); 
            $("#PRO_NAME").focus();
            tag = '0';
        }else if(tag == '0'){
            $("#BLURRED").hide();
            $("#PRO_NAME").val('');
            $("#project-id").val('');
            $("#mytag").val('1');
            $(".set_only select").attr("disabled",false); 
            tag = '1';
       }
    });
});
function CheckForm1()
{
    var OFFICE_DEPOSITORY = $('#OFFICE_DEPOSITORY').val();
    if(OFFICE_DEPOSITORY!="-1")
    {
        var OFFICE_PROTYPE = $('#OFFICE_PROTYPE').val();
        var PRO_ID = $('#PRO_ID').val();

        if(OFFICE_PROTYPE=="-1")
        {
            alert("<?=_("请选择办公用品类别")?>");
            $('#OFFICE_PROTYPE').focus();
            return false;
        }
        if(PRO_ID=="-1")
        {
            alert("<?=_("请选择办公用品")?>");
            $('#PRO_ID').focus();
            return false;
        }
    }
    document.form1.submit();
}
function sel_change()
{
    if(form1.TRANS_FLAG.value=="a1")
    {
        $("#OFFICE_DEPOSITORY").attr("disabled","disabled");
        $("#OFFICE_PROTYPE").attr("disabled","disabled");
        $("#PRO_ID").attr("disabled","disabled");
        $("#BYNAME").hide();
    }else
    {
        $("#OFFICE_DEPOSITORY").removeAttr("disabled","disabled");
        $("#OFFICE_PROTYPE").removeAttr("disabled","disabled");
        $("#PRO_ID").removeAttr("disabled","disabled");
        $("#BYNAME").show();
    }
}
</script>
<body>
<div class="row-fluid" align="center">
    <div class='span8' style='float:none;'>
        <div style="text-align:left;">
            <h3><?=_("办公用品查询")?></h3>
        </div>
        <form enctype="multipart/form-data" action="office_search.php" name="form1" id="form1"  method="post">
            <table class="table table-bordered table-hover">
                <tr>
                    <td class="align-right" style='width: 120px;'><?=_("查询类型：")?></td>
                    <td>
                        <select class="input-xlarge" id="TRANS_FLAG" name="TRANS_FLAG" style='<?=$display_set_one?>' onChange="sel_change()">
                            <option value="-1"><?=_("---请选择---")?></option>
                            <option value="0"><?=_("采购入库")?></option>
                            <option value="1"><?=_("领用")?></option>
                            <option value="2"><?=_("借用")?></option>
                            <option value="5"><?=_("维护")?></option>
                            <option value="4"><?=_("报废")?></option>
                            <option value="a1"><?=_("调拨")?></option>
                        </select>
                    </td>
                </tr>
                <tr id="BYNAME">
                    <td class="align-right"><?=_("申请人：")?></td>
                    <td>
                        <input type="hidden" name="TO_ID" id="TO_ID" value="<?=$res2['BORROWER']?>">  
                        <input type="text" name="TO_NAME" id="TO_NAME" size="20" maxlength="20"  value="<?=$BORROWER_NAME?>"  style="margin-bottom:0px;" disabled>
                        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('126','','TO_ID','TO_NAME')"><?=_("选择")?></a>
                        <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("办公用品库：")?></td>
                    <td class='set_only'>
                        <select name="OFFICE_DEPOSITORY" id="OFFICE_DEPOSITORY" class="input-xlarge" onchange = "depositoryOfTypeOne(this.value,'');">
                            <option value="-1"><?=_("请选择")?></option>
                            <?=get_depository('office_type',$_SESSION["LOGIN_DEPT_ID"],$type)?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("办公用品类别：")?></td>
                    <td class='set_only'>
                        <select name="OFFICE_PROTYPE" id="OFFICE_PROTYPE"  class="input-xlarge" onChange="depositoryOfProductsOne(this.value,'');">
                            <option value="-1"><?=_("请选择")?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("办公用品：")?></td>
                    <td class='set_only'>
                        <select name="PRO_ID"  id="PRO_ID" class="input-xlarge">
                            <option value="-1"><?=_("请选择")?></option>
                        </select> &nbsp;
                       <input id="TOGGLE_BLUR" type="button" name="SelectPro" title="<?=_("模糊选择")?>" value="<?=_("模糊选择")?>" class="btn btn-small btn-info">
                    </td>
                </tr>
                <tr id="BLURRED" style="display:none">
                    <td class="align-right"><?=_("模糊名称")?>:</td>
                    <td>
                        <input type="text" id="PRO_NAME" name="PRO_NAME" size="20" maxlength="20" class="input-large" style="margin:0px;" value="">
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("日期")?>:</td>
                    <td>
                         <input type="text" name="FROM_DATE"  id="FROM_DATE" class="input-small"  size="15" maxlength="10" value="<?=$DATE?>" onClick="WdatePicker({maxDate:'#F{$dp.$D(\'TO_DATE\')}'})">&nbsp;<?=_("至")?>&nbsp;
       <input type="text" name="TO_DATE" id="TO_DATE" class="input-small" size="15" maxlength="10" value="<?=$DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'FROM_DATE\')}'})">&nbsp;
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style='text-align: center;'>
                        <input type="button" class="btn btn-small btn-primary" style="margin-top:5px;" onClick="CheckForm1();" value="<?=_("查询")?>">
                        <input type="reset" class="btn btn-small" style="margin-top:5px;" value="<?=_("重置")?>" onClick="clear_stock();">
                    </td>
                </tr>
            </table>
        </form>
    </div>    
</div>
</body>
</html>