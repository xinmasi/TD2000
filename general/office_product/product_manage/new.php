<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("../function_type.php");
include_once("inc/utility_file.php");
ob_end_clean();

$HTML_PAGE_TITLE = _("新建办公用品");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css"    href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css"    href="<?=MYOA_STATIC_SERVER?>/static/modules/office_product/css/product.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript"    src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/ajax.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/product_manage.js"></script>
<script>
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
String.prototype.trim= function()  
{  
    return this.replace(/(^\s*)|(\s*$)/g, "");  
}

function SelectManager(MODULE_ID,TO_ID, TO_NAME, FORM_NAME)
{
    var ID=document.getElementById("DEPOSITORY_ID").value;
    if(ID)
    {
        var OFFICE_TYPE_ID=true;
    }
    else
    {
        var OFFICE_TYPE_ID=false;
    }
    URL="/module/product_manager/?MODULE_ID="+MODULE_ID+"&TO_ID="+TO_ID+"&TO_NAME="+TO_NAME+"&ID="+ID+"&OFFICE_TYPE_ID="+OFFICE_TYPE_ID;
    loc_y=loc_x=200;
    if(is_ie)
    {
        loc_x=document.body.scrollLeft+event.clientX-100;
        loc_y=document.body.scrollTop+event.clientY+170;
    }
    LoadDialogWindow(URL,self,loc_x, loc_y, 250, 350);
}
</script>
<body>
    <div class="product_main_div">
        <form enctype="multipart/form-data" action="add.php" id="check_product" method="post" name="form1" style="margin: 0px;">
            <h3><?=_("新建办公用品")?></h3>
            <div class="center_div">
                <div class="wrap_center_div clear">
                    <!-- 左侧布局开始 -->
                    <div class="float_left info_left">
                        <div class="flied_info">
                            <span class="public_font_color padding_left_1"><?=_("办公用品名称")?><font style="color: red;padding-left: 1px;">*</font></span>
                            <input type="text" name="PRO_NAME" value="" class="filed_info_input">
                        </div>
                        <div>
                            <span class="public_font_color padding_left_3"><?=_("登记类型")?><font style="color: red;padding-left: 1px;">*</font></span>
                            <select name="OFFICE_TYPE2"class="filed_info_input">
                                <option value=""><?=_("请选择")?></option>
                                <option value='1'><?=_("领用")?></option>
                                <option value='2'><?=_("借用")?></option>
                            </select>
                        </div>
                        <div>
                            <span class="public_font_color padding_left_2"><?=_("办公用品库")?><font style="color: red;padding-left: 1px;">*</font></span>
                            <select name="OFFICE_DEPOSITORY" id="OFFICE_DEPOSITORY" class="filed_info_input" onchange = "depositoryOfTypeOne(this.value,'');">
                                <option value=""><?=_("请选择")?></option>
                                <?=get_depository('dept_aut',$_SESSION["LOGIN_DEPT_ID"])?>
                            </select>
                        </div>
                        <div>
                            <span class="public_font_color padding_left_1"><?=_("办公用品类别")?><font style="color: red;padding-left: 1px;">*</font></span>
                                <select  name="OFFICE_PROTYPE" class="filed_info_input" id="OFFICE_PROTYPE" onChange="depositoryOfProductsOne(this.value,'');">
                                    <option value=""><?=_("请选择")?></option>
                                </select>
                        </div>
                        <div>
                            <span class="public_font_color padding_left_1"><?=_("办公用品编码")?><font style="color: red;padding-left: 1px;">*</font></span>
                            <input type="text" name="PRO_CODE" value="<?=date('ymdhis').rand(10,100)?>" class="filed_info_input">
                        </div>
                        <div>
                            <span class="public_font_color padding_left_3"><?=_("当前库存")?><font style="color: red;padding-left: 1px;">*</font></span>
                            <input type="text" name="PRO_STOCK" value="" class="filed_info_input">
                        </div>
                        <div>
                            <span class="public_font_color padding_left_1"><?=_("最低警戒库存")?>&nbsp;</span>
                            <input type="text" name="PRO_LOWSTOCK" value="" class="filed_info_input">
                        </div>
                        <div>
                            <span class="public_font_color padding_left_1"><?=_("最高警戒库存")?>&nbsp;</span>
                            <input type="text" name="PRO_MAXSTOCK" value="" class="filed_info_input">
                        </div>
                        <div>
                            <span class="public_font_color padding_left_1"><?=_("审批权限(用户)")?></span> 
                            <input type="hidden" name="AUDIT_TO_ID" value="">
                            <textarea cols=37 name="AUDIT_TO_NAME" rows=2 wrap="yes" readonly class="filed_info_textarea"></textarea>
                            <a href="javascript:;" class="orgAdd" onClick="SelectManager('','AUDIT_TO_ID', 'AUDIT_TO_NAME')"><?=_("选择")?></a>
                            <a href="javascript:;" class="orgClear" onClick="ClearUser('AUDIT_TO_ID', 'AUDIT_TO_NAME')"><?=_("清空")?></a>
                        </div>
                    </div>
                    <!-- 左侧布局结束 -->
                    <!-- 右侧布局开始 -->
                    <div class="float_left info_right" style="margin-left: 30px;">
                        <div>
                            <span class="public_font_color padding_left_7"><?=_("规格/型号")?><font style="color: red;padding-left: 1px;">*</font></span>
                            <textarea cols=35 name="PRO_DESC" id="PRO_DESC" rows="1" wrap="yes" class="filed_info_textarea"></textarea>
                        </div>
                        <div id="attachment1">
                            <div style="float: left;">
                                <span class="public_font_color  padding_left_6" id="ATTACH_LABEL"><?=_("附件上传")?></span>
                            </div>
                            <div style="float: left; margin-left: 15px;">
                                <script>ShowAddFile();</script>
                                <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
                                <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
                            </div>
                        </div>
                        <div style="clear: both;">
                            <span class="public_font_color padding_left_6"><?=_("计量单位")?></span>
                            <input type="text" name="PRO_UNIT" value="" class="filed_info_input">
                        </div>
                        <div>
                            <span class="public_font_color padding_left_5"><?=_("单价")?></span>
                            <input type="text" name="PRO_PRICE" value="" class="filed_info_input">(元)
                </div>
                        <div>
                            <span class="public_font_color padding_left_4"><?=_("供应商")?></span>
                            <input type="text" name="PRO_SUPPLIER" value="" class="filed_info_input">
                        </div>
                        <div>
                            <span class="public_font_color padding_left_4"><?=_("创建人")?></span>
                            <input type="hidden" name="PRO_CREATOR" value="<?=$_SESSION['LOGIN_USER_ID']?>">
                            <input type="text" name="PRO_CREATORNAME" readonly value="<?=$_SESSION['LOGIN_USER_NAME']?>" class="filed_info_input"> 
                            <!--<span style="color: green;">创建人可以修改自己创建的办公用品信息。</span>-->
                        </div>
                        <div>
                            <span class="public_font_color"><?=_("登记权限(用户)")?></span>
                            <input type="hidden" name="COPY_TO_ID" value="">
                            <textarea cols=37 name="COPY_TO_NAME" rows=2 class="filed_info_textarea" wrap="yes" readonly></textarea>
                            <a href="javascript:;" class="orgAdd" onClick="SelectUser('126','','COPY_TO_ID', 'COPY_TO_NAME')">选择</a>
                            <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')">清空</a>
                        </div>
                        <div>
                            <span class="public_font_color"><?=_("登记权限(部门)")?></span>
                            <input type="hidden" name="TO_ID" value="">
                            <textarea cols=37 name=TO_NAME rows=2 wrap="yes" readonly class="filed_info_textarea"></textarea>
                            <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("添加")?></a>
                            <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a><br/><br/>
                            <div style="color: green;"><?=_("有登记权限的用户或部门，可以申请领用、借用该办公用品")?></div>
                        </div>
                    </div>
                    <!-- 右侧布局结束 -->
                </div>
            </div>
            <div class="bottom">
                <input type="button" onClick="check_product_manage();"    value="<?=_("保存")?>" class="btn  btn-primary bottom_left" title="<?=_("添加办公用品")?>" name="button" style="margin-top: 10px; width: 65px;">
                <input type="reset" value="<?=_("重填")?>" class="btn" style="margin-top: 10px; width: 65px;">
            </div>
            <input type="hidden" value="<?=$ATTACHMENT_ID?>" name="ATTACHMENT_ID_OLD">
            <input type="hidden" value="<?=$ATTACHMENT_NAME?>" name="ATTACHMENT_NAME_OLD">
            <input type="hidden" value="<?=$DEPOSITORY_ID?>" id="DEPOSITORY_ID" name="DEPOSITORY_ID">
        </form>
    </div>
</body>
</html>