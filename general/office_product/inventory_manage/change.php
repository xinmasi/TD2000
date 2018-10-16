<?
include_once("inc/auth.inc.php");
include_once("../function_type.php");

$HTML_PAGE_TITLE = _("调拨");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css"	href="<?=MYOA_STATIC_SERVER?>/static/modules/office_product/css/style.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/ajax.js"></script>
<style type="text/css">
    label{
        cursor:text;
    }
    .controls{
        text-align:left;
    }
</style>
<body style="background:#f8f8f8;">
<div class="container-fluid" align="center">
    <div class="row-fluid">
        <div style="text-align:left;">
            <h3><?=_("调拨")?></h3>
        </div>
        <div class="span10" style='float:none;'>
            <form class="form-horizontal" enctype="multipart/form-data" action="change_add.php" method="post" name="form1">
                <div class="row" style='margin-top:20px;'>
                    <div class="span5" style="border:1px solid #ccc;background:#fff; padding:10px;padding-top:30px;">
                        <div class="control-group">
                            <label class="control-label control-align"><?=_("办公用品库：")?><font style="color: red;padding-left: 1px;">*</font></label>
                            <div class="controls" id="control-left">
                                <select name="OFFICE_DEPOSITORY" id="OFFICE_DEPOSITORY" class="input-medium" onchange = "depositoryOfTypeOne(this.value,'');">
                                    <option value="-1"><?=_("请选择")?></option>
                                    <?
                                    if($id !='')
                                    {
                                        $type=get_office_depository($id);
                                    }else{
                                        $type=-1;
                                    }
                                    ?>
                                    <?=get_depository('office_type',$_SESSION["LOGIN_DEPT_ID"],$type)?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label id="OFFICE_TYPE" class="control-label control-align"><?=_("办公用品类别：")?><font style="color: red;padding-left: 1px;">*</font></label>
                            <div class="controls" id="control-left">
                                <select name="OFFICE_PROTYPE" id="OFFICE_PROTYPE" class="input-medium" onChange="depositoryOfProductsOne(this.value,'2');">
                                    <option value="-1"><?=_("请选择")?></option>
                                    <?
                                    if($id !='')
                                    {
                                        $name=get_office_type($id);
                                        ?>
                                        <option value="<?=$id?>" selected><?=$name?></option>
                                        <?
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label id="OFFICE_PRODUCTS" class="control-label control-align"><?=_("办公用品：")?><font style="color: red;padding-left: 1px;">*</font></label>
                            <div class="controls" id="control-left">
                                <select name="PRO_ID" id="PRO_ID" class="input-medium">
                                    <option value="-1"><?=_("请选择")?></option>
                                    <?
                                    if($id != '')
                                    {
                                        $name=get_office_name($id);
                                        ?>
                                        <option value="<?=$id?>" selected><?=$name?></option>
                                        <?
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label control-align"><?=_("申请数量：")?><font style="color: red;padding-left: 1px;">*</font></label>
                            <div class="controls" id="control-left">
                                <input type="text" name="TRANS_QTY" id='TRANS_QTY' size="10" maxlength="20" class="input-small">
                            </div>
                        </div>
                    </div>
                    <div class="span1">
                        <div class="arrow-right">
                            <img src="<?=MYOA_STATIC_SERVER?>/static/images/arrow.png" alt="arrow-right">
                        </div>
                        <input type="button" class="btn btn-small btn-primary" style="margin-top:110px;" onClick="checkform_change();" value="调拨">
                    </div>
                    <div class="span5" style="border:1px solid #ccc;background:#fff; padding:10px;padding-top:30px;">
                        <div class="control-group">
                            <label class="control-label control-align"><?=_("办公用品库：")?><font style="color: red;padding-left: 1px;">*</font></label>
                            <div class="controls" id="control-left">
                                <select name="OFFICE_DEPOSITORY1" id="OFFICE_DEPOSITORY1" class="input-medium" onchange = "depositoryOfTypeOne(this.value,'1');">
                                    <option value="-1"><?=_("请选择")?></option>
                                    <?=get_depository('office_type',$_SESSION["LOGIN_DEPT_ID"])?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label id="OFFIClaE_TYPE" class="control-label control-align"><?=_("办公用品类别：")?><font style="color: red;padding-left: 1px;">*</font></label>
                            <div class="controls" id="control-left">
                                <select name="OFFICE_PROTYPE1" id="OFFICE_PROTYPE1" class="input-medium" onChange="depositoryOfProductsOne(this.value,'1');">
                                    <option value="-1"><?=_("请选择")?></option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label id="OFFICE_PRODUCTS" class="control-label control-align"><?=_("办公用品：")?></label>
                            <div class="controls" id="control-left">
                                <select name="PRO_ID1" id="PRO_ID1" class="input-medium">
                                    <option value="-1"><?=_("请选择")?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
<script>
    //调拨checkform
    function checkform_change()
    {
        var OFFICE_DEPOSITORY = $('#OFFICE_DEPOSITORY').val();
        if(OFFICE_DEPOSITORY=="-1")
        {
            alert("<?=_("请选择办公用品库")?>");
            $('#OFFICE_DEPOSITORY').focus();
            return (false);
        }

        var OFFICE_PROTYPE = $('#OFFICE_PROTYPE').val();
        if(OFFICE_PROTYPE=="-1")
        {
            alert("<?=_("请选择办公用品类别")?>");
            $('#OFFICE_PROTYPE').focus();
            return (false);
        }
        var PRO_ID = $('#PRO_ID').val();
        if(PRO_ID=="-1")
        {
            alert("<?=_("请选择办公用品")?>");
            $('#PRO_ID').focus();
            return (false);
        }

        var OFFICE_DEPOSITORY1 = $('#OFFICE_DEPOSITORY1').val();
        if(OFFICE_DEPOSITORY1=="-1")
        {
            alert("<?=_("请选择办公用品库")?>");
            $('#OFFICE_DEPOSITORY1').focus();
            return (false);
        }

        var OFFICE_PROTYPE1 = $('#OFFICE_PROTYPE1').val();
        if(OFFICE_PROTYPE1=="-1")
        {
            alert("<?=_("请选择办公用品类别")?>");
            $('#OFFICE_PROTYPE1').focus();
            return (false);
        }

        var PRO_ID1 = $('#PRO_ID1').val();
        var PRO_ID_TEXT1 = $('#PRO_ID_TEXT1').val();
        if(PRO_ID1=="-1"&&PRO_ID_TEXT1=="")
        {
            alert("<?=_("请选择办公用品")?>");
            $('#PRO_ID1').focus();
            return (false);
        }
        var reg = new RegExp("^[0-9]*$");
        var TRANS_QTY = $('#TRANS_QTY').val();
        if(TRANS_QTY=="")
        {
            alert("<?=_("申请数量不能为空")?>");
            $('#TRANS_QTY').val('').focus();
            return (false);
        }else if(TRANS_QTY=="0")
        {
            alert("<?=_("申请数量不能为0")?>");
            $('#TRANS_QTY').val('').focus();
            return (false);
        }else if(!reg.test(TRANS_QTY))
        {
            alert("<?=_("申请数量必须为正整数")?>");
            $('#TRANS_QTY').val('').focus();
            return (false);
        }else{
            check_pro_stock(PRO_ID,TRANS_QTY,'change');
            //document.form1.submit();
        }

    }
</script>
</html>
