<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
header("Cache-control: private");
$CUR_DATE=date("Y-m-d",time());

$HTML_PAGE_TITLE = _("新建出差登记");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
<script>
function refreshPriv(user_id)
{
    jQuery.ajax({
        type: 'POST',
        url:'../../data.php',
        data:{
            assign_user_id: user_id
        },
        //async: true,
        success:function(d){
            var data = d;
            jQuery('#leader_id').html(data.leader_id);
        }
    });
}
jQuery(document).ready(function(){
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});

    jQuery(".TableData").delegate(".orgAdds","click",function(){
        var to_id = "TO_ID",to_name = "TO_NAME";

        window.org_select_callbacks = window.org_select_callbacks || {};

        window.org_select_callbacks.add = function(item_id, item_name){
            refreshPriv(item_id);
        };
        window.org_select_callbacks.remove = function(item_id, item_name){
            refreshPriv("");
        };
        window.org_select_callbacks.clear = function(){
        };

        SelectUserSingle('7', '100', to_id, to_name);
        return false;
    });
});
function CheckForm()
{
    if(document.form1.EVECTION_DEST.value=="")
    { alert("<?=_("出差地点不能为空！")?>");
        return (false);
    }
    if(document.form1.EVECTION_DATE1.value=="")
    { alert("<?=_("出差开始时间不能为空！")?>");
        return (false);
    }
    if(document.form1.EVECTION_DATE2.value=="")
    { alert("<?=_("出差结束时间不能为空！")?>");
        return (false);
    }
    if(document.form1.REASON.value=="")
    { alert("<?=_("事由不能为空！")?>");
        return false;
    }
    return (true);
}
function sendForm()
{
    if(CheckForm())
    {
        document.form1.submit();
        document.getElementById('BUTTON').disabled='disabled';
        //document.form1.action="";
    }
}
</script>


<body class="bodycolor attendance" onload="document.form1.EVECTION_DEST.focus();">

<h5 class="attendance-title"><span class="big3"><?=_(" 新建出差登记")?></span></h5><br>

<br>
<form action="submit.php"  method="post" id="form1" name="form1">
    <table class="TableBlock" width="90%" align="center">
        <?
        $query = "select DEPT_HR_MANAGER from HR_MANAGER where DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."'";
        $cursor=exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
            $DEPT_HR_MANAGER=$ROW["DEPT_HR_MANAGER"];

        /*$query2 = "select PARA_VALUE from SYS_PARA where PARA_NAME ='DEPT_HR_AGENT'";
        $cursor2=exequery(TD::conn(),$query2);
        if($ROW2=mysql_fetch_array($cursor2))
           $DEPT_HR_AGENT=$ROW2["PARA_VALUE"];*/
        $query2 = "select MANAGER_ID,MANAGERS,DEPT_ID_STR from ATTEND_LEAVE_MANAGER";
        $cursor2=exequery(TD::conn(),$query2);
        while($ROW2=mysql_fetch_array($cursor2))
        {
            $MANAGERS1.=$ROW2["MANAGERS"];
            $DEPT_ID_STR.=$ROW2["DEPT_ID_STR"];
        }
        if($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($DEPT_HR_MANAGER,$_SESSION["LOGIN_USER_ID"]) || find_id($MANAGERS1,$_SESSION["LOGIN_USER_ID"]))
        {
            ?>
            <tr>
                <td nowrap class="TableData"> <?=_("是否批量添加：")?></td>
                <td class="TableData"><input type="checkbox" name="batch" id="batch"></td>
            </tr>
            <tr>
                <td nowrap class="TableData"><?=_("出差人：")?></td>
                <td nowrap class="TableData" id="WaiChuRenYuan2" style="display: none">
                    <input type="hidden" name="COPY_TO_ID" value="">
                    <textarea cols=21 name="COPY_TO_NAME" rows=2  wrap="yes" readonly></textarea>
                    <a href="javascript:;" class="orgAdd" onClick="SelectUser('7','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("选择")?></a>
                    <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
                    <span style="font-size:12px;">  <?=_("（说明：不填写为本人出差登记。）")?></span>
                </td>
                <td class="TableData" id="WaiChuRenYuan1">
                    <input type="hidden" name="TO_ID" value="">
                    <input type="text" name="TO_NAME" size="13"  value="" readonly>&nbsp;
                    <a href="javascript:;" class="orgAdd orgAdds" name="orgAdd" title="<?=_("指定出差人")?>"><?=_("指定")?></a>
                    <span style="font-size:12px;"><?=_("  (说明：不填写为本人出差登记。)")?></span>
                </td>
            </tr>
            <?
        }
        ?>
        <tr>
            <td nowrap class="TableData"><?=_("出差地点：")?></td>
            <td class="TableData">
                <input type="text" name="EVECTION_DEST" size="50" maxlength="100" class="validate[required]" data-prompt-position="centerRight:0,-8"  value="">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("出差时间：")?></td>
            <td class="TableData">
                <input type="text" id="start_time" name="EVECTION_DATE1" size="15" maxlength="10" class="validate[required]" data-prompt-position="topRight:0,-8" value="<?=$CUR_DATE?>" onClick="WdatePicker()"/>
                <?=_("至")?>
                <input type="text" name="EVECTION_DATE2" size="15" maxlength="10" class="validate[required]" data-prompt-position="centerRight:0,-8" value="<?=$CUR_DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("事由：")?></td>
            <td class="TableData">
                <textarea name="REASON" class="validate[required]" data-prompt-position="centerRight:0,18" cols="50" rows="4" ></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("审批人：")?></td>
            <td class="TableData">
                <select name="LEADER_ID" id="leader_id" >
                    <?
                    include_once("../../manager.inc.php");
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("提醒：")?></td>
            <td class="TableData">
                <?=sms_remind(6);?>
            </td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan="2" nowrap>
                <input type="submit" id="BUTTON" onClick='sendForm();' value="<?=_("确定")?>" class="btn btn-primary" title="<?=_("申请出差")?>">&nbsp;&nbsp; <input type="button" value="<?=_("返回")?>" class="btn" onclick="location='../'">
            </td>
        </tr>
</form>
</table>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery("#batch").click(function(){
        if(jQuery("#batch").is(":checked"))
        {
            jQuery("#WaiChuRenYuan2").show();
            jQuery("#WaiChuRenYuan1").hide();
        }
        else
        {
            jQuery("#WaiChuRenYuan2").hide();
            jQuery("#WaiChuRenYuan1").show();
        }
    })
});
</script>
</body>
</html>
