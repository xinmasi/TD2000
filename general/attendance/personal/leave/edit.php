<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("./new/get_ann_func.php");


$PARA_ARRAY=get_sys_para("LEAVE_BY_SENIORITY,ENTRY_RESET_LEAVE");
$entry_reset_leave = $PARA_ARRAY["ENTRY_RESET_LEAVE"];//是否开启按入职日期计算年假
$leave_by_seniority = $PARA_ARRAY["LEAVE_BY_SENIORITY"];//是否开启按工龄计算年假

$CUR_DATE=date("Y-m-d H:i:s",time());

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


    $query = "SELECT * from ATTEND_LEAVE where LEAVE_ID!='$LEAVE_ID' AND USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and (ALLOW='1' or ALLOW='3'or ALLOW='0') and LEAVE_DATE1 >='$BEGIN_TIME' and LEAVE_DATE1 <='$END_TIME'";
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
    $query = "SELECT * from ATTEND_LEAVE where LEAVE_ID!='$LEAVE_ID' AND USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and (ALLOW='1' or ALLOW='3'or ALLOW='0') and LEAVE_DATE1 >='$begin_time' and LEAVE_DATE1 <='$end_time'";
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

$query = "SELECT * from ATTEND_LEAVE where LEAVE_ID='$LEAVE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $LEAVE_TYPE=$ROW["LEAVE_TYPE"];
    $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
    $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
    $ANNUAL_LEAVE=$ROW["ANNUAL_LEAVE"];
    $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
}

//$ANNUAL_LEAVE_LEFT += $ANNUAL_LEAVE;

$ANN_LEFT=$ANNUAL_LEAVE_LEFT-$ANNUAL_LEAVE;
$ANN_LEFT=number_format($ANN_LEFT, 1, '.', ' ');


$HTML_PAGE_TITLE = _("修改请假登记");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
    var ANNUAL_LEAVE_LEFT = <?=$ANNUAL_LEAVE_LEFT?>;
    function show_ann(val)
    {
        if(val==3)
        {
            document.getElementById("ann_display").style.display="";
            document.getElementById("ANNUAL_LEAVE").value="<?=$ANNUAL_LEAVE ?>";
            document.getElementById("LEFT_ANUUAL_LEAVE").innerHTML="<?=$ANN_LEFT ?>";
        }
        else
        {
            document.getElementById("ANNUAL_LEAVE").value="0.0";
            document.getElementById("LEFT_ANUUAL_LEAVE").innerHTML=parseFloat(document.getElementById("ann").innerHTML).toFixed(1);
            document.getElementById("ann_display").style.display="none";
        }
    }
    function getAnnLeft(real_ann)
    {
        total_ann_left=parseFloat(document.getElementById("ann").innerHTML);
        if(document.form1.ANNUAL_LEAVE.value < 0)
        {
            alert("<?=_("使用年休假天数必须大于或等于0")?>");
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
            var left_ann=total_ann_left - parseFloat(real_ann);
            left_ann=left_ann.toFixed(1);
            document.getElementById("LEFT_ANUUAL_LEAVE").innerHTML=left_ann;
        }
    }
    function CheckForm()
    {
        var temp_time = "<?=$CUR_DATE?>";

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
        if(document.form1.LEAVE_TYPE2.value==3)
        {
            if(document.form1.ANNUAL_LEAVE.value <= 0)
            {
                alert("<?=_("使用年休假天数必须大于0")?>");
                document.form1.ANNUAL_LEAVE.focus();
                return (false);
            }
        }
        else
        {
            if(document.form1.ANNUAL_LEAVE.value < 0)
            {
                alert("<?=_("使用年休假天数必须大于或等于0")?>");
                return (false);
            }
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
</script>


<body class="bodycolor attendance" topmargin="5" onLoad="document.form1.LEAVE_TYPE.focus();">

<h5 class="attendance-title"><span class="big3"> <?=_("修改请假登记")?></span>
</h5>

<br>
<form action="edit_submit.php"  method="post" name="form1" class="big1" onSubmit="return CheckForm();">
    <table class="TableBlock" width="90%" align="center">
        <tr>
            <td nowrap class="TableData"> <?=_("请假原因：")?></td>
            <td class="TableData">
                <textarea name="LEAVE_TYPE"  cols="60" rows="3"><?=$LEAVE_TYPE?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("请假时间：")?></td>
            <td class="TableData">
                <input type="text" name="LEAVE_DATE1" size="20" maxlength="20"  value="<?=$LEAVE_DATE1?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
                <a href="javascript:resetTime();"><?=_("置为当前时间")?></a>
                <?=_("至")?>
                <input type="text" name="LEAVE_DATE2" size="20" maxlength="20"  value="<?=$LEAVE_DATE2?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
                <a href="javascript:resetTime1();"><?=_("置为当前时间")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("请假类型：")?></td>
            <td class="TableData">
                <select name="LEAVE_TYPE2"  onChange="show_ann(this.value)">
                    <?=hrms_code_list("ATTEND_LEAVE",$LEAVE_TYPE2);?>
                </select>
            </td>
        </tr>
        <tr <? if($LEAVE_TYPE2!=3) echo 'style="display:none"';?>" id="ann_display">
        <td nowrap class="TableData"> <?=_("使用年休假：")?></td>
        <input type="hidden" name="ANNUAL_LEAVE_LEFT" value="<?=$ANNUAL_LEAVE_LEFT?>">
        <td class="TableData">
            <?=sprintf(_("使用之前%s天，本次使用%s天，本次使用后剩余%s天"), "<font color='red'><span id='ann'>$ANNUAL_LEAVE_LEFT</span></font>", "<font color='red'><input type=\"text\" name=\"ANNUAL_LEAVE\" id=\"ANNUAL_LEAVE\" size=\"4\" maxlength=\"4\"  value=\"$ANNUAL_LEAVE\" onchange='getAnnLeft(this.value)'  if($ANNUAL_LEAVE_LEFT<=0) echo \"readonly\";></font>", "<font color='red'><span name='LEFT_ANUUAL_LEAVE' id='LEFT_ANUUAL_LEAVE'>$ANN_LEFT</span></font>")?><br />
            <span style="color:red"><?=_("说明：1.若使用年休假时间大于实际请假时间，以实际请假时间为准。")?><br />&nbsp;&nbsp;&nbsp;<?=_("2.年休假统计只支持1天或0.5天请谨慎填写")?><br />&nbsp;&nbsp;&nbsp;<?=_("3.使用年休假时,计算请假时间以年休假天数为准")?></span>
        </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("审批人：")?></td>
            <td class="TableData">
                <select name="LEADER_ID" >
                    <?
                    include_once("../manager.inc.php");
                    ?>
                </select>
                <?=_("请假登记修改后需重新审批")?>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("事务提醒：")?></td>
            <td class="TableData"><?=sms_remind(6);?></td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan="2" nowrap>
                <input type="submit" value="<?=_("保存修改")?>" class="btn btn-primary">&nbsp;&nbsp;
                <input type="button" value="<?=_("返回")?>" class="btn" onClick="location='./'">&nbsp;&nbsp;
            </td>
        </tr>
    </table>
    <input type="hidden" name="LEAVE_ID" value="<?=$LEAVE_ID?>">
</form>

</body>
</html>