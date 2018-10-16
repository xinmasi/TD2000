<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("../function_type.php");

$HTML_PAGE_TITLE = _("库存维护");
include_once("inc/header.inc.php");
$action =stripslashes($_GET['action']);
if($action=='one')
{
    $display_set_two = 'display:none';
    $display_set_one = 'display:block';
    $title = _('库存维护');
    //调用方法查询此类型列表  可以传参数，也可以调用2个不同方法
}else if($action=='two') {
    $display_set_two = 'display:block';
    $display_set_one = 'display:none';
    $title = _('待登记');
    //调用方法查询此类型列表
}
$res2 = array();
if($id != '')
{
    $query="SELECT * FROM office_transhistory WHERE trans_id='{$id}' ";
    $cursor = exequery ( TD::conn (), $query );
    $res2 = mysql_fetch_array($cursor);
    $BORROWER_NAME = GetUserNameById($res2['BORROWER']);
    
    //获取办公用品库存
    $pro_id = $res2['PRO_ID'];
    $res2_pro = get_product_num($pro_id);
    $STOCK = $res2_pro['pro_stock'];
}
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/office_product/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/numberpicker/numberpicker.css" />
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/jquery-1.10.2/jquery-ui/css/flick/jquery-ui-1.10.3.custom.min.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/numberpicker/numberpicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/ajax.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/mohu_query.js"></script>
<script>

//新增数量选择器插件numberpicker
$(document).ready(function(){
    var set_exit = $('#TRANS_QTY').val();
    if(set_exit =='')//初始化
    {
        set_exit = 0;
        myeditable = false;
        }else{//编辑页面初始化
            myeditable = true;
            var mystock = $('#STOCK').val();
        }
    $("#np").dpNumberPicker({
             value: parseInt(set_exit),
             min: 0,         
             max: mystock,
             editable: myeditable,
             afterChange: function(){
                var num = $(this).find('input').val();//获取input的值
                $("#getstock").html(mystock);
                if(num>=parseInt(mystock)){
                    $('#StockAlert').show();
                }
                else{
                $('#StockAlert').hide();
                }
                $('#TRANS_QTY').val(num);//将值放入隐藏域中
             },
         });
});
</script>
<body>

<div class="row-fluid" align="center">
    <div class='span8' style='float:none;'>
        <div class='top_left'>
            <h3><?=$title?></h3>
        </div>
        <form enctype="multipart/form-data" action="oneapply_add.php" name="form1" id="form1"  method="post">
        <input type="hidden" name="TRANS_ID" value="<?=$res2['TRANS_ID']?>"> 
        <div class='top_right'>
            <input type="reset" class="btn btn-small btn-primary" value="<?=_("重置")?>">
        </div>
            <table class="table table-bordered table-hover">
                <tr>
                    <td class="align-right" style='width: 120px;'><?=_("登记类型：")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td>
                        <select class="input-xlarge" id="TRANS_FLAG" name="TRANS_FLAG" style='<?=$display_set_one?>'>
                            <option value="-1"><?=_("---请选择---")?></option>
                            <option value="0" <?=$res2['TRANS_FLAG']=='0'?'selected':''?>><?=_("采购入库")?></option>
                            <option value="5" <?=$res2['TRANS_FLAG']=='5'?'selected':''?>><?=_("维护")?></option>
                            <option value="4" <?=$res2['TRANS_FLAG']=='4'?'selected':''?>><?=_("报废")?></option>
                        </select>
                        <select class="input-xlarge" id="TRANS_FLAG2" name="TRANS_FLAG2" style='<?=$display_set_two?>'>
                            <option value="-1"><?=_("---请选择---")?></option>
                            <option value="1" <?=$res2['TRANS_FLAG']==('1'||'2')?'selected':''?>><?=_("领用/借用")?></option>
                            <option value="3" <?=$res2['TRANS_FLAG']=='3'?'selected':''?>><?=_("归还")?></option>
                        </select>
                    </td>
                </tr>
                <tr style='<?=$action=='two'?'':'display:none'?>'>
                    <td class="align-right"><?=_("申请人：")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td>
                        <input type="hidden" name="TO_ID" id="TO_ID" value="<?=$res2['BORROWER']?>">  
                        <input type="text" name="TO_NAME" id="TO_NAME" size="20" maxlength="20"  value="<?=$BORROWER_NAME?>"  style="margin-bottom:0px;" disabled>
                        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('126','','TO_ID','TO_NAME')"><?=_("选择")?></a>
                        <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("办公用品库：")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td class='set_only'>
                        <select name="OFFICE_DEPOSITORY" id="OFFICE_DEPOSITORY" class="input-xlarge" onchange = "depositoryOfTypeOne(this.value,'');">
                            <option value="-1"><?=_("请选择")?></option>
                            <? 
                                if($id !='')
                                {
                                    $type=get_office_depository($res2['PRO_ID']);
                                }else{
                                    $type=-1;
                                }
                            ?>
                            <?=get_depository('dept',$_SESSION["LOGIN_DEPT_ID"],$type)?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("办公用品类别：")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td class='set_only'>
                        <select name="OFFICE_PROTYPE" id="OFFICE_PROTYPE"  class="input-xlarge" onChange="depositoryOfProductsOne(this.value,'');">
                            <option value="-1"><?=_("请选择")?></option>
                            <? 
                                if($id !='')
                                {
                                    $name=get_office_type($res2['PRO_ID']);
                            ?>
                                 <option value="<?=$res2['PRO_ID']?>" selected><?=$name?></option>
                            <? 
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("办公用品：")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td class='set_only'>
                        <select name="PRO_ID"  id="PRO_ID" class="input-xlarge" onChange="GetProduct(this.value);">
                            <option value="-1"><?=_("请选择")?></option>
                            <? 
                                if($id != '')
                                { 
                                    $name=get_office_name($res2['PRO_ID']);
                            ?>
                                 <option value="<?=$res2['PRO_ID']?>" selected><?=$name?></option>
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
                        <input type="text" id="project-id" name='project_id'>
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("申请数量：")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td>
                        <div id="np"></div>
                        <input type="text" name="TRANS_ID" id='TRANS_ID' value='<?=$id; ?>' />
                        <input type="hidden" id="TRANS_QTY" name='TRANS_QTY' value="<?=$res2['FACT_QTY']?>">
                        <input type="text" name="PRO_ID_OLD" id='PRO_ID_OLD' value='<?=$res2['PRO_ID']; ?>' />
                        <input type="hidden" name="TRANS_QTY_OLD" id='TRANS_QTY_OLD' value='<?=$res2['FACT_QTY']; ?>' />
                        <input type="text" id="STOCK" name='STOCK' value="<?=$STOCK?>">
                        <input type="hidden" id="set_stock_tag"  value="1">
                         <span id="StockAlert" class="text-error" style="display:none;line-height:31px;">最大库存为<span id="getstock"></span>！</span>
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("备注：")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td>
                        <textarea name="REMARK" id="REMARK" cols="45" rows="5"><?=$res2['REMARK']?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style='text-align: center;'>
                        <input type="hidden" id="action" value="<?=$action; ?>">
                        <input type="button" class="btn btn-small btn-primary" style="margin-top:5px;" onClick="CheckForm();" value="<?=_("确认")?>">
                    </td>
                </tr>
            </table>
        </form>
    </div>    
    <? 
    $CUR_DATE = date ( "Y-m-d", time () );
    if($action=='one')
    {
        $TRANS_FLAG = '0,4,5';
    }else if($action=='two') {
       $TRANS_FLAG = '1,2,3';
    }
    $query = "SELECT * from OFFICE_TRANSHISTORY where TRANS_FLAG in (".$TRANS_FLAG.") and OPERATOR='" . $_SESSION ["LOGIN_USER_ID"] . "' and TRANS_DATE = '". $CUR_DATE ."' order by TRANS_ID desc";
    echo  $query;
    $cursor = exequery ( TD::conn (), $query );
    if(mysql_num_rows ( $cursor )==0){
        Message(_('提示'), _('暂无今日操作记录'));
        exit;
    }
    ?>
    <div class='span11'>
        <div style='float:left;'>
            <img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" WIDTH="22" HEIGHT="22" align="absmiddle">
            <span class="big3">&nbsp;<?=_("今日操作查看")?></span>
        </div>
        <table class="table table-bordered center table-hover">
            <tr style="background: #e4f1f9;">
                <td><?=_("办公用品名称")?></td>
                <td><?=_("登记类型")?></td>
                <td><?=_("领用/借用/归还人")?></td>
                <td><?=_("数量")?></td>
                <td><?=_("当前库存")?></td>
                <td><?=_("单价")?></td>
                <td><?=_("操作日期")?></td>
                <td><?=_("操作员")?></td>
                <td><?=_("操作")?></td>
            </tr>
            <? 
                $arr = array(0=>_('采购入库'),1=>_('领用'),2=>_('借用'),3=>_('归还'),4=>_('报废'),5=>_('维护'));
                $res = array();
                while ( $ROW = mysql_fetch_array ($cursor) ) {
                    $name  = get_office_name($ROW['PRO_ID']);
                    $res   = get_product_num($ROW['PRO_ID']);
                    $num   = $res['pro_price'];
                    $stock = $res['pro_stock'];
            ?>
            <tr>
                <td><?=$name?></td>
                <td><?=$arr[$ROW['TRANS_FLAG']]?></td>
                <td><?=$ROW['BORROWER']?></td>
                <td><?=$ROW['FACT_QTY']?></td>
                <td><?=$stock?></td>
                <td><?=$num?></td>
                <td><?=$ROW['TRANS_DATE']?></td>
                <td><?=$ROW['OPERATOR']?></td>
                <td>
                <?
                    $a=($ROW['TRANS_FLAG']>=1&&$ROW['TRANS_FLAG']<=3)?'two':'one';
                ?>
                <a href="<? echo "query_list.php?action=$a";?>&id=<?=$ROW['TRANS_ID']?>"><span><?=_("修改")?></span></a>&nbsp&nbsp
                <a href="javascript:void(0):"><span class= 'trans_del' id='<?=$ROW['TRANS_ID']?>'><?=_("放弃操作")?></span></a>
                </td>
            </tr>
            <? } ?>
        </table>
    </div>
</div>
</body>
<script>

function CheckForm()
{
    var PRO_NAME = $('#PRO_NAME').val();
    var OFFICE_DEPOSITORY = $('#OFFICE_DEPOSITORY').val();

    var TRANS_FLAG = $("#TRANS_FLAG").val();
    var TRANS_FLAG2 = $("#TRANS_FLAG2").val();
    var action = $("#action").val();
    if(TRANS_FLAG =='-1' && TRANS_FLAG2 == '-1'){
        alert("<?=_("修改")?>请选择登记类型！");
        if(action =='one'){$("#TRANS_FLAG").focus();}else{$("#TRANS_FLAG2").focus();}        
        return false;
    }
    //申请人不能为空
    var TO_ID = $("#TO_ID").val();
    if((TO_ID =='') && (TRANS_FLAG == '-1'))
    {
        alert("<?=_("请填写申请人")?>");
        $("#TO_NAME").focus();
        return false;
    }
    if($("#mytag").val() == '1')
    {
        if(OFFICE_DEPOSITORY=="-1")
        { 
             alert("<?=_("请选择办公用品库")?>");
             $('#OFFICE_DEPOSITORY').focus();
             return false;
        }
        
        var OFFICE_PROTYPE = $('#OFFICE_PROTYPE').val();
        if(OFFICE_PROTYPE=="-1")
        { 
             alert("<?=_("请选择办公用品类别")?>");
             $('#OFFICE_PROTYPE').focus();
             return false;
        }
        
        var PRO_ID = $('#PRO_ID').val();
        var PRO_ID_TEXT = $('#PRO_ID_TEXT').val();
        if(PRO_ID=="-1" || PRO_ID_TEXT=="")
        { 
             alert("<?=_("请选择办公用品")?>");
             $('#PRO_ID').focus();
             return false;
        }
    }
    else
    {
        if(OFFICE_DEPOSITORY =="-1" && PRO_NAME == '')
        {
            alert("<?=_("办公用品模糊名称不能为空")?>");
             $('#PRO_NAME').focus();
             return false;
        }
    }
    var TRANS_QTY = $('#TRANS_QTY').val();
    if(TRANS_QTY == '' || TRANS_QTY == '0')
    {
         alert("<?=_("申请数量不能为0")?>");
         $('#np').focus();
         return false;
    }else if(checknum(TRANS_QTY)=="0") 
    { 
         alert("<?=_("申请数量必须为数字")?>");
         $('#np').focus();
         return false;
    }
//     else{
//         check_pro_stock(PRO_ID,TRANS_QTY,'apply');
//     }
    var REMARK = $('#REMARK').val();
    if(REMARK=="")
    { 
         alert("<?=_("备注信息不能为空")?>");
         $('#REMARK').focus();
         return false;
    }
    if(TRANS_QTY){
        var STOCK = $("#STOCK").val();
        var TRANS_QTY = $("#TRANS_QTY").val();
        var PRO_ID = $("#PRO_ID").val();
//         if((TRANS_FLAG2 =='-1' && TRANS_FLAG != '0') || (TRANS_FLAG2 !='3' && TRANS_FLAG == '-1')){//非采购入库和归还
//             if(STOCK<TRANS_QTY)
//             {
//                 alert("申请数量大于库存，请重新填写");
//                 return false;
//             }
//         }else 
        if(TRANS_FLAG2 == '3')//归还 是否存在借用，并且归还数量是否大于借用数量
        { 
            alert(TRANS_FLAG2+PRO_ID);
        }
        document.form1.submit();
    }
    else
    {
        alert(TRANS_QTY+'----'+REMARK);
    }
    
}
</script>
</html>
