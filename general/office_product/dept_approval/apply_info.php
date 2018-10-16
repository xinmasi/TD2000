<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("../function_type.php");
$HTML_PAGE_TITLE = _("�����¼");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/numberpicker/numberpicker.css" />

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/numberpicker/numberpicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/ajax.js"></script>
<script>
function CheckForm1(){
    if($('#REASON').val()== ""){
        $(".control-group").addClass("error");
        return false;
    }
    else{
        $(".control-group").removeClass("error");
        $("form[name='form']").submit();
    }
}
function check_num(id,transid)
{
    var reg =/^\+?[1-9][0-9]*$/;
    var pro_num = $("#pro_"+id).val();

    if(!reg.test(pro_num) || pro_num=="")
    {
        alert("<?=_("����������������")?>");
        $("#pro_"+id).focus();
        return false;
    }
    var status1 = 0;
    $.ajax({
        async:false,
        type: 'GET',
        url:'check_no.php',
        data:{
            pro_num:pro_num,
            transid:transid,
            id:id,
        },
        success: function(d){
            if($.trim(d)!="OK")
            {
                status1 = 1;
                $("#checknum").val(0);
                alert(d);
            }else
            {
                $("#checknum").val(1);
            }
        }
    });
    if(status1==1)
    {
        $("#pro_"+id).focus();
        return false;
    }
}
</script>
<body marginwidth="0" marginheight="0" style="background:#f7f7f7;">
<h3><?=_("�����������")?></h3>
<div class="container">
    <?
    $num= get_transhistory($_SESSION['LOGIN_USER_ID']);
    if(isset($TRANS_ID))
    {
        $query="SELECT a.PRO_ID,a.pro_name,a.pro_stock,a.pro_lowstock,a.pro_maxstock,a.pro_price,b.TRANS_ID,b.fact_qty,b.remark,b.trans_date,b.trans_flag,b.dept_status,b.RETURN_STATUS,b.RETURN_DATE,b.RETURN_REASON from office_products a LEFT JOIN office_transhistory b ON a.pro_id=b.pro_id where b.TRANS_ID='{$TRANS_ID}'";
        $where = "trans_id='{$TRANS_ID}'";
    }elseif(isset($CYCLE_NO))
    {
        $query="SELECT a.PRO_ID,a.pro_name,a.pro_stock,a.pro_lowstock,a.pro_maxstock,a.pro_price,b.TRANS_ID,b.fact_qty,b.remark,b.trans_date,b.dept_status,b.RETURN_STATUS,b.RETURN_DATE,b.RETURN_REASON from office_products a LEFT JOIN office_transhistory b ON a.pro_id=b.pro_id where (b.cycle_no='{$CYCLE_NO}' and b.trans_state=0 and b.dept_status=1 and b.pro_id in ($num)) or(b.cycle_no='{$CYCLE_NO}' and b.dept_status=0 and FIND_IN_SET('{$_SESSION['LOGIN_USER_ID']}',b.dept_manager)) or (b.cycle_no='{$CYCLE_NO}' and b.trans_state=1 and b.dept_status=1 and b.pro_id in ($num) and GRANT_STATUS=1 and RETURN_DATE!='0000-00-00' and RETURN_STATUS = 0)";
        $where1 = "";
        if($stype==1)
        {
            $where1 = " and RETURN_DATE!='0000-00-00' and RETURN_STATUS = 0";
        }
        $where = "cycle_no='{$CYCLE_NO}'".$where1;
    }
    $cursor = exequery(TD::conn(),$query);
    $sql="select trans_date,borrower,TRANS_FLAG,RETURN_DATE from office_transhistory where ".$where;
    $res = exequery(TD::conn(),$sql);
    $arr = mysql_fetch_array($res);
    ?>
    <fieldset class="box">
        <h4><?=substr(GetUserNameById($arr['borrower']),0,-1)._("��")?><? if($arr['TRANS_FLAG']==1){ echo _("����");}elseif($arr['TRANS_FLAG']==2){if($arr['RETURN_DATE']!='0000-00-00'){echo _("�黹");$intype = 1;}else{echo _("����");}}?><?=_("����")?><small class="space"><?=$arr['RETURN_DATE']!='0000-00-00'?$arr['RETURN_DATE']:$arr['trans_date']?></small>
        </h4>
        <table class="table table-bordered table-hover ">
            <thead>
            <tr>
                <th nowrap=""><?=_("�칫��Ʒ��")?></th>
                <th nowrap=""><?=_("�칫��Ʒ����")?></th>
                <th nowrap=""><?=_("�����淶Χ")?></th>
                <th nowrap=""><?=_("����")?></th>
                <th nowrap=""><?=_("��������")?></th>
                <th nowrap=""><?=_("���뱸ע")?></th>
                <th nowrap=""><?=_("�黹��Ϣ")?></th>
            </tr>
            </thead>
            <tbody>
            <?
            while($ROW = mysql_fetch_array($cursor))
            {
                $num         = $ROW['fact_qty'];
                $dept_status = $ROW['dept_status'];
                $type        = $ROW['trans_flag'];
                $date        = $ROW['trans_date'];
                $pro_name    = $ROW['pro_name'];
                $pro_id      = $ROW['pro_id'];

                $pro_id_str   .= $ROW['PRO_ID'].",";
                $fact_qty_str .= $ROW['fact_qty'].",";
                $trans_id_str .= $ROW['TRANS_ID'].",";
                $pro_name_str .= $ROW['pro_name'].",";


                //��ȡ��Ʒ������
                $de_array = get_depository_id($ROW['PRO_ID']);
                $de_name = $de_array['depository_name'];
                ?>
                <tr>
                    <td><?=$de_name?></td>
                    <td><?=$pro_name?>(<?=_("���:").$ROW['pro_stock']?>)</td>
                    <td><?=$ROW['pro_lowstock']?> �� <?=$ROW['pro_maxstock']?></td>
                    <td><?=$ROW['pro_price']?>Ԫ</td>
                    <?
                    if($intype==1)
                    {
                        ?>
                        <td><?=abs($ROW['fact_qty'])?></td>
                        <?
                    }else
                    {
                        ?>
                        <td style="width:130px;"><div data-np-id="<?=$ROW['PRO_ID']?>" data-max="<?=$ROW['pro_stock']?>" data-value="<?=abs($ROW['fact_qty'])?>" data-trans="<?=$ROW['TRANS_ID']?>" ></div></td>
                        <!--<input type="text" id="pro_<?=$ROW['PRO_ID']?>" value="<?=abs($ROW['fact_qty'])?>" style="width:50px;" onBlur="check_num(<?=$ROW['PRO_ID']?>,<?=$ROW['TRANS_ID']?>);">-->
                        <?
                    }
                    ?>

                    <td><?=$ROW['remark']?></td>
                    <td><?=$ROW['RETURN_REASON']?></td>
                </tr>
            <?} ?>
            </tbody>
        </table>
        <div class="help-block" style="display:none;">
            <label class="checkbox inline">
                <input type="checkbox" value=""><?=_("����������")?>
            </label>
            <label class="checkbox inline">
                <input type="checkbox" value=""><?=_("���ѿ��Ա")?>
            </label>
        </div>
        <div>
            <?
            if(isset($TRANS_ID))
            {
                ?>
                <input type="button" value="<?=_("��  ׼")?>" id="1_<?=$TRANS_ID?><?=$type==3?'_'.$num:''?><?=$dept_status==1?'':'_a'?>" class="btn status btn-primary">&nbsp;&nbsp;
                <input type="button" value="<?=_("����׼")?>" id="2_<?=$TRANS_ID?><?=$type==3?'_'.$num:''?><?=$dept_status==1?'':'_a'?>" class="btn status1 btn-danger" data-toggle="modal" data-target="#myModal">&nbsp;&nbsp;
                <input type="button" value="<?=_("����")?>" onClick="history.go(-1)" class="btn">

                <?
            }elseif(isset($CYCLE_NO))
            {
                ?>
                <input type="button" value="<?=_("��  ׼")?>" id="3_<?=$CYCLE_NO?><?=$dept_status==1?'':'_a'?>" class="btn status btn-primary">&nbsp;&nbsp;
                <input type="button" value="<?=_("����׼")?>" id="4_<?=$CYCLE_NO?><?=$dept_status==1?'':'_a'?>" class="btn status1 btn-danger" data-toggle="modal" data-target="#myModal">&nbsp;&nbsp;
                <input type="button" value="<?=_("����")?>" onClick="history.go(-1)" class="btn">

                <?
            }
            ?>
        </div>
        <form action="info_add.php"  method="post" name="form">
            <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">��</button>
                    <h3 id="myModalLabel"><?=_("����׼����")?><font style="color: red;padding-left: 1px;">*</font></h3>
                </div>
                <div class="modal-body">
                    <div class="control-group ">
                        <textarea id="REASON" name="REASON" cols="45" rows="5" placeholder="<?=_("����׼����")?>" style="width:400px;" maxlength="40"></textarea>
                        <span class="help-block" style="dispaly:none;"><?=_("�����벻��׼����")?></span>
                    </div>
                </div>
                <div class="modal-footer">

                    <input type="hidden" name="TRANS_ID" id="TRANS_ID" value="<?=$TRANS_ID?>">
                    <input type="hidden" name="pro_name" id="pro_name" value="<?=$pro_name?>"/>
                    <input type="hidden" name="borrower" id="borrower" value="<?=$arr['borrower']?>"/>
                    <input type="hidden" name="CYCLE_NO" id="CYCLE_NO" value="<?=$CYCLE_NO?>"/>
                    <input type="hidden" name="RETURN_DATE" id="RETURN_DATE" value="<?=$arr['RETURN_DATE']?>">
                    <input type="hidden" name="dept_status" id="dept_status" value="<?=$dept_status?>"/>

                    <input type="hidden" name="pro_id_str" id="pro_id_str" value="<?=$pro_id_str?>"/>
                    <input type="hidden" name="trans_id_str" id="trans_id_str" value="<?=$trans_id_str?>"/>
                    <input type="hidden" name="pro_name_str" id="pro_name_str" value="<?=$pro_name_str?>"/>

                    <input type="hidden" name="repeat" id="repeat" value="<?=$repeat?>"/>
                    <input type="hidden" name="checknum" id="checknum" value="1"/>

                    <input type="button" value="<?=_("����")?>" class="btn btn-primary" onClick="CheckForm1()" />
                    <input type="button" value="<?=_("�ر�")?>" class="btn" data-dismiss="modal" aria-hidden="true" />
                </div>
            </div>
        </form>
    </fieldset>
</div>
<script>
$('[data-np-id]').each(function(){
    var set_sum = this.getAttribute('data-value');
    $(this).dpNumberPicker({
        value: parseInt(set_sum),
        min: 1,
        max: this.getAttribute('data-max'),
        afterChange: function(){
            var num = $(this).find('input').val(),
                lib_num = this.getAttribute('data-max');
            if(parseInt(num, 10) > parseInt(lib_num, 10))
            {
                alert("<?=_("�����������ܴ��ڿ����")?>");
                window.location.reload();
                return false;
            }else
            {
                var status1 = 0;
                $.ajax({
                    async:false,
                    type: 'GET',
                    url:'check_no.php',
                    data:{
                        pro_num:num,
                        transid:this.getAttribute('data-trans'),
                        id:this.getAttribute('data-np-id'),
                    },
                    success: function(d){
                        if($.trim(d)!="OK")
                        {
                            status1 = 1;
                            $("#checknum").val(0);
                            alert(d);
                        }else
                        {
                            $("#checknum").val(1);
                        }
                    }
                });
                if(status1==1)
                {
                    //$("#pro_"+id).focus();
                    return false;
                }
            }
        }
    });
});
</script>
</body>
</html>
