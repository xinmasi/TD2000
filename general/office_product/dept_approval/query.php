<?
include_once("inc/auth.inc.php");
include_once("../function_type.php");

$HTML_PAGE_TITLE = _("�����¼��ѯ");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/office_product/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/numberpicker/numberpicker.css" />
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery-ui/css/flick/jquery-ui-1.10.3.custom.min.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/numberpicker/numberpicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/modules/office_product/js/ajax.js"></script>
<script>
function CheckForm1(id)
{
    var OFFICE_DEPOSITORY = $('#OFFICE_DEPOSITORY').val();
    if(OFFICE_DEPOSITORY!="-1")
    {
        var OFFICE_PROTYPE = $('#OFFICE_PROTYPE').val();
        var PRO_ID = $('#PRO_ID').val();

        if(OFFICE_PROTYPE=="-1")
        {
            alert("<?=_("��ѡ��칫��Ʒ���")?>");
            $('#OFFICE_PROTYPE').focus();
            return false;
        }
        if(PRO_ID=="-1")
        {
            alert("<?=_("��ѡ��칫��Ʒ")?>");
            $('#PRO_ID').focus();
            return false;
        }
    }
    if(id==1)
    {
        document.form1.action="export.php?ispirit_export=1";
    }else
    {
        document.form1.action="query_list.php";
    }
    document.form1.submit();
}
</script>
<body>
<h3><?=_("�����¼��ѯ")?></h3>
<div class="row-fluid" align="center">
    <div class='span11' style='float:none;'>
        <form method="get" action="query_list.php" name="form1">
            <table class="table table-bordered" >
                <tr>
                    <td class="vertial_center" nowrap><?=_("������")?></td>
                    <td nowrap>
                        <input type="hidden" name="RECORDER_ID" value="<?=$RECORDER_ID?>">
                        <input disabled type="text" name="RECORDER_NAME" class="input-small" value="<?=$RECORDER_NAME?>" style="margin-bottom:0px;">
                        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('126','','RECORDER_ID','RECORDER_NAME')"><?=_("ѡ��")?></a>
                        <a href="javascript:;" class="orgClear" onClick="ClearUser('RECORDER_ID','RECORDER_NAME')"><?=_("���")?></a>
                    </td>
                    <td class="vertial_center" nowrap><?=_("�칫��Ʒ��")?><font style="color: red;padding-left: 1px;"></font></td>
                    <td class='set_only span3'>
                        <select name="OFFICE_DEPOSITORY" id="OFFICE_DEPOSITORY" class="input-medium" onchange = "depositoryOfTypeOne(this.value,'');">
                            <option value="-1"><?=_("��ѡ��")?></option>
                            <?=get_depository('dept',$_SESSION["LOGIN_DEPT_ID"])?>
                        </select>
                    </td>
                    <td class="vertial_center" nowrap><?=_("�칫��Ʒ���")?><font style="color: red;padding-left: 1px;"></font></td>
                    <td id="OFFICE_TYPE" class='set_only'>
                        <select name="OFFICE_PROTYPE" id="OFFICE_PROTYPE" class="input-medium" onChange="depositoryOfProductsOne(this.value,'');">
                            <option value="-1"><?=_("��ѡ��")?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="vertial_center"><?=_("״̬")?></td>
                    <td>
                        <select name="GRANT_STATUS" class="input-small">
                            <option value=""><?=_("��ѡ��")?></option>
                            <option value="0"><?=_("δ����")?></option>
                            <option value="1"><?=_("�Ѵ���")?></option>
                        </select>
                    </td>
                    <td class="vertial_center"><?=_("�칫��Ʒ")?><font style="color: red;padding-left: 1px;"></font></td>
                    <td id="OFFICE_PRODUCTS" class='set_only' >
                        <select name="PRO_ID" id="PRO_ID" class="input-medium">
                            <option value="-1"><?=_("��ѡ��")?></option>
                        </select>
                    </td>
                    <td class="vertial_center"><?=_("��������")?></td>
                    <td nowrap>
                        <input style="margin-bottom:0px;width: 68px;" type="text" name="FROM_DATE" id="FROM_DATE" class="input-small" value="<?=$DATE?>" onClick="WdatePicker({maxDate:'#F{$dp.$D(\'TO_DATE\')}'})">
                        <?=_("��")?>
                        <input style="margin-bottom:0px;width: 68px;" type="text" name="TO_DATE" id="TO_DATE" class="input-small" value="<?=$DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'FROM_DATE\')}'})">
                    </td>
                </tr>
                <tr>
                    <td colspan="8" style='text-align:center;'>
                        <input value="<?=_("��ѯ")?>" class="btn btn-small btn-primary" type="button" onClick="CheckForm1(0);">
                        <input value="<?=_("����")?>" class="btn btn-small btn-primary" type="button" onClick="CheckForm1(1);">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
</body>
</html>