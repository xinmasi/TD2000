<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/reportform.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/office_product/css/style.css" />
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js<?=$GZIP_POSTFIX?>"></script>
<script language="Javascript" src="<?=MYOA_JS_SERVER?>/inc/FusionCharts/FusionCharts.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/ajax.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/paginator/bootstrap.paginator.min.js"></script>
<script type="text/javascript">
function depositoryOfType(id)
{
    if(id != '-1')
    {
        var url_data = "id="+id;
        jQuery.ajax({
            type: "POST",
            url: "type_ajax.php",
            data: url_data,
            success: function(data)
            {
                jQuery('#OFFICE_TYPE').html(data);
            }
        });
        jQuery('#OFFICE_PRODUCTS').html('<select name="PRO_ID" id="PRO_ID" onchange = "depositoryOfProid(this.value);"><option value="-1"><?=_("请选择")?></option></select> ');
    }
    else
    {
        jQuery('#OFFICE_TYPE').html('<select name="OFFICE_PROTYPE" id = "OFFICE_PROTYPE" onchange = "depositoryOfProducts(this.value);"><option value="-1"><?=_("请选择")?></option></select>');
    }

    selectData('<?=$url?>','<?=$TRANS_FLAG?>');
}
function depositoryOfProducts(id)
{
    if(id != '-1')
    {
        var url_data = "id="+id;
        jQuery.ajax({
            type: "POST",
            url: "products_ajax.php",
            data: url_data,
            success: function(data)
            {
                jQuery('#OFFICE_PRODUCTS').html(data);
            }
        });
    }
    else
    {
        jQuery('#OFFICE_PRODUCTS').html('<select name="PRO_ID" id="PRO_ID" onchange = "depositoryOfProid(this.value);"><option value="-1"><?=_("请选择")?></option></select> ');
    }
    selectData('<?=$url?>','<?=$TRANS_FLAG?>');
}
function depositoryOfProid(id)
{
    selectData('<?=$url?>','<?=$TRANS_FLAG?>');
}
function selectData(url,trans_flag){
    if(url == '')
    {
        return false;
    }
    var MAP_TYPE=document.getElementsByName("MAP_TYPE");
    for(var i=0;i<MAP_TYPE.length;i++)
    {
        if(MAP_TYPE[i].checked == true){
            var MAP_VALUE = i;
        }
    }
    var OFFICE_DEPOSITORY = document.form.OFFICE_DEPOSITORY.value == -1 ? '' : "&OFFICE_DEPOSITORY="+document.form.OFFICE_DEPOSITORY.value;
    var OFFICE_PROTYPE = document.form.OFFICE_PROTYPE.value == -1 ? '' : "&OFFICE_PROTYPE="+document.form.OFFICE_PROTYPE.value;
    var PRO_ID = document.form.PRO_ID.value == -1 ? '' : "&PRO_ID="+document.form.PRO_ID.value;
    if(url == 'purchase.php'){
        if(MAP_VALUE==0 || MAP_VALUE==1){
            if(OFFICE_DEPOSITORY == "" || OFFICE_PROTYPE == "")
            {
                //alert("办公用品库和办公用品类别必须选择，否则不能生成图表");
                //return;
            }
            URL = url+"?FROM_DATE="+document.form.FROM_DATE.value+"&TO_DATE="+document.form.TO_DATE.value+PRO_ID+"&TRANS_FLAG="+trans_flag+OFFICE_PROTYPE+OFFICE_DEPOSITORY+'&MAP_TYPE='+MAP_VALUE;
        }else if(MAP_VALUE == 2){
            url = 'report.php';
            URL = url+"?FROM_DATE="+document.form.FROM_DATE.value+"&TO_DATE="+document.form.TO_DATE.value+PRO_ID+"&TRANS_FLAG="+trans_flag+OFFICE_PROTYPE+OFFICE_DEPOSITORY+'&MAP_TYPE='+MAP_VALUE;
        }
    }
    else if(url == 'dept_Sum.php'){
        if(MAP_VALUE == 2){
            url = 'dept_Sum_data.php';
            URL = url+"?FROM_DATE="+document.form.FROM_DATE.value+"&TO_DATE="+document.form.TO_DATE.value+PRO_ID+"&TRANS_FLAG="+trans_flag+OFFICE_PROTYPE+OFFICE_DEPOSITORY+'&MAP_TYPE='+MAP_VALUE;
        }
        else
        {
          URL = url+"?FROM_DATE="+document.form.FROM_DATE.value+"&TO_DATE="+document.form.TO_DATE.value+PRO_ID+"&TRANS_FLAG="+trans_flag+OFFICE_PROTYPE+OFFICE_DEPOSITORY+'&MAP_TYPE='+MAP_VALUE;
        }
    }
    else if(url == 'borrow_sum.php' || url == 'noreturn.php'){
        URL = url+"?FROM_DATE="+document.form.FROM_DATE.value+"&TO_DATE="+document.form.TO_DATE.value+PRO_ID+"&TRANS_FLAG="+trans_flag+OFFICE_PROTYPE+OFFICE_DEPOSITORY+'&MAP_TYPE='+MAP_VALUE+'&TO_ID1='+document.form.TO_ID1.value;
    }
    else
    {
        var to_date=jQuery('#sure_date').val();
        URL = url+"?FROM_DATE="+document.form.FROM_DATE.value+"&TO_DATE="+document.form.TO_DATE.value+"&TRANS_FLAG="+PRO_ID+trans_flag+OFFICE_PROTYPE+OFFICE_DEPOSITORY;    //查询物品总表的时候少一个参数 ---照熙
    }
    if(document.form.FROM_DATE.value!="" && document.form.TO_DATE.value!="")
    {
        if(document.form.FROM_DATE.value > document.form.TO_DATE.value)
        {
            alert("开始时间不能大于结束时间");
            return false;
        }
    }

    //parent.main_down.location = URL;
    jQuery.ajax({
        type: "POST",
        url: URL,
        data: "",
        success: function(data)
        {
            //document.getElementById("searchResult").innerHTML=data;
            $('.clearfix').empty();
            $('#table_border').empty();
            $('#table_info').empty();
            $('#searchResult').empty();
            $('#id').empty();
            $('#searchResult').html(data);
            $('.pull-left').css('font-weight', '600');
            $('.clearfix').css('padding-top', '10px');
            $('.clearfix').css('margin-bottom', '3px');
        }
    });
}
</script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<body>
<div>
<form method="post" name="form" style="margin-right:10px">
<table  style="text-align: center;" class="table table-bordered query-table " border="0" style=" margin-top: 10px; ">
    <tr>
        <td nowrap  width="10%" style="line-height: 30px"><?=_("办公用品库")?><span style="color: red;padding-left: 1px;"></span> </td>
        <td nowrap width = "20%">
            <select name="OFFICE_DEPOSITORY" id="aa" class="input-medium"  onChange = "depositoryOfType(this.value);">
                <option value="-1"><?=_("请选择")?></option>
<?
$query = "select * from OFFICE_DEPOSITORY where find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',DEPT_ID) or DEPT_ID = '' or DEPT_ID = 'ALL_DEPT' or ".$_SESSION["LOGIN_USER_PRIV"]."= 1";
$cursor = exequery(TD::conn(),$query);
while($ROW = mysql_fetch_array($cursor))
{
    echo "<option value=".$ROW['OFFICE_TYPE_ID'].">".$ROW['DEPOSITORY_NAME']."</option>";
}
?>
            </select>
        </td>
        <td nowrap width = "10%" style="line-height: 30px"><?=_("办公用品类别")?><span style="color: red;padding-left: 1px;"></span></td>
        <td nowrap id="OFFICE_TYPE" width = "20%">
            <select name="OFFICE_PROTYPE" id='OFFICE_PROTYPE' class="input-medium" onChange = "depositoryOfProducts(this.value);">
                <option value="-1"><?=_("请选择")?></option>
            </select>
        </td>
        <td nowrap   width = "10%" style="line-height: 30px"><?=_("办公用品")?><span style="color: red;padding-left: 1px;"></span></td>
        <td nowrap id="OFFICE_PRODUCTS" >
            <select name="PRO_ID" id="PRO_ID" class="input-medium" onChange="depositoryOfProid(this.value);">
                <option value="-1"><?=_("请选择")?></option>
            </select>
        </td>
    </tr>
<?
if($url == 'purchase.php' || $url == 'dept_Sum.php')
{
?>
    <tr>
        <td nowrap  style="line-height: 30px"><?=_("日期")?> </td>
        <td nowrap  colspan = '3'>
            <input type="text" name="FROM_DATE" id="FROM_DATE" class="input-small" style="margin-bottom: 0px" size="15" maxlength="10" value="<?=$DATE?>" onClick="WdatePicker({maxDate:'#F{$dp.$D(\'TO_DATE\')}'})"><?=_(" 至 ")?>
            <input type="text" name="TO_DATE" id="TO_DATE" class="input-small"  style="margin-bottom: 0px" size="15" maxlength="10" value="<?=$DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'FROM_DATE\')}'})">
        </td>
        <td nowrap  width="70"> <?=_("统计图")?></td>
        <td nowrap width="120">
            <input type="radio" name='MAP_TYPE' value="1" checked><span for="MAP_TYPE1"><?=_("饼图")?></span>&nbsp;
            <input type="radio" name='MAP_TYPE' value="2"><span for="MAP_TYPE2"><?=_("柱状图")?></span>&nbsp;
            <input type="radio" name='MAP_TYPE' value="3"><span for="MAP_TYPE3"><?=_("数据表")?></span>
        </td>
    </tr>
<?
}
elseif($url == 'borrow_sum.php'||$url == 'noreturn.php')
{
?>
    <tr>
        <td nowrap style="line-height: 30px"><?=_("日期")?> </td>
        <td nowrap  colspan = '3'>
            <input type="text" name="FROM_DATE" id="FROM_DATE" class="input-small" size="15" maxlength="10" value="<?=$DATE?>" onClick="WdatePicker({maxDate:'#F{$dp.$D(\'TO_DATE\')}'})"  style="margin-bottom: 0px"><?=_(" 至 ")?>
            <input type="text" name="TO_DATE" id="TO_DATE" class="input-small"  size="15" maxlength="10" value="<?=$DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'FROM_DATE\')}'})" style="margin-bottom: 0px">
        </td>
        <td nowrap> <?=_("申请人")?></td>
        <td nowrap>
            <input type="hidden" name="TO_ID1" value="">
            <input type="text" name="TO_NAME1" class="input-medium" size="13"  value="">
            <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('126','','TO_ID1','TO_NAME1')"><?=_("选择")?></a>
        </td>
    </tr>
<?
}
else
{
?>
    <tr>
        <td nowrap style="line-height: 30px"><?=_("日期")?> </td>
        <td nowrap  colspan = '5'>
            <input type="text" name="FROM_DATE" id="FROM_DATE" class="input-small" size="15" maxlength="10" value="<?=$DATE?>" style="margin-bottom: 0px" onClick="WdatePicker({maxDate:'#F{$dp.$D(\'TO_DATE\')}'})"><?=_(" 至 ")?>
            <input type="text" name="TO_DATE" id="TO_DATE" class="input-small"  size="15" maxlength="10" value="<?=$DATE?>" style="margin-bottom: 0px" onClick="WdatePicker({minDate:'#F{$dp.$D(\'FROM_DATE\')}'})">
        </td>
    </tr>
<?
}
?>
    <tr>
        <td colspan = "7" style="text-align: center;" style=" text-align: center; ">
            <input type="button" value="<?=_("查询")?>" class="btn btn-small btn-primary"  name="button" onClick = "selectData('<?=$url?>','<?=$TRANS_FLAG?>');" >
        </td>
    </tr>
</table>
</form>
</div>
<div id="searchResult" style="margin-right:10px"></div>
