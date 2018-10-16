<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("../function_type.php");

$HTML_PAGE_TITLE = _("办公用品申领");
include_once("inc/header.inc.php");

$DEPT_ID_STR = td_trim($_SESSION["LOGIN_DEPT_ID"].",".$_SESSION["LOGIN_DEPT_ID_OTHER"]);

?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/numberpicker/numberpicker.css" />
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery-ui/css/flick/jquery-ui-1.10.3.custom.min.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/module.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/numberpicker/numberpicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/ajax.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/mohu_query.js"></script>
<? 
    if(isset($id))
    {
        if($type == '2')//审批左侧申领入口
        {
            $ROW['PRO_ID'] = $id;
            $product_num = get_product_num($id);
            $PRO_PRICE   = $product_num['pro_price'];
        }
        else if($type == '1'){//申领记录中修改等入口
            $query="SELECT * FROM office_transhistory WHERE borrower='{$_SESSION['LOGIN_USER_ID']}' and trans_id='{$id}' ORDER BY trans_date desc";
            $cursor = exequery ( TD::conn (), $query );
            $ROW = mysql_fetch_array ( $cursor ); 
            $PRO_PRICE   = $ROW['PRICE'];
       }else{
               $query="SELECT * FROM office_transhistory WHERE borrower='{$_SESSION['LOGIN_USER_ID']}' and trans_id='{$id}' ORDER BY trans_date desc";
            $cursor = exequery ( TD::conn (), $query );
            $ROW = mysql_fetch_array ( $cursor );
       }
       $sql="select pro_stock from office_products where pro_id='{$ROW['PRO_ID']}'";
        $cursor3 = exequery ( TD::conn (), $sql );
        $ROW3 = mysql_fetch_array ( $cursor3 );
    }
?>
<script>

