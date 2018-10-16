<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("�칫��Ʒ���Ź���");
include_once("inc/header.inc.php");

//�޸���������״̬--yc
update_sms_status('75',0);

?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<style>
    table td.align-right{
    text-align: right;
    vertical-align: middle;
    }
    td select,input.btn {margin-bottom: 0px;}
</style>

<body>
<h3><?=_("�칫��Ʒ���Ų�ѯ")?></h3>
<div class="row-fluid" align="center" style='margin-top:-42px !important;'>
    <div class='span6' style='float:none;'>
        <form method="get" name="form1" target="search_area" action="search.php">
            <table class="table table-bordered" >
                <tr>
                    <td class="align-right"><?=_("�����ˣ�")?></td>
                    <td colspan="3">
                        <input type="hidden" name="RECORDER_ID" value="<?=$RECORDER_ID?>">  
                        <input disabled type="text" name="RECORDER_NAME" size="20" maxlength="20"  value="<?=$RECORDER_NAME?>">
                        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('126','','RECORDER_ID','RECORDER_NAME')"><?=_("ѡ��")?></a>
                        <a href="javascript:;" class="orgClear" onClick="ClearUser('RECORDER_ID','RECORDER_NAME')"><?=_("���")?></a>
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("״̬��")?></td>
                    <td>
                        <select name="GRANT_STATUS" class="span2" style="width:100px;">
                            <option value=""><?=_("��ѡ��")?></option>
                            <option value="0"><?=_("δ����")?></option>
                            <option value="1"><?=_("�Ѵ���")?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("�������ڣ�")?> </td>
                    <td>
                        <input type="text" name="FROM_DATE" id="FROM_DATE" class="input-small" size="8" maxlength="10" value="<?=$DATE?>" onClick="WdatePicker({maxDate:'#F{$dp.$D(\'TO_DATE\')}'})">&nbsp;
                        <?=_("��")?>&nbsp;
                        <input type="text" name="TO_DATE" id="TO_DATE" class="input-small" size="8" maxlength="10" value="<?=$DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'FROM_DATE\')}'})">&nbsp;
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style='text-align:center;'>
                        <input value="<?=_("��ѯ")?>" class="btn btn-small btn-primary" title="<?=_("ģ����ѯ")?>" type="submit" >
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
</body>
</html>