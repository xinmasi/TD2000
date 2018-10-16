<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
header("Cache-control: private");
$PARA_ARRAY=get_sys_para("LEAVE_BY_SENIORITY,ENTRY_RESET_LEAVE");
$entry_reset_leave = $PARA_ARRAY["ENTRY_RESET_LEAVE"];//是否开启按入职日期计算年假
$leave_by_seniority = $PARA_ARRAY["LEAVE_BY_SENIORITY"];//是否开启按工龄计算年假

$CUR_DATE=date("Y-m-d",time());
$query = "SELECT LEAVE_TYPE,DATES_EMPLOYED from HR_STAFF_INFO where USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $DATES_EMPLOYED = $ROW["DATES_EMPLOYED"];//入职时间
    $LEAVE_TYPE1=$ROW["LEAVE_TYPE"];//年休假总计
}
if($DATES_EMPLOYED!="0000-00-00" && $DATES_EMPLOYED!="")
{
    $agearray = explode("-",$DATES_EMPLOYED);
    $cur = explode("-",$CUR_DATE);
    $year=$agearray[0];
    $month=(int)$agearray[1];
    $day=(int)$agearray[2];
    if(date("Y")>=$year)
    {
        if((int)date("m")>$month ||((int)date("m")==$month && (int)date("d")>=$day))
        {
            $JOB_AGE=date("Y")-$year;
        }
        else
        {
            $JOB_AGE=date("Y")-$year-1;
        }
    }
    else
    {
        $JOB_AGE=0;
    }
}
else
{
    $JOB_AGE="";
}
if($leave_by_seniority=="1")
{
    $sql = "select leave_day from attend_leave_param where working_years <= '$JOB_AGE' order by working_years DESC";
    $result= exequery(TD::conn(),$sql);
    if($ROW=mysql_fetch_array($result))
    {
        $LEAVE_TYPE1 = $ROW['leave_day'];
    }
    else
    {
        $LEAVE_TYPE1 = 0;
    }
}
if($DATES_EMPLOYED=="" || $DATES_EMPLOYED=="0000-00-00" || $entry_reset_leave==0)
{
    //获取SYS_PARA数据库的年休假开始时间和结束时间20131014
    $query="select * from SYS_PARA where PARA_NAME='ANNUAL_BEGIN_TIME'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $ANNUAL_BEGIN_TIME=$ROW["PARA_VALUE"];
    $query="select * from SYS_PARA where PARA_NAME='ANNUAL_END_TIME'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $ANNUAL_END_TIME=$ROW["PARA_VALUE"];

    $CUR_YEAR = date("Y",time());
    $BEGIN_TIME = $CUR_YEAR."$ANNUAL_BEGIN_TIME";
    $END_TIME = $CUR_YEAR."$ANNUAL_END_TIME";
    //$BEGIN_TIME=substr($CUR_DATE,0,4)."-01-01 00:00:01";
    //$END_TIME=substr($CUR_DATE,0,4)."-12-30 23:59:59";
    //如果格式为-01-01 00:00:01，则年数加1
    if('-01-01 00:00:01' != $ANNUAL_BEGIN_TIME){
        $CUR_YEAR +=1;
        $END_TIME = $CUR_YEAR.$ANNUAL_END_TIME;
    }


    $query = "SELECT * from ATTEND_LEAVE where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and (ALLOW='1' or ALLOW='3'or ALLOW='0') and LEAVE_DATE1 >='$BEGIN_TIME' and LEAVE_DATE1 <='$END_TIME'";
    $cursor= exequery(TD::conn(),$query);
    $LEAVE_DAYS=0;
    $ANNUAL_LEAVE_DAYS=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
        $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
        $ANNUAL_LEAVE=$ROW["ANNUAL_LEAVE"];

        $DAY_DIFF= DateDiff_("d",$LEAVE_DATE1,$LEAVE_DATE2);

        $LEAVE_DAYS+=$DAY_DIFF;
        $LEAVE_DAYS=number_format($LEAVE_DAYS, 1, '.', ' ');
        $ANNUAL_LEAVE_DAYS+=$ANNUAL_LEAVE;
        $ANNUAL_LEAVE_DAYS=number_format($ANNUAL_LEAVE_DAYS, 1, '.', ' ');
    }
}
else
{
    $str   = strtok($DATES_EMPLOYED,"-");
    $year  = $str;
    $str   = strtok("-");
    $month = $str;
    $str   = strtok(" ");
    $day   = $str;

    $cur_year  = date("Y",time());
    $cur_month = date("m",time());
    $cur_day   = date("d",time());
    $cur_time  = date("Y-m-d H:i:s",time());

    $annual_leave_days = 0;

    if((int)$cur_month>(int)$month || ((int)$cur_month==(int)$month && (int)$cur_day>(int)$day))
    {
        $begin_time = $cur_year."-".$month."-".$day." 00:00:01";
        $cur_years  = $cur_year+1;
        $end_time   = $cur_years."-".$month."-".$day." 00:00:01";;

    }else
    {
        $cur_years  = $cur_year-1;
        $begin_time = $cur_years."-".$month."-".$day." 00:00:01";
        $end_time   = $cur_year."-".$month."-".$day." 00:00:01";;
    }
    $query = "SELECT * from ATTEND_LEAVE where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and (ALLOW='1' or ALLOW='3'or ALLOW='0') and LEAVE_DATE1 >='$begin_time' and LEAVE_DATE1 <='$end_time'";
    $cursor= exequery(TD::conn(),$query);
    $LEAVE_DAYS=0;
    $ANNUAL_LEAVE_DAYS=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
        $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
        $ANNUAL_LEAVE=$ROW["ANNUAL_LEAVE"];

        $DAY_DIFF= DateDiff_("d",$LEAVE_DATE1,$LEAVE_DATE2);

        $LEAVE_DAYS+=$DAY_DIFF;
        $LEAVE_DAYS=number_format($LEAVE_DAYS, 1, '.', ' ');
        $ANNUAL_LEAVE_DAYS+=$ANNUAL_LEAVE;
        $ANNUAL_LEAVE_DAYS=number_format($ANNUAL_LEAVE_DAYS, 1, '.', ' ');
    }
}
$ANNUAL_LEAVE_LEFT=number_format(($LEAVE_TYPE1-$ANNUAL_LEAVE_DAYS), 1, '.', ' ');
if($ANNUAL_LEAVE_LEFT < 0)
    $ANNUAL_LEAVE_LEFT=0;


if($ANNUAL_LEAVE_LEFT!="")
{
    $LEAVE_DATE1="";
    $LEAVE_DATE2="";

}

$HTML_PAGE_TITLE = _("新建请假登记");
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
    // var assign_user_id = jQuery("#TO_ID").val();
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

    jQuery("#batch").click(function(){
        if(jQuery("#batch").is(":checked"))
        {
            jQuery("#WaiChuRenYuan2").show();
            jQuery("#WaiChuRenYuan1").hide();
            jQuery("#select_id option[value='3']").remove();
            jQuery("#ann_display").hide();
            jQuery("#get_ann_button").hide();
        }
        else
        {
            jQuery("#WaiChuRenYuan2").hide();
            jQuery("#WaiChuRenYuan1").show();
            jQuery("#select_id").append("<option value='3'><?=_("年假")?></option>")
        }
    })

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

    if (jQuery("#batch").attr('checked'))
    {
        jQuery("#WaiChuRenYuan2").show();
        jQuery("#WaiChuRenYuan1").hide();
        jQuery("#select_id option[value='3']").remove();
        jQuery("#ann_display").hide();
        jQuery("#get_ann_button").hide();
    }

});
var ANNUAL_LEAVE_LEFT = <?=$ANNUAL_LEAVE_LEFT?>;


