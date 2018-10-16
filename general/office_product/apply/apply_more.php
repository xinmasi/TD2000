<?
include_once("inc/auth.inc.php");

include_once("../function_type.php");
$HTML_PAGE_TITLE = _("批量申领");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/office_product/css/listview.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/numberpicker/numberpicker.css" />
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/numberpicker/numberpicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/module.js"></script>

<body>
    <div class="moreapply-add">        
        <div>
            <h3 class="moreapply-header">
                <?=_("办公用品批量申领")?>
            </h3>

        </div>
        
        <div class="wrapper">
        <?
            if($_SESSION["LOGIN_USER_PRIV"]!=1)
            {
                $query1=" and ((find_in_set('".$_SESSION["LOGIN_USER_ID"]."',PRO_MANAGER) or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',PRO_DEPT)) or (PRO_MANAGER='' and PRO_DEPT='') or PRO_DEPT='ALL_DEPT' or PRO_CREATOR='".$_SESSION["LOGIN_USER_ID"]."')";
            }
            else
            {
                $query1=" and 1=1";
            }
            
            if($_SESSION["LOGIN_USER_PRIV"]==1)
            {
                $query = "SELECT * FROM office_depository where office_type_id!='' ";
            }else
            {
                $DEPT_ID_STR = td_trim($_SESSION["LOGIN_DEPT_ID"].",".$_SESSION["LOGIN_DEPT_ID_OTHER"]);
                
                $id_array = explode(',',$DEPT_ID_STR); 
                $where = "";
                if(count($id_array)>1)
                {
                    for($i=0;$i<count($id_array);$i++)
                    {
                        $where .= "FIND_IN_SET('{$id_array[$i]}',DEPT_ID) or ";
                    }
                }
                else
                {
                    $where = "FIND_IN_SET('$DEPT_ID_STR',DEPT_ID) or ";
                }
                $query = "SELECT * FROM office_depository where office_type_id!='' and (".$where." DEPT_ID = '' or  DEPT_ID = 'ALL_DEPT')";
            }
            $cursor = exequery(TD::conn(),$query);
            if(mysql_num_rows($cursor)==0)
            {
                Message(_('提示'),_('暂无办公用品信息'));
                exit;
            }
            $i=0;
            while($ROW = mysql_fetch_array($cursor))
            {
                $i++;
        ?>
            <h5 data-lib-id='<?=$i?>' data-group-handle="1" class="grouphandle expand">
                <i class="type-icon type-group-icon"></i>
                <span class="tip"><?=$ROW['DEPOSITORY_NAME']?></span>
            </h5>
            
            <table  data-lib-id='<?=$i?>' class="table table-hover lib-table">        
                <colgroup>
                    <col width="200">
                    <col width="150">
                    <col width="200">
                    <col width="200">
                    <col width="">
                </colgroup>
                <thead>
                    <tr>
                        <th><?=_("名称")?></th>
                        <th class="text-center"><?=_("库存")?></th>
                        <th class="text-center"><?=_("单价")?></th>
                        <th class="text-center"><?=_("数量")?></th>
                        <th class="text-center"><?=_("总价")?></th>
                    </tr>
                </thead>
                <tbody>    
                    <? 
                        $query="SELECT * FROM office_type where find_in_set(id,'{$ROW["OFFICE_TYPE_ID"]}')";
                        $cursor2 = exequery ( TD::conn (), $query );
                        $num=0;
                        while ( $ROW2 = mysql_fetch_array ( $cursor2 ) )
                        {
                            $num++;
                    ?>            
                    <tr data-group-id="<?=$i.$num?>" data-group-handle="1" class="grouphandle expand" >
                        <td colspan="5">
                            <div>
                            <b>
                                <i class="type-icon type-group-icon"></i>
                                <span class="tip"><?=$ROW2['TYPE_NAME']?></span>
                            </b>
                            </div>
                        </td>
                    </tr>
                    <? 
                        $query3="SELECT * FROM office_products where office_protype='{$ROW2['ID']}'".$query1;
                        $cursor3 = exequery(TD::conn(),$query3);
                        
                        $this_date = date('Y-m-d',time());
                        $this_time = strtotime($this_date);
                        
                        while($ROW2 = mysql_fetch_array($cursor3))
                        {
                            $AVAILABLE = $ROW2['AVAILABLE'];
                            if($AVAILABLE!="")
                            {
                                $time_array =  explode("|",$AVAILABLE);
                                if($this_time>=$time_array[0] && $this_time<=$time_array[1])
                                {
                                    continue;
                                }
                            }
                    ?>
                    <tr  data-group-id="<?=$i.$num?>" >
                        <td><?=$ROW2['PRO_NAME']?></td>
                        <td data-lib-num="<?=$ROW2['PRO_STOCK']?>" class="text-center"><?=$ROW2['PRO_STOCK']?></td>
                        <td class="apply-list-price text-center" data-price="<?=$ROW2['PRO_PRICE']?>">￥<?=$ROW2['PRO_PRICE']?></td>
                        <td class="text-center"><div data-np-id="<?=$ROW2['PRO_ID']?>" data-max="<?=$ROW2['PRO_STOCK']?>"></div></td>
                        <td class="apply-list-price text-center" data-total="0">￥0</td>
                    </tr>
                    <? } }?>    
                </tbody>
            </table>
            
            <? } ?>
            <div class="submit-button" style="margin-bottom:10px;">
                <button  data-toggle="modal" class="btn btn-small btn-primary" onClick="CheckForm1()">提交</button>            
            </div>    
            
            <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">                   
                   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                   <h3><?=_("申请条件")?></h3>
                </div>
                <div class="modal-body">
                <form method="post" name="form1" id="form" action="moreapply_add.php">
                <input type="hidden" name="json" id="json" value="">
                     <table class="moreapply-modal">
                        <tbody>
                        <tr>
                            <td class="align-right"><?=_("部门审批人")?><span class="tip"></span></td>
                            <td>
                                <input type="hidden" name="MANAGER" value="">
                                <textarea cols="29" name="MANAGER_NAME" rows="2" class="BigStatic" wrap="yes" id="MANAGER_NAME" disabled></textarea>
                                <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('126','','MANAGER', 'MANAGER_NAME')"><?=_("选择")?></a>
                                <a href="javascript:;" class="orgClear" onClick="ClearUser('MANAGER', 'MANAGER_NAME')"><?=_("清空")?></a>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-right"><?=_("备注")?><span class="tip">*</span></td>
                            <td>
                                <textarea name="REMARK" id="REMARK" cols="45" rows="5" class="input" maxlength="40"></textarea>
                            </td>
                        </tr>                 
                        </tbody>
                    </table>    
                </form>            
            </div>    
            <div class="modal-footer" style="text-align: center;">
                <input type="hidden" name="stroid"  id="stroid">                       
                  <button class="btn btn-small btn-primary" onClick="CheckForm2()"><?=_("提交")?></button>
                  <button class="btn" data-dismiss="modal" aria-hidden="true"><?=_("关闭")?></button>
            </div>        
        </div>
        
    <script>     
    //数量计算器组件NumberPicker    
    $('[data-np-id]').each(function(){
        $(this).dpNumberPicker({
            value: 0,
            min: 0,
            max: this.getAttribute('data-max'),
            formatter: function(value){
                var num = value,
                     $tr = $(this).parents('tr'),
                    price = $tr.find('[data-price]').attr('data-price'),
                    lib_num = $tr.find('[data-lib-num]').attr('data-lib-num'),    
                    total = price * num;
                    
                $tr.find('[data-total]').attr('data-total', total).html('￥' + total);
                return value;
            },
            afterChange: function(){
                var num = $(this).find('input').val(),
                     $tr = $(this).parents('tr'),
                    price = $tr.find('[data-price]').attr('data-price'),
                    lib_num = $tr.find('[data-lib-num]').attr('data-lib-num');                         
                if(parseInt(num, 10) > parseInt(lib_num, 10)){
                     alert("<?=_("申请数量不能大于库存量")?>");
                     return false;
                }else{
                     total = price *1000 * num/1000;
                     $tr.find('[data-total]').attr('data-total', total).html('￥' + total);
                }           
            }
        });    
    });    
    
    //实现库的收放功能
    $('body').delegate('[data-group-handle="1"][data-lib-id]', 'click', function(e){
        var $target = $(this);        
        if ($target.hasClass('expand')){
            $target.removeClass('expand');
            $('[data-lib-id="'+ this.getAttribute('data-lib-id') +'"]').not('[data-group-handle="1"]').hide();
        }else{
            $target.addClass('expand');
            $('[data-lib-id="'+ this.getAttribute('data-lib-id') +'"]').not('[data-group-handle="1"]').show();
        }

    });
    
    //实现办法用品类型的收放功能
    $('body').delegate('[data-group-handle="1"][data-group-id]', 'click', function(e){
        var $target = $(this);
        if ($target.hasClass('expand')){
            $target.removeClass('expand');
            $('[data-group-id="'+ this.getAttribute('data-group-id') +'"]').not('[data-group-handle="1"]').hide();
        }else{
            $target.addClass('expand');
            $('[data-group-id="'+ this.getAttribute('data-group-id') +'"]').not('[data-group-handle="1"]').show();
        }
    });
     
    //申请表单验证
    function CheckForm1()
    {
        var data = (function(){
            var n = 0, ret =[],str="";
            $('[data-np-id]>input').each(function(){
                var i = parseInt(this.value, 10);
                n += i;
                i && (str += this.parentNode.getAttribute('data-np-id')+",");
                i && ret.push({ id: this.parentNode.getAttribute('data-np-id'), value: i});   
            });
            return { num: n, json: ret ,strid: str}; 
        })();
        if(data.num == 0)
        {
            alert("<?=_("请选择要申请的物品")?>");
            return;s
        }else
        {
            $('#stroid').attr("value",data.strid);
            $('#json').attr("value",JSON.stringify(data.json));
            $("#myModal").modal('show');
        }                         
        
    } 
    //备注表单验证
    function CheckForm2()
    {
        var stroid = $('#stroid').val();
        var status = 0;
        $.ajax({
            async:false,
            type: 'GET',
            url:'check_no.php',
            data:{
                action: 'vague_more',
                stroid:stroid
                },
            success: function(d){
                status = d;
            }
        });
        if(status==1 && $("input[name='MANAGER']").val()=="")
        {
            alert("<?=_("请选择部门审批人")?>");
            return false;
        }
        if($('#REMARK').val() == "")
        {
            alert("<?=_("请填写申请原因")?>");
            return false;
        }
        $('#form').submit();
    }
    </script>
</body>
</html>
