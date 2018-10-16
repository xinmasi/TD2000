<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("../function_type.php");
include_once("inc/utility_all.php");

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
    $title = _('代登记');
    //调用方法查询此类型列表
}
$res2 = array();
if($id != '')
{
    $query  = "SELECT * FROM office_transhistory WHERE trans_id='{$id}' ";
    $cursor = exequery(TD::conn (),$query);
    $res2   = mysql_fetch_array($cursor);
    
    $BORROWER_NAME = substr(GetUserNameById($res2['BORROWER']),0,-1);
       
    $FACT_QTY   = $res2['FACT_QTY'];
    $TRANS_FLAG = $res2['TRANS_FLAG'];
    $pro_id     = $res2['PRO_ID'];

    //获取办公用品库存
    
    $res2_pro   = get_product_num($pro_id);
    $STOCK      = $res2_pro['pro_stock'];
    $PRO_PRICE  = $res2_pro['pro_price'];
    
}

//修改事务提醒状态--yc
update_sms_status('43,46',0);
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/office_product/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/numberpicker/numberpicker.css" />
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/jquery-1.10.2/jquery-ui/css/flick/jquery-ui-1.10.3.custom.min.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js"></script><script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/numberpicker/numberpicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/ajax.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/mohu_query.js"></script>
<script type="text/javascript" src="/module/DatePicker/WdatePicker.js"></script>
<script>