function CheckForm()
{
    var temp_time = "<?=$CUR_DATE?>";

    if(document.form1.LEAVE_TYPE2.value==3)
    {
        
        return (true);
    }
    else
    {
        if(document.form1.ANNUAL_LEAVE.value < 0)
        {
            alert("<?=_("使用年休假天数必须大于或等于0")?>");
            return (false);
        }
    }
    if(parseFloat(document.form1.ANNUAL_LEAVE.value) > parseFloat(ANNUAL_LEAVE_LEFT))
    { alert("<?=_("使用年休假天数应小于或等于年休假剩余天数！")?>");
        return (false);
    }
    if(document.form1.LEAVE_TYPE.value=="")
    { alert("<?=_("请假原因不能为空！")?>");
        return (false);
    }
    if(document.form1.LEAVE_DATE1.value=="")
    { alert("<?=_("请假开始时间不能为空！")?>");
        return (false);
    }
    if(document.form1.LEAVE_DATE2.value=="")
    { alert("<?=_("请假结束时间不能为空！")?>");
        return (false);
    }

    return (true);
}
function resetTime()
{
    document.form1.LEAVE_DATE1.value="<?=date("Y-m-d H:i:s",time())?>";
}
function resetTime1()
{
    document.form1.LEAVE_DATE2.value="<?=date("Y-m-d H:i:s",time())?>";
}
$(document).ready(function(){
    if(document.all)
        $("#getanns").hide();
});
function getann()
{
    var to_id=document.form1.TO_ID.value;
    if(to_id!="")
    {
        url="get_ann.php?TO_ID="+to_id;
        $.get(url,'',function(data)
        {
            ANNUAL_LEAVE_LEFT=data;
            $("#ann").text(ANNUAL_LEAVE_LEFT);
            $("#LEFT_ANUUAL_LEAVE").text(ANNUAL_LEAVE_LEFT);
            if(parseFloat(ANNUAL_LEAVE_LEFT)<=0)
                $("input#ANNUAL_LEAVE").attr("readonly","readonly");
            else
            {
                $("input#ANNUAL_LEAVE").removeAttr("readonly");
                $("input#ANNUAL_LEAVE").val("0.0");
                document.form1.ANNUAL_LEAVE_LEFT.value=ANNUAL_LEAVE_LEFT;
            }
        });
    }
    else
        ANNUAL_LEAVE_LEFT=<?=$ANNUAL_LEAVE_LEFT?>;
    $("#ann").text(ANNUAL_LEAVE_LEFT);
    if(parseFloat(ANNUAL_LEAVE_LEFT)<=0)
        $("input#ANNUAL_LEAVE").attr("readonly","readonly");
    else
    {
        $("input#ANNUAL_LEAVE").removeAttr("readonly");
        $("input#ANNUAL_LEAVE").val("0.0");
    }
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
function show_ann(val)
{
    if(val==3)
    {
        document.getElementById("ann_display").style.display="";
        document.getElementById("get_ann_button").style.display="";
    }
    else
    {
        document.getElementById("ANNUAL_LEAVE").value="0.0";
        document.getElementById("LEFT_ANUUAL_LEAVE").innerHTML=parseFloat(document.getElementById("ann").innerHTML).toFixed(1);
        document.getElementById("ann_display").style.display="none";
        document.getElementById("get_ann_button").style.display="none";
    }
}
function getAnnLeft(real_ann)
{
    total_ann_left=parseFloat(document.getElementById("ann").innerHTML);
    if(document.form1.ANNUAL_LEAVE.value <=0)
    {
        alert("<?=_("使用年休假天数必须大于0")?>");
        document.form1.ANNUAL_LEAVE.value="0.0";
        return (false);
    }
    if(parseFloat(document.form1.ANNUAL_LEAVE.value) > parseFloat(ANNUAL_LEAVE_LEFT))
    {
        alert("<?=_("使用年休假天数应小于或等于年休假剩余天数！")?>");
        document.form1.ANNUAL_LEAVE.value="0.0"
        document.form1.ANNUAL_LEAVE.focus();
        return (false);
    }
    if(real_ann==""||real_ann==0)
        document.getElementById("ANNUAL_LEAVE").value="0.0";
    else
    {
        var left_ann=parseFloat(total_ann_left) - parseFloat(real_ann);
        left_ann=left_ann.toFixed(1);
        document.getElementById("LEFT_ANUUAL_LEAVE").innerHTML=left_ann;
    }
}
</script>

<body class="bodycolor attendance" topmargin="5" onload="document.form1.LEAVE_TYPE.focus();">

<h5 class="attendance-title"><span class="big3"> <?=_("请假登记")?></span></h5>


<br>
<form action="submit.php"  method="post" id="form1" name="form1" class="big1">
    <table class="TableBlock" width="90%" align="center">
        <?
        $query = "select DEPT_HR_MANAGER from HR_MANAGER where DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."'";
        $cursor=exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
            $DEPT_HR_MANAGER=$ROW["DEPT_HR_MANAGER"];

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
                <td nowrap class="TableData"> <?=_("请假人：")?></td>
                <td nowrap class="TableData" id="WaiChuRenYuan2" style="display: none">
                    <input type="hidden" name="COPY_TO_ID" value="">
                    <textarea cols=21 name="COPY_TO_NAME" rows=2  wrap="yes" readonly></textarea>
                    <a href="javascript:;" class="orgAdd" onClick="SelectUser('7','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("选择")?></a>
                    <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
                    <span style="font-size:12px;">  <?=_("（说明：不填写为本人请假登记。）")?></span>
                </td>

                <td class="TableData" id="WaiChuRenYuan1" >
                    <div>
                        <span>
                            <input type="hidden" name="TO_ID" value="">
                            <input type="text" name="TO_NAME" size="13" onpropertychange="getann()"  value="" readonly>&nbsp;
                            <a href="javascript:;" class="orgAdd orgAdds" name="orgAdd" title="<?=_("指定请假人")?>"><?=_("指定")?></a>&nbsp;
                        </span>
                        <span style="font-size:12px;"><?=_("（说明：不填写为本人请假登记。）")?></span>
                    </div>
                </td>
            </tr>
            <?
        }
        ?>
        <tr>
            <td nowrap class="TableData"> <?=_("请假原因：")?></td>
            <td class="TableData">
                <textarea name="LEAVE_TYPE" class="validate[required]" data-prompt-position="centerRight:0,18" cols="60" rows="3"><?=$LEAVE_TYPE?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("请假时间：")?></td>
            <td class="TableData">
                <input type="text" id="start_time" name="LEAVE_DATE1" size="20" maxlength="20" class=" validate[required]" data-prompt-position="topRight:-150,-8" value="<?=$LEAVE_DATE1?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
                <a href="javascript:resetTime();"><?=_("置为当前时间")?></a>
                <?=_("至")?>
                <input type="text" name="LEAVE_DATE2" size="20" maxlength="20" class=" validate[required]" data-prompt-position="topRight:-150,-8" value="<?=$LEAVE_DATE2?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'#F{$dp.$D(\'start_time\')}'})"/>
                <a href="javascript:resetTime1();"><?=_("置为当前时间")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("请假类型：")?></td>
            <td class="TableData">
                <select name="LEAVE_TYPE2" id="select_id"  onchange="show_ann(this.value)">
                    <?=hrms_code_list("ATTEND_LEAVE",$LEAVE_TYPE2);?>
                </select>
            </td>
        </tr>
        <tr style="display:none" id="ann_display">
            <td nowrap class="TableData"> <?=_("使用年休假：")?></td>
            <input type="hidden" name="ANNUAL_LEAVE_LEFT" value="<?=$ANNUAL_LEAVE_LEFT?>">
            <td class="TableData">
                <?=_("使用之前<font color='red'><span id='ann'>$ANNUAL_LEAVE_LEFT</span></font>天,本次使用<font color='red'><input type=\"text\" name=\"ANNUAL_LEAVE\" id=\"ANNUAL_LEAVE\" size=\"4\" maxlength=\"4\"  value=\"0.0\" onchange='getAnnLeft(this.value)'  if($ANNUAL_LEAVE_LEFT<=0) echo \"readonly\";></font>天,本次使用后剩余<font color='red'><span name='LEFT_ANUUAL_LEAVE' id='LEFT_ANUUAL_LEAVE'>$ANNUAL_LEAVE_LEFT</span></font>天 ")?>
                <span id="get_ann_button" style="display:none;">
                      <input type="button" id="get_ann_button" class="btn" value="<?=_("获取年假剩余天数")?>" title="<?=_("获取制定请假人的剩余年假天数")?>" onClick="getann()"/>
                </span><br />
                <span style="color:red"><?=_("说明：1.若使用年休假时间大于实际请假时间，以实际请假时间为准。")?><br />&nbsp;&nbsp;&nbsp;<?=_("2.年休假统计只支持1天或0.5天请谨慎填写")?><br />&nbsp;&nbsp;&nbsp;<?=_("3.使用年休假时,计算请假时间以年休假天数为准")?></span>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("审批人：")?></td>
            <td class="TableData">
                <select name="LEADER_ID" id="leader_id">
                    <?
                    include_once("../../manager.inc.php");
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("事务提醒：")?></td>
            <td class="TableData"><?=sms_remind(6);?></td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan="2" nowrap>
                <input type="submit" id="BUTTON" onClick='sendForm();' value="<?=_("请假")?>" class="btn btn-primary" title="<?=_("请假登记")?>">&nbsp;&nbsp;
                <input type="button" value="<?=_("返回")?>" class="btn" onclick="location='../'">&nbsp;&nbsp;
            </td>
        </tr>
    </table>
</form>
</body>
</html>