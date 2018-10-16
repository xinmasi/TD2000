<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
header("Cache-control: private");
$CUR_TIME=date("Y-m-d H:i:s",time());

$HTML_PAGE_TITLE = _("新建加班登记");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
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
            jQuery('#approve_id').html(data.leader_id);
        }
    });
}
function count_time(){
    var s_time = jQuery("#start_time").val();
    var e_time = jQuery("#end_time").val();

    if(s_time=='' || e_time==''){
        jQuery("#overtime_hours").val(0);
        jQuery("#overtime_minutes").val(0);
    }else{
        var s_ms = Date.parse(new Date(s_time.replace(/-/g, "/")));
        var e_ms = Date.parse(new Date(e_time.replace(/-/g, "/")));

        if(e_ms > s_ms){
            var diff_ms = parseInt((e_ms - s_ms)/1000);
            var diff_h = parseInt(diff_ms/3600);
            var diff_m = parseInt((diff_ms % 3600)/60);

            jQuery("#overtime_hours").val(diff_h);
            jQuery("#overtime_minutes").val(diff_m);
        }else{
            jQuery("#overtime_hours").val(0);
            jQuery("#overtime_minutes").val(0);
        }
    }
}

jQuery(document).ready(function(){
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});

    jQuery("#start_time").blur(function(){
        count_time();
    });

    jQuery("#end_time").blur(function(){
        count_time();
    });

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
    if(document.form1.START_TIME.value=="")
    { alert("<?=_("加班开始时间不能为空！")?>");
        return (false);
    }
    if(document.form1.END_TIME.value=="")
    { alert("<?=_("加班结束时间不能为空！")?>");
        return (false);
    }
    if(document.form1.OVERTIME_CONTENT.value=="")
    { alert("<?=_("加班内容不能为空！")?>");
        return (false);
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
function resetTime()
{
    document.form1.START_TIME.value="<?=date("Y-m-d H:i:s",time())?>";
    count_time();
}
function resetTime1()
{
    document.form1.END_TIME.value="<?=date("Y-m-d H:i:s",time())?>";
    count_time();
}
</script>


<body class="bodycolor attendance" onload="document.form1.OVERTIME_CONTENT.focus();">

<h5 class="attendance-title"><span class="big3"> <?=_("新建加班登记")?></span></h5><br>
<br>

<form action="overtime.php"  method="post" id="form1" name="form1" class="big1" >
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

        $OVERTIME_HOURS = $OVERTIME_HOURS ? $OVERTIME_HOURS : 0;
        $OVERTIME_MINUTES = $OVERTIME_MINUTES ? $OVERTIME_MINUTES : 0;

        if($_SESSION["LOGIN_USER_PRIV"]==1 || find_id($DEPT_HR_MANAGER,$_SESSION["LOGIN_USER_ID"]) || find_id($MANAGERS1,$_SESSION["LOGIN_USER_ID"]))
        {
            ?>
            <tr>
                <td nowrap class="TableData"> <?=_("是否批量添加：")?></td>
                <td class="TableData"><input type="checkbox" name="batch" id="batch"></td>
            </tr>
            <tr>
                <td nowrap class="TableData"> <?=_("加班人：")?></td>
                <td nowrap class="TableData" id="WaiChuRenYuan2" style="display: none">
                    <input type="hidden" name="COPY_TO_ID" value="">
                    <textarea cols=21 name="COPY_TO_NAME" rows=2  wrap="yes" readonly></textarea>
                    <a href="javascript:;" class="orgAdd" name="orgAdd" onClick="SelectUser('7','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("选择")?></a>
                    <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
                    <span style="font-size:12px;">  <?=_("（说明：不填写为本人加班登记。）")?></span>
                </td>
                <td class="TableData" id="WaiChuRenYuan1">
                    <input type="hidden" name="TO_ID" value="">
                    <input type="text" name="TO_NAME" size="13"  value="" readonly>&nbsp;
                    <a href="javascript:;" class="orgAdd orgAdds"  title=<?=_("指定加班人")?>><?=_("指定")?></a>
                    <span style="font-size:12px;"><?=_("(说明：不填写为本人加班登记。)")?></span>
                </td>
            </tr>
            <?
        }
        ?>
        <tr>
            <td nowrap class="TableData"> <?=_("加班开始时间：")?></td>
            <td class="TableData">
                <input type="text" id="start_time" name="START_TIME" size="20" maxlength="20" class=" validate[required]"  data-prompt-position="centerRight:0,-8" value="<?=$START_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
                &nbsp;&nbsp;<a href="javascript:resetTime();"><?=_("置为当前时间")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("加班结束时间：")?></td>
            <td class="TableData">
                <input type="text" id="end_time" name="END_TIME" size="20" maxlength="20" class=" validate[required]"  data-prompt-position="centerRight:0,-8" value="<?=$END_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'#F{$dp.$D(\'start_time\')}'})"/>
                &nbsp;&nbsp;<a href="javascript:resetTime1();"><?=_("置为当前时间")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("加班时长：")?></td>
            <td class="TableData">
                <input type="text" name="OVERTIME_HOURS" id="overtime_hours" size="2" maxlength="2" class=" validate[required,custom[nonNegative]]" data-prompt-position="topRight:-30,-8" value="<?=$OVERTIME_HOURS?>"><?=_("小时")?>
                <input type="text" name="OVERTIME_MINUTES" id="overtime_minutes" size="2" maxlength="2" class=" validate[required,custom[nonNegative]]" data-prompt-position="topRight:0,-8" value="<?=$OVERTIME_MINUTES?>"><?=_("分")?>
                <?=_("(注：不填写则按加班结束时间与加班开始时间差值计算得到小时、分钟数)")?>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("加班内容：")?></td>
            <td class="TableData">
                <textarea name="OVERTIME_CONTENT" class=" validate[required]" data-prompt-position="centerRight:0,18" cols="60" rows="3"><?=$OVERTIME_CONTENT?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("审批人：")?></td>
            <td class="TableData">
                <select name="APPROVE_ID" id="approve_id">
                    <?
                    include_once("../../manager.inc.php");
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("事务提醒：")?></td>
            <td class="TableData"> <?=sms_remind(6);?></td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan="2" nowrap>
                <input type="submit" id="BUTTON" onClick='sendForm();' value=<?=_("申请加班")?> class="btn btn-primary" title=<?=_("申请加班")?>>&nbsp;&nbsp;&nbsp;
                <input type="button" value=<?=_("返回上页")?> class="btn" onclick="location='../'">&nbsp;&nbsp;
            </td>
        </tr>
    </table>
</form>
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