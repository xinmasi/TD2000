<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("../function_type.php");

$HTML_PAGE_TITLE = _("���ά��");
include_once("inc/header.inc.php");
$action =stripslashes($_GET['action']);
if($action=='one')
{
    $display_set_two = 'display:none';
    $display_set_one = 'display:block';
    $title = _('���ά��');
    //���÷�����ѯ�������б�  ���Դ�������Ҳ���Ե���2����ͬ����
}else if($action=='two') {
    $display_set_two = 'display:block';
    $display_set_one = 'display:none';
    $title = _('���Ǽ�');
    //���÷�����ѯ�������б�
}
$res2 = array();
if($id != '')
{
    $query="SELECT * FROM office_transhistory WHERE trans_id='{$id}' ";
    $cursor = exequery ( TD::conn (), $query );
    $res2 = mysql_fetch_array($cursor);
    $BORROWER_NAME = GetUserNameById($res2['BORROWER']);
    
    //��ȡ�칫��Ʒ���
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

//��������ѡ�������numberpicker
$(document).ready(function(){
    var set_exit = $('#TRANS_QTY').val();
    if(set_exit =='')//��ʼ��
    {
        set_exit = 0;
        myeditable = false;
        }else{//�༭ҳ���ʼ��
            myeditable = true;
            var mystock = $('#STOCK').val();
        }
    $("#np").dpNumberPicker({
             value: parseInt(set_exit),
             min: 0,         
             max: mystock,
             editable: myeditable,
             afterChange: function(){
                var num = $(this).find('input').val();//��ȡinput��ֵ
                $("#getstock").html(mystock);
                if(num>=parseInt(mystock)){
                    $('#StockAlert').show();
                }
                else{
                $('#StockAlert').hide();
                }
                $('#TRANS_QTY').val(num);//��ֵ������������
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
            <input type="reset" class="btn btn-small btn-primary" value="<?=_("����")?>">
        </div>
            <table class="table table-bordered table-hover">
                <tr>
                    <td class="align-right" style='width: 120px;'><?=_("�Ǽ����ͣ�")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td>
                        <select class="input-xlarge" id="TRANS_FLAG" name="TRANS_FLAG" style='<?=$display_set_one?>'>
                            <option value="-1"><?=_("---��ѡ��---")?></option>
                            <option value="0" <?=$res2['TRANS_FLAG']=='0'?'selected':''?>><?=_("�ɹ����")?></option>
                            <option value="5" <?=$res2['TRANS_FLAG']=='5'?'selected':''?>><?=_("ά��")?></option>
                            <option value="4" <?=$res2['TRANS_FLAG']=='4'?'selected':''?>><?=_("����")?></option>
                        </select>
                        <select class="input-xlarge" id="TRANS_FLAG2" name="TRANS_FLAG2" style='<?=$display_set_two?>'>
                            <option value="-1"><?=_("---��ѡ��---")?></option>
                            <option value="1" <?=$res2['TRANS_FLAG']==('1'||'2')?'selected':''?>><?=_("����/����")?></option>
                            <option value="3" <?=$res2['TRANS_FLAG']=='3'?'selected':''?>><?=_("�黹")?></option>
                        </select>
                    </td>
                </tr>
                <tr style='<?=$action=='two'?'':'display:none'?>'>
                    <td class="align-right"><?=_("�����ˣ�")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td>
                        <input type="hidden" name="TO_ID" id="TO_ID" value="<?=$res2['BORROWER']?>">  
                        <input type="text" name="TO_NAME" id="TO_NAME" size="20" maxlength="20"  value="<?=$BORROWER_NAME?>"  style="margin-bottom:0px;" disabled>
                        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('126','','TO_ID','TO_NAME')"><?=_("ѡ��")?></a>
                        <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("���")?></a>
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("�칫��Ʒ�⣺")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td class='set_only'>
                        <select name="OFFICE_DEPOSITORY" id="OFFICE_DEPOSITORY" class="input-xlarge" onchange = "depositoryOfTypeOne(this.value,'');">
                            <option value="-1"><?=_("��ѡ��")?></option>
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
                    <td class="align-right"><?=_("�칫��Ʒ���")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td class='set_only'>
                        <select name="OFFICE_PROTYPE" id="OFFICE_PROTYPE"  class="input-xlarge" onChange="depositoryOfProductsOne(this.value,'');">
                            <option value="-1"><?=_("��ѡ��")?></option>
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
                    <td class="align-right"><?=_("�칫��Ʒ��")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td class='set_only'>
                        <select name="PRO_ID"  id="PRO_ID" class="input-xlarge" onChange="GetProduct(this.value);">
                            <option value="-1"><?=_("��ѡ��")?></option>
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
                        <input id="TOGGLE_BLUR" type="button" name="SelectPro" title="<?=_("ģ��ѡ��")?>" value="<?=_("ģ��ѡ��")?>" class="btn btn-small btn-info">
                    </td>
                </tr>
                <tr id="BLURRED" style="display:none">
                    <td class="align-right"><?=_("ģ������")?>:</td>
                    <td>
                        <input type="hidden" id="mytag" name='mytag' value='1'>
                        <input type="text" id="PRO_NAME" name="PRO_NAME" size="20" maxlength="20" class="input-large" style="margin:0px;" value="">&nbsp;&nbsp;
                        <input type="text" id="project-id" name='project_id'>
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("����������")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td>
                        <div id="np"></div>
                        <input type="text" name="TRANS_ID" id='TRANS_ID' value='<?=$id; ?>' />
                        <input type="hidden" id="TRANS_QTY" name='TRANS_QTY' value="<?=$res2['FACT_QTY']?>">
                        <input type="text" name="PRO_ID_OLD" id='PRO_ID_OLD' value='<?=$res2['PRO_ID']; ?>' />
                        <input type="hidden" name="TRANS_QTY_OLD" id='TRANS_QTY_OLD' value='<?=$res2['FACT_QTY']; ?>' />
                        <input type="text" id="STOCK" name='STOCK' value="<?=$STOCK?>">
                        <input type="hidden" id="set_stock_tag"  value="1">
                         <span id="StockAlert" class="text-error" style="display:none;line-height:31px;">�����Ϊ<span id="getstock"></span>��</span>
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("��ע��")?><font style="color: red;padding-left: 1px;">*</font></td>
                    <td>
                        <textarea name="REMARK" id="REMARK" cols="45" rows="5"><?=$res2['REMARK']?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style='text-align: center;'>
                        <input type="hidden" id="action" value="<?=$action; ?>">
                        <input type="button" class="btn btn-small btn-primary" style="margin-top:5px;" onClick="CheckForm();" value="<?=_("ȷ��")?>">
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
        Message(_('��ʾ'), _('���޽��ղ�����¼'));
        exit;
    }
    ?>
    <div class='span11'>
        <div style='float:left;'>
            <img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" WIDTH="22" HEIGHT="22" align="absmiddle">
            <span class="big3">&nbsp;<?=_("���ղ����鿴")?></span>
        </div>
        <table class="table table-bordered center table-hover">
            <tr style="background: #e4f1f9;">
                <td><?=_("�칫��Ʒ����")?></td>
                <td><?=_("�Ǽ�����")?></td>
                <td><?=_("����/����/�黹��")?></td>
                <td><?=_("����")?></td>
                <td><?=_("��ǰ���")?></td>
                <td><?=_("����")?></td>
                <td><?=_("��������")?></td>
                <td><?=_("����Ա")?></td>
                <td><?=_("����")?></td>
            </tr>
            <? 
                $arr = array(0=>_('�ɹ����'),1=>_('����'),2=>_('����'),3=>_('�黹'),4=>_('����'),5=>_('ά��'));
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
                <a href="<? echo "query_list.php?action=$a";?>&id=<?=$ROW['TRANS_ID']?>"><span><?=_("�޸�")?></span></a>&nbsp&nbsp
                <a href="javascript:void(0):"><span class= 'trans_del' id='<?=$ROW['TRANS_ID']?>'><?=_("��������")?></span></a>
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
        alert("<?=_("�޸�")?>��ѡ��Ǽ����ͣ�");
        if(action =='one'){$("#TRANS_FLAG").focus();}else{$("#TRANS_FLAG2").focus();}        
        return false;
    }
    //�����˲���Ϊ��
    var TO_ID = $("#TO_ID").val();
    if((TO_ID =='') && (TRANS_FLAG == '-1'))
    {
        alert("<?=_("����д������")?>");
        $("#TO_NAME").focus();
        return false;
    }
    if($("#mytag").val() == '1')
    {
        if(OFFICE_DEPOSITORY=="-1")
        { 
             alert("<?=_("��ѡ��칫��Ʒ��")?>");
             $('#OFFICE_DEPOSITORY').focus();
             return false;
        }
        
        var OFFICE_PROTYPE = $('#OFFICE_PROTYPE').val();
        if(OFFICE_PROTYPE=="-1")
        { 
             alert("<?=_("��ѡ��칫��Ʒ���")?>");
             $('#OFFICE_PROTYPE').focus();
             return false;
        }
        
        var PRO_ID = $('#PRO_ID').val();
        var PRO_ID_TEXT = $('#PRO_ID_TEXT').val();
        if(PRO_ID=="-1" || PRO_ID_TEXT=="")
        { 
             alert("<?=_("��ѡ��칫��Ʒ")?>");
             $('#PRO_ID').focus();
             return false;
        }
    }
    else
    {
        if(OFFICE_DEPOSITORY =="-1" && PRO_NAME == '')
        {
            alert("<?=_("�칫��Ʒģ�����Ʋ���Ϊ��")?>");
             $('#PRO_NAME').focus();
             return false;
        }
    }
    var TRANS_QTY = $('#TRANS_QTY').val();
    if(TRANS_QTY == '' || TRANS_QTY == '0')
    {
         alert("<?=_("������������Ϊ0")?>");
         $('#np').focus();
         return false;
    }else if(checknum(TRANS_QTY)=="0") 
    { 
         alert("<?=_("������������Ϊ����")?>");
         $('#np').focus();
         return false;
    }
//     else{
//         check_pro_stock(PRO_ID,TRANS_QTY,'apply');
//     }
    var REMARK = $('#REMARK').val();
    if(REMARK=="")
    { 
         alert("<?=_("��ע��Ϣ����Ϊ��")?>");
         $('#REMARK').focus();
         return false;
    }
    if(TRANS_QTY){
        var STOCK = $("#STOCK").val();
        var TRANS_QTY = $("#TRANS_QTY").val();
        var PRO_ID = $("#PRO_ID").val();
//         if((TRANS_FLAG2 =='-1' && TRANS_FLAG != '0') || (TRANS_FLAG2 !='3' && TRANS_FLAG == '-1')){//�ǲɹ����͹黹
//             if(STOCK<TRANS_QTY)
//             {
//                 alert("�����������ڿ�棬��������д");
//                 return false;
//             }
//         }else 
        if(TRANS_FLAG2 == '3')//�黹 �Ƿ���ڽ��ã����ҹ黹�����Ƿ���ڽ�������
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