//新增数量选择器插件numberpicker
$(document).ready(function(){
    var set_exit = $('#TRANS_QTY').val();
    var mystock = 0;
    if(set_exit =='0')//初始化
    {
        set_exit = 0;
        myeditable = false;
    }else{//编辑页面初始化
        myeditable = true;
        mystock = $('#STOCK').val();
    }
    $("#np").dpNumberPicker({
        value: parseInt(set_exit),
        editable: myeditable,
        min: 0,         
        max: mystock,
        afterChange: function(){
            var num = $(this).find("input").val();//获取input的值
            $('#TRANS_QTY').val(num);//将值放入隐藏域中
            if(num>=parseInt(mystock)){
                $('#StockAlert').show();
            }
            else{
            $('#StockAlert').hide();
            }
            $('#TRANS_QTY').val(num);//将值放入隐藏域
        },
    });
});
function ADD_TRANS_FLAG(id)
{
    depositoryOfTypeOne(-1,'');
    $('#TRANS_FLAG_LOG').val(id);    
}
</script>
<body>
<div class="row-fluid" align="center">
    <div class='span8' style='float:none;'>
        <h3 style="text-align:left;"><?=isset($type)?($type==1?_('修改'):_('新建')):(isset($id)?_('归还'):_('新建'))?>办公用品申领</h3>
        <form enctype="multipart/form-data" action="oneapply_add.php"  method="post" name="form1">    
        <input type="hidden" id="office_id" name='office_id' value="<?=$id?>">
        <input type="hidden" id="office_type" name='office_type' value="<?=$type?>">
        <input type="hidden" id="curpage" name='curpage' value="<?=$curpage?>">      
            <table class="table table-bordered table-hover">
                <tr>
                    <td class="align-right" style='width: 120px;'><?=_("登记类型：")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td>
                        <select class="input-xlarge" name="TRANS_FLAG" onChange="ADD_TRANS_FLAG(this.value);">
                            <option value="1" ><?=_("领用/借用")?></option>
                            <!--<option value="3" <?=isset($type)?($type==1?'':''):(isset($id)?'selected':'')?>><?=_("归还")?></option>-->
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("办公用品库:")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td class='set_only'>
                        <select name="OFFICE_DEPOSITORY" id="OFFICE_DEPOSITORY" class="input-xlarge" onchange = "depositoryOfTypeOne(this.value,'');">
                            <option value="-1"><?=_("请选择")?></option>
                            <? 
                                if(isset($id))
                                {
                                    $type2=get_office_depository($ROW['PRO_ID']);
                                }else{
                                    $type2=-1;
                                }
                            ?>
                            <?=get_depository('dept',$DEPT_ID_STR,$type2)?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("办公用品类别:")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td id="OFFICE_TYPE"  class='set_only'>
                        <select name="OFFICE_PROTYPE" id="OFFICE_PROTYPE" class="input-xlarge" onChange="depositoryOfProductsOne(this.value,'');">
                            <option value="-1"><?=_("请选择")?></option>
                            <? 
                                if(isset($id))
                                {
                                    $name=get_office_type($ROW['PRO_ID']);
                            ?>
                                 <option value="<?=$ROW['PRO_ID']?>" selected><?=$name?></option>
                            <? 
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("办公用品:")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td id="OFFICE_PRODUCTS"  class='set_only'>
                        <select name="PRO_ID" id="PRO_ID" class="input-xlarge" onChange="GetProduct(this.value);">
                            <option value="-1"><?=_("请选择")?></option>
                            <? 
                                if(isset($id))
                                {
                                    $name=get_office_name($ROW['PRO_ID']);
                            ?>
                                 <option value="<?=$ROW['PRO_ID']?>" selected><?=$name?></option>
                            <? 
                                }
                            ?>
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
                <tr id="PRICCE">
                    <td class="align-right"><?=_("单 价")?>:</td>
                    <td>
                        <input type="text" name="THIS_PRICCE" id="THIS_PRICCE" size="5" class="input-large" value="<?=$PRO_PRICE?>" disabled>
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("申请数量:")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td>
                        <div id="np"></div>
                        <input type="hidden" id="TRANS_QTY" name='TRANS_QTY' value="<?=$type==2?'1':abs($ROW['FACT_QTY'])?>">
                        <input type="hidden" id="STOCK" name='STOCK' value="<?=isset($type)?($type==1?$ROW3['pro_stock']:$ROW3['pro_stock']):(isset($id)?abs($ROW['FACT_QTY']):'0')?>">
                        <input type="hidden" id="set_stock_tag"  value="1">
                         <span id="StockAlert" class="text-error" style="display:none;line-height:31px;"><?=isset($type)?($type==1?_('最大库存为'):_('最大库存为')):(isset($id)?_('最多可归还'):_('最大库存为'))?><span id="getstock"><?=isset($type)?($type==1?$ROW3['pro_stock']:$ROW3['pro_stock']):(isset($id)?abs($ROW['FACT_QTY']):$ROW3['pro_stock'])?></span>！</span>
                    </td>
                </tr>   
                <tr>
                    <td class="align-right"><?=_("部门审批人:")?></td>
                    <td>
                        <input type="hidden" name="DEPT_MANAGER" value="<?=$ROW['DEPT_MANAGER']?>">     
                        <input type="text" name="DEPT_MANAGER_NAME" id="disabledInput" size="13" class="BigStatic" value="<?=td_trim(GetUserNameById($ROW['DEPT_MANAGER']))?>" disabled>&nbsp;
                        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('126','','DEPT_MANAGER', 'DEPT_MANAGER_NAME')"><?=_("选择")?></a>
                        <br><?=_("为空时，直接由办公用品管理员审批; 不为空时，先交给部门审批人审批，再由办公用品管理员审批;")?>                    
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("备注:")?></td>
                    <td>
                        <textarea name="REMARK" id="REMARK" cols="45" rows="5" class="input" style="margin:0px;" maxlength="40"><?=$ROW['REMARK']?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style='text-align: center;'>
                        <input type="hidden" id="TRANS_FLAG_LOG" name="TRANS_FLAG_LOG" value="1">
                        <input type="button" class="btn btn-small btn-primary" style="margin-top:5px;" onClick="CheckForm();" value="<?=_("确认")?>">
                        <? 
                        if(!isset($id)){
                        ?>
                         <input type="reset" class="btn btn-small " style="margin-top:5px;" value="<?=_("重置")?>" onClick="clear_stock();">
                        <? 
                        }else{
                        ?>
                        <input type="reset" class="btn btn-small " onClick="history.go(-1)" style="margin-top:5px;" value="<?=_("返回")?>">
                        <?
                            }
                        ?>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
</body>
</html>