//新增数量选择器插件numberpicker
$(document).ready(function(){
    var set_exit = $('#TRANS_QTY').val();
    var mystock = 0;
    if(set_exit =='')//初始化
    {
        set_exit = 0;
        myeditable = false;
    }else{//编辑页面初始化
        
        $("#TRANS_FLAG").attr("disabled","disabled");
        $("#TRANS_FLAG2").attr("disabled","disabled");
        $("#OFFICE_DEPOSITORY").attr("disabled","disabled");
        $("#OFFICE_PROTYPE").attr("disabled","disabled");
        $("#PRO_ID").attr("disabled","disabled");
        
        var THISFLAG  =  $('#THISFLAG').val();
        myeditable = true;
        mystock = $('#STOCK').val();
        if(THISFLAG==0)
        {
            $('#np').hide();
            $('#np1').show();
        }
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
    $("#np1").dpNumberPicker({
            value: parseInt(set_exit),
            min: 0,         
            max: 99999,
            editable: true,
            afterChange: function(){
            var num = $(this).find('input').val();//获取input的值
            $('#TRANS_QTY').val(num);//将值放入隐藏域中
            },
    });
});

function CheckForm1()
{
    var PRO_NAME = $('#PRO_NAME').val();
    var OFFICE_DEPOSITORY = $('#OFFICE_DEPOSITORY').val();

    var TRANS_FLAG = $("#TRANS_FLAG").val();
    var TRANS_FLAG2 = $("#TRANS_FLAG2").val();
    var action = $("#action").val();
    if(TRANS_FLAG =='-1' && TRANS_FLAG2 == '-1'){
        alert("<?=_("请选择登记类型")?>");
        if(action =='one'){$("#TRANS_FLAG").focus();}else{$("#TRANS_FLAG2").focus();}        
        return false;
    }
    //申请人不能为空
    var TO_ID = $("#TO_ID").val();
    if( (TO_ID =='') && (TRANS_FLAG == '-1')){
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
    
    if((TRANS_QTY == '' || TRANS_QTY == '0') && form1.TRANS_FLAG.value!=5 && form1.TRANS_FLAG2.value!=3)
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
    
    var REMARK = $('#REMARK').val();
    if(REMARK=="")
    { 
         alert("<?=_("备注信息不能为空")?>");
         $('#REMARK').focus();
         return false;
    }
    if(form1.TRANS_FLAG.value=="5")
    {
        if($('#REP_TIME1').val()=="" || $('#REP_TIME2').val()=="")
        {
            alert("<?=_("维护日期不能为空")?>");
            return false;
        }
        
        
        var controldate  = $('#REP_TIME1').val();
        var controldates = $('#REP_TIME2').val();
        var day = new Date();
        var Year = 0; 
        var Month = 0; 
        var Day = 0; 
        var CurrentDate = ""; 
        //初始化时间 
        Year = day.getFullYear(); 
        Month = day.getMonth()+1; 
        Day = day.getDate(); 
        CurrentDate += Year + "-"; 
        if(Month >= 10) 
        {
            CurrentDate += Month + "-"; 
        } 
        else 
        {
            CurrentDate += "0" + Month + "-"; 
        } 
        if(Day >= 10) 
        {
            CurrentDate += Day ; 
        } 
        else 
        {
            CurrentDate += "0" + Day ; 
        }
        var startDate = new Date(CurrentDate.replace("-",",")).getTime() ; 
        var endDate   = new Date(controldate.replace("-",",")).getTime() ;
        var endDates  = new Date(controldates.replace("-",",")).getTime() ;
            
        if(startDate >= endDate) 
        {
            alert("<?=_("维护日期必须大于当前日期")?>");
            return false;
        }
        if(endDate>endDates)
        {
            alert("<?=_("结束日期不得小于开始日期")?>");
            return false;
        }
            
        if($('#REM_TIME').val()!="")
        {
            var remtime = $('#REM_TIME').val();
            var endDate1 = new Date(remtime.replace("-",",")).getTime() ;
            if(startDate > endDate1)
            {
                alert("<?=_("提醒日期不能小于当前日期")?>");
                return false;
            }
        }
    }
    if(form1.TRANS_FLAG.value=="0")
    {
        var STOCK = $("#STOCK").val();
        var TRANS_QTY = $("#TRANS_QTY").val();
        var PRO_ID = $("#PRO_ID").val();
        $.post('get_check.php', {
            PRO_ID: PRO_ID,
            PRO_NAME: PRO_NAME,
            TRANS_QTY: TRANS_QTY
            }, function(ret){
                ret = $.trim(ret);
                if(ret != "ok"){
                    alert("<?=_("采购数量超过最高警戒库存")?>");
                    return false;
                }else
                {
                    document.form1.submit();
                }
        });    
    }else
    {
        var STOCK = $("#STOCK").val();
        var TRANS_QTY = $("#TRANS_QTY").val();
        var PRO_ID = $("#PRO_ID").val();
        document.form1.submit();
    }
      
}
function sel_change()
{
    if(form1.TRANS_FLAG.value=="5" || form1.TRANS_FLAG2.value=="3")
    {
        if(form1.TRANS_FLAG.value=="5")
        {
            document.getElementById("REPAIR1").style.display="";
            document.getElementById("REPAIR2").style.display="";
            //document.getElementById("REMTIME").style.display="";
        }
        document.getElementById("PCOUNT").style.display="none";
    }
    else if(form1.TRANS_FLAG.value=="0")
    {
        document.getElementById("REPAIR1").style.display="none";
        document.getElementById("REPAIR2").style.display="none";
        document.getElementById("REMTIME").style.display="none";
        document.getElementById("PCOUNT").style.display="";
        document.getElementById("PRICCE").style.display="";
        
        document.getElementById("np").style.display="none";
        document.getElementById("StockAlert").style.display="none";
        document.getElementById("np1").style.display="";
    }
    else
    {
        document.getElementById("REPAIR1").style.display="none";
        document.getElementById("REPAIR2").style.display="none";
        document.getElementById("REMTIME").style.display="none";
        document.getElementById("PCOUNT").style.display="";
        document.getElementById("PRICCE").style.display="none";
        
        document.getElementById("np1").style.display="none";
        document.getElementById("np").style.display="";
    }
    if(form1.TRANS_FLAG.value=="4")
    {
        document.getElementById("SMS").style.display="none";
    }else
    {
        document.getElementById("SMS").style.display="";
    }
}
</script>
<body>

<div class="row-fluid" align="center">
    <div class='span8' style='float:none;'>
        <div style="text-align:left;">
            <h3><?=$title?></h3>
        </div>
        <form enctype="multipart/form-data" action="oneapply_add.php" name="form1" id="form1"  method="post">
        <input type="hidden" name="TRANS_ID" value="<?=$res2['TRANS_ID']?>"> 
            <table class="table table-bordered table-hover">
                <tr>
                    <td class="align-right" style='width: 120px;'><?=_("登记类型：")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td>
                        <select class="input-xlarge" id="TRANS_FLAG" name="TRANS_FLAG" style='<?=$display_set_one?>' onChange="sel_change()">
                            <option value="-1">---请选择---</option>
                            <option value="0" <?=$res2['TRANS_FLAG']=='0'?'selected':''?> onChange="get_price(1)">采购入库</option>
                            <option value="5" <?=$res2['TRANS_FLAG']=='5'?'selected':''?>><?=_("维护")?></option>
                            <option value="4" <?=$res2['TRANS_FLAG']=='4'?'selected':''?>><?=_("报废")?></option>
                        </select>
                        <select class="input-xlarge" id="TRANS_FLAG2" name="TRANS_FLAG2" style='<?=$display_set_two?>' onChange="sel_change()">
                            <option value="-1"><?=_("---请选择---")?></option>
                            <option value="1" <?=$res2['TRANS_FLAG']==('1'||'2')?'selected':''?>><?=_("领用/借用")?></option>
                            <option value="3" <?=$res2['TRANS_FLAG']=='3'?'selected':''?>><?=_("归还")?></option>
                        </select>
                    </td>
                </tr>
                <tr style='<?=$action=='two'?'':'display:none'?>'>
                    <td class="align-right"><?=_("维护 申请人：")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td>
                        <input type="hidden" name="TO_ID" id="TO_ID" value="<?=$res2['BORROWER']?>">  
                        <input type="text" name="TO_NAME" id="TO_NAME" size="20" maxlength="20"  value="<?=$BORROWER_NAME?>"  style="margin-bottom:0px;" disabled>
                        <? if($id==""){?>
                        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('126','','TO_ID','TO_NAME')"><?=_("选择")?></a>
                        <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
                        <?}?>
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
                            <?=get_depository('office_type',$_SESSION["LOGIN_DEPT_ID"],$type)?>
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
                       <? if($id==""){?> <input id="TOGGLE_BLUR" type="button" name="SelectPro" title="<?=_("模糊选择")?>" value="<?=_("模糊选择")?>" class="btn btn-small btn-info"><?}?>
                    </td>
                </tr>
                <tr id="BLURRED" style="display:none">
                    <td class="align-right"><?=_("模糊名称")?>:</td>
                    <td>
                        <input type="hidden" id="mytag" name='mytag' value='1'>
                        <input type="text" id="PRO_NAME" name="PRO_NAME" size="20" maxlength="20" class="input-large" style="margin:0px;" value="">&nbsp;&nbsp;
                    </td>
                </tr>
                <tr id="REPAIR1" style="display:none">
                    <td class="align-right"><?=_("维护开始日期")?>:</td>
                    <td>
                      <input type="text" name="REP_TIME1" id="REP_TIME1" size="20" maxlength="20" class="BigInput" value="<?=$REP_TIME?>" onClick="WdatePicker()"> 
                    </td>
                </tr>
                <tr id="REPAIR2" style="display:none">
                    <td class="align-right"><?=_("维护结束日期")?>:</td>
                    <td>
                      <input type="text" name="REP_TIME2" id="REP_TIME2" size="20" maxlength="20" class="BigInput" value="<?=$REP_TIME?>" onClick="WdatePicker()"> 
                    </td>
                </tr>
                <tr id="REMTIME" style="display:none">
                    <td class="align-right"><?=_("提醒日期")?>:</td>
                    <td>
                      <input type="text" name="REM_TIME" id="REM_TIME" size="20" maxlength="20" class="BigInput" value="" onClick="WdatePicker()"> 
                    </td>
                </tr>
                <tr id="PRICCE">
                    <td class="align-right"><?=_("单 价")?>:</td>
                    <td>
                      <input type="text" name="THIS_PRICCE" id="THIS_PRICCE" size="5" class="input-large" value="<?=$PRO_PRICE?>" disabled> 
                    </td>
                </tr>
                <tr id="PCOUNT";>
                    <td class="align-right"><?=_("申请数量：")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td>
                        <div id="np"></div>
                        <div id="np1" style="display:none;"></div>
                        <input type="hidden" name="TRANS_ID" id='TRANS_ID' value='<?=$id; ?>' />
                        <input type="hidden" id="TRANS_QTY" name='TRANS_QTY' value="<?=$FACT_QTY?>">
                        <input type="hidden" id="THISFLAG" name='THISFLAG' value="<?=$TRANS_FLAG?>">
                        <input type="hidden" id="TRANS_QTY_OLD" name='TRANS_QTY_OLD' value="<?=$FACT_QTY?>">
                        <input type="hidden" name="PRO_ID_OLD" id='PRO_ID_OLD' value='<?=$res2['PRO_ID']; ?>' />
                        <input type="hidden" id="STOCK" name='STOCK' value="<?=$STOCK?>">
                        <input type="hidden" id="set_stock_tag"  value="1">
                        <span id="StockAlert" class="text-error" style="display:none;line-height:31px;">最大库存为<span id="getstock"></span>！</span>
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("备注：")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td>
                        <textarea name="REMARK" id="REMARK" cols="45" rows="5" maxlength="40"><?=$res2['REMARK']?></textarea>
                    </td>
                </tr>
                <tr id="SMS" <? if($TRANS_FLAG=='4'){?>style="display:none;"<?}?>>
                    <td class="align-right"><?=_("提醒：")?></td>
                    <td>
                        <?=sms_remind(46);?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style='text-align: center;'>
                        <input type="hidden" id="action" name="action"  value="<?=$action; ?>">
                        <input type="button" class="btn btn-small btn-primary" style="margin-top:5px;" onClick="CheckForm1();" value="<?=_("确认")?>">
                        <input type="reset" class="btn btn-small" style="margin-top:5px;" value="<?=_("重置")?>" <? if($id!=""){?> disabled <? }?> onClick="clear_stock();">
                    </td>
                </tr>
            </table>
        </form>
    </div>    
    <? 
    $CUR_DATE = date("Y-m-d",time());
    if($action=='one')
    {
        $TRANS_FLAG = '0,4,5';
    }elseif($action=='two')
    {
       $TRANS_FLAG = '1,2';
    }
    $query = "SELECT * from OFFICE_TRANSHISTORY where TRANS_FLAG in (".$TRANS_FLAG.") and OPERATOR='" . $_SESSION ["LOGIN_USER_ID"] . "' and TRANS_DATE = '". $CUR_DATE ."' and TRANS_STATE = 1 and TRANS_FLAG <> 6 order by TRANS_ID desc";
    $cursor = exequery(TD::conn(),$query);
    if(mysql_num_rows ($cursor)==0)
    {
        Message(_('提示'), _('暂无今日操作记录'));
        exit;
    }
    ?>
    <div class='span11'>
        <div style='float:left;'>
            <h3><?=_("今日操作查看")?></h3>
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
                while ( $ROW = mysql_fetch_array ($cursor) ) 
                {
                    $name  = get_office_name($ROW['PRO_ID']);
                    $res   = get_product_num($ROW['PRO_ID']);
                    $num   = $res['pro_price'];
                    $stock = $res['pro_stock'];

            ?>
            <tr id='tr_<?=$ROW['TRANS_ID']?>'>
                <td><?=$name?></td>
                <td><?=$arr[$ROW['TRANS_FLAG']]?></td>
                <td><?=substr(GetUserNameById($ROW['BORROWER']),0,-1)?></td>
                <td><?=$ROW['TRANS_FLAG']==5?"-":abs($ROW['TRANS_QTY']);?></td>
                <td><?=$stock?></td>
                <td><?=$num?></td>
                <td><?=$ROW['TRANS_DATE']?></td>
                <td><?=substr(GetUserNameById($ROW['OPERATOR']),0,-1)?></td>
                <td>
                <?
                    $a=($ROW['TRANS_FLAG']>=1&&$ROW['TRANS_FLAG']<=3)?'two':'one';
                ?>
                <? if(($ROW['GRANT_STATUS']!=1 && $ROW['TRANS_FLAG']!=5)){?>
                <a href="<? echo "query_list.php?action=$a";?>&id=<?=$ROW['TRANS_ID']?>"><span>修改</span></a>&nbsp;<? }?>
                <a href="javascript:void(0);"><span class= 'trans_del' id='<?=$ROW['TRANS_ID']?>'>放弃操作</span></a>
                </td>
            </tr>
            <? } ?>
        </table>
    </div>
</div>
</body>
</html>
