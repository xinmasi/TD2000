<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("办公用品发放管理");
include_once("inc/header.inc.php");

//修改事务提醒状态--yc
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
<h3><?=_("办公用品发放查询")?></h3>
<div class="row-fluid" align="center" style='margin-top:-42px !important;'>
    <div class='span6' style='float:none;'>
        <form method="get" name="form1" target="search_area" action="search.php">
            <table class="table table-bordered" >
                <tr>
                    <td class="align-right"><?=_("申请人：")?></td>
                    <td colspan="3">
                        <input type="hidden" name="RECORDER_ID" value="<?=$RECORDER_ID?>">  
                        <input disabled type="text" name="RECORDER_NAME" size="20" maxlength="20"  value="<?=$RECORDER_NAME?>">
                        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('126','','RECORDER_ID','RECORDER_NAME')"><?=_("选择")?></a>
                        <a href="javascript:;" class="orgClear" onClick="ClearUser('RECORDER_ID','RECORDER_NAME')"><?=_("清空")?></a>
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("状态：")?></td>
                    <td>
                        <select name="GRANT_STATUS" class="span2" style="width:100px;">
                            <option value=""><?=_("请选择")?></option>
                            <option value="0"><?=_("未处理")?></option>
                            <option value="1"><?=_("已处理")?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="align-right"><?=_("申请日期：")?> </td>
                    <td>
                        <input type="text" name="FROM_DATE" id="FROM_DATE" class="input-small" size="8" maxlength="10" value="<?=$DATE?>" onClick="WdatePicker({maxDate:'#F{$dp.$D(\'TO_DATE\')}'})">&nbsp;
                        <?=_("至")?>&nbsp;
                        <input type="text" name="TO_DATE" id="TO_DATE" class="input-small" size="8" maxlength="10" value="<?=$DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'FROM_DATE\')}'})">&nbsp;
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style='text-align:center;'>
                        <input value="<?=_("查询")?>" class="btn btn-small btn-primary" title="<?=_("模糊查询")?>" type="submit" >
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
</body>
</html>