<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("销假");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function CheckForm()
{
    var annual_day=document.getElementById("ANNUAL_LEAVE").value;
    if(annual_day=="")
    {
        annual_day=0.0;
        document.getElementById("ANNUAL_LEAVE").value=0.0;
    }
    if(parseInt(annual_day)>0)
    {
        if(confirm(sprintf("<?=_("确认占用年休假为%s天")?>", annual_day)))
            document.form1.submit();
    }
    else
    {
        document.form1.submit();
    }

}
</script>

<?

$query="select * from SYS_PARA where PARA_NAME='SMS_REMIND'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $PARA_VALUE=$ROW["PARA_VALUE"];
$SMS_REMIND1=substr($PARA_VALUE,0,strpos($PARA_VALUE,"|"));
$SMS2_REMIND1_TMP=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);
$SMS2_REMIND1=substr($SMS2_REMIND1_TMP,0,strpos($SMS2_REMIND1_TMP,"|"));

if($OP=="1")
{
    if(compare_date_time($LEAVE_DATE1_I,$LEAVE_DATES2)>=0)
    {
        Message(_("错误"),_("请假结束时间应晚于请假开始时间"));
        Button_Back();
        exit;
    }

    if($ANNUAL_LEAVE<0)
    {
        Message(_("错误"),_("占年年休假必须大于0"));
        Button_Back();
        exit;
    }
    if(!preg_match('/^[0-9]\d*([.][0|5])?$/', $ANNUAL_LEAVE))
    {
        Message(_("错误"),_("占年休假只支持1天或0.5天！"));
        Button_Back();
        exit;
    }
    if($ANNUAL_LEAVE != "0.0" && $ANNUAL_LEAVE != $ANNUAL_LEAVE_I)
    {

        //验证年假剩余天数
        $PARA_ARRAY=get_sys_para("LEAVE_BY_SENIORITY,ENTRY_RESET_LEAVE");
        $entry_reset_leave = $PARA_ARRAY["ENTRY_RESET_LEAVE"];//是否开启按入职日期计算年假
        $leave_by_seniority = $PARA_ARRAY["LEAVE_BY_SENIORITY"];//是否开启按工龄计算年假

        $CUR_DATE=date("Y-m-d H:i:s",time());

        $query = "SELECT LEAVE_TYPE,DATES_EMPLOYED from HR_STAFF_INFO where USER_ID='$USER_ID'";
        $cursor= exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
        {
            $DATES_EMPLOYED = $ROW["DATES_EMPLOYED"];//入职时间
            $LEAVE_TYPE1    = $ROW["LEAVE_TYPE"];//年休假总计
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
            $sql = "SELECT leave_day FROM attend_leave_param WHERE working_years <= '$JOB_AGE' ORDER BY working_years DESC";
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

            $query = "SELECT * from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3'or ALLOW='0') and LEAVE_DATE1 >='$BEGIN_TIME' and LEAVE_DATE1 <='$END_TIME'";
            $cursor= exequery(TD::conn(),$query);
            $LEAVE_DAYS=0;
            $ANNUAL_LEAVE_DAYS=0;
            while($ROW=mysql_fetch_array($cursor))
            {
                $LEAVE_DATE1    = $ROW["LEAVE_DATE1"];
                $LEAVE_DATE2    = $ROW["LEAVE_DATE2"];
                $ANNUAL_LEAVE1  = $ROW["ANNUAL_LEAVE"];

                $DAY_DIFF= DateDiff_("d",$LEAVE_DATE1,$LEAVE_DATE2);

                $LEAVE_DAYS+=$DAY_DIFF;
                $LEAVE_DAYS=number_format($LEAVE_DAYS, 1, '.', ' ');
                $ANNUAL_LEAVE_DAYS+=$ANNUAL_LEAVE1;
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
            $query = "SELECT * from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3'or ALLOW='0') and LEAVE_DATE1 >='$begin_time' and LEAVE_DATE1 <='$end_time'";
            $cursor= exequery(TD::conn(),$query);
            $LEAVE_DAYS=0;
            $ANNUAL_LEAVE_DAYS=0;
            while($ROW=mysql_fetch_array($cursor))
            {
                $LEAVE_DATE1    = $ROW["LEAVE_DATE1"];
                $LEAVE_DATE2    = $ROW["LEAVE_DATE2"];
                $ANNUAL_LEAVE2  = $ROW["ANNUAL_LEAVE"];

                $DAY_DIFF= DateDiff_("d",$LEAVE_DATE1,$LEAVE_DATE2);

                $LEAVE_DAYS+=$DAY_DIFF;
                $LEAVE_DAYS=number_format($LEAVE_DAYS, 1, '.', ' ');
                $ANNUAL_LEAVE_DAYS+=$ANNUAL_LEAVE2;
                $ANNUAL_LEAVE_DAYS=number_format($ANNUAL_LEAVE_DAYS, 1, '.', ' ');
            }
        }
        $ANNUAL_LEAVE_LEFT=number_format(($LEAVE_TYPE1-$ANNUAL_LEAVE_DAYS), 1, '.', ' ');
        if($ANNUAL_LEAVE_LEFT < 0)
            $ANNUAL_LEAVE_LEFT=0;

        $ANNUAL_LEAVE_LEFT += $ANNUAL_LEAVE_I;
        $ANN_LEFT_I=$ANNUAL_LEAVE_LEFT-$ANNUAL_LEAVE;
        $ANN_LEFT_I=number_format($ANN_LEFT_I, 1, '.', ' ');

        if($ANN_LEFT_I<0)
        {
            Message(_("错误"),_("修改后的占用年假天数已超出年假剩余天数"));
            Button_Back();
            exit;
        }
    }
    $query="select * from ATTEND_LEAVE where LEAVE_ID='$LEAVE_ID'";
    $cursor=exequery(TD::conn(),$query);

    if($ROW=mysql_fetch_array($cursor)){
        $LEAVE_DATE1    = $ROW["LEAVE_DATE1"];
    }
    $DAY_DIFF= DateDiff_("d",$LEAVE_DATE1,$LEAVE_DATES2);
    if($DAY_DIFF< $ANNUAL_LEAVE)
    $ANNUAL_LEAVE=$DAY_DIFF;

    $query="update ATTEND_LEAVE set STATUS='2',LEAVE_DATE2='$LEAVE_DATES2',ANNUAL_LEAVE='$ANNUAL_LEAVE' where LEAVE_ID='$LEAVE_ID'";
    exequery(TD::conn(),$query);

    //---------- 事务提醒 ----------
    $SMS_CONTENT=_("您的销假申请已被批准，已销假！");
    if(find_id($SMS_REMIND1,6))
    {
        $REMIND_URL="attendance/personal/leave";
        send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID,6,$SMS_CONTENT,$REMIND_URL);
    }
    if($MOBILE_FLAG=="1")
        if(find_id($SMS2_REMIND1,6))
            send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$USER_ID,$SMS_CONTENT,6);
    ?>
    <script>
        if(window.opener.location.href.indexOf("connstatus") < 0 ){
            window.opener.location.href = window.parent.opener.location.href+"?connstatus=1";
        }else{
            window.opener.location.reload();
        }
        window.close();
    </script>
    <?
    exit;
}

$query = "SELECT * from ATTEND_LEAVE where LEAVE_ID='$LEAVE_ID'";
$cursor= exequery(TD::conn(),$query, $connstatus);
$CONFIRM_DATE = "";
if($ROW=mysql_fetch_array($cursor))
{

    $LEAVE_DATE1    = $ROW["LEAVE_DATE1"];
    $LEAVE_DATE2    = $ROW["LEAVE_DATE2"];
    $DESTROY_TIME   = $ROW["DESTROY_TIME"];

    if(strtotime($LEAVE_DATE2) <= strtotime($DESTROY_TIME))
        $CONFIRM_DATE = $LEAVE_DATE2;
    else
        $CONFIRM_DATE = $DESTROY_TIME;

    $RECORD_TIME    = $ROW["RECORD_TIME"];
    $LEAVE_TYPE     = $ROW["LEAVE_TYPE"];
    $USER_ID        = $ROW["USER_ID"];
    $ALLOW          = $ROW["ALLOW"];
    $REGISTER_IP    = $ROW["REGISTER_IP"];
    $ANNUAL_LEAVE   = $ROW["ANNUAL_LEAVE"];
    $LEAVE_TYPE2    = $ROW["LEAVE_TYPE2"];
    $LEAVE_TYPE2    = get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");
}

$query = "SELECT USER.DEPT_ID,USER_NAME from USER,DEPARTMENT where USER.DEPT_ID=DEPARTMENT.DEPT_ID and USER_ID='$USER_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $DEPT_ID=$ROW["DEPT_ID"];
    $USER_NAME=$ROW["USER_NAME"];
    $DEPT_LONG_NAME=dept_long_name($DEPT_ID);
}
?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("销假审批")?></span><br>
        </td>
    </tr>
</table>
<br>
<form action="back.php?connstatus=1"  method="post" name="form1" class="big1">
    <table class="TableBlock" width="100%" align="center">
        <tr>
            <td nowrap class="TableData" width=150> <?=_("请假人：")?></td>
            <td class="TableData">
                &nbsp;<span style="font-size:10pt"><?=$USER_NAME?></span>
            </td>
            <td nowrap class="TableData"> <?=_("请假人部门：")?></td>
            <td class="TableData">
                &nbsp;<span style="font-size:10pt"><?=$DEPT_LONG_NAME?></span>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" width=150> <?=_("请假原因：")?></td>
            <td class="TableData" colspan="3"><?=$LEAVE_TYPE?></td>
        </tr>
        <tr>
            <td nowrap class="TableData" width=150> <?=_("申请时间：")?></td>
            <td class="TableData">
                &nbsp;<span style="font-size:10pt"><?=$RECORD_TIME?></span>
            </td>
            <td nowrap class="TableData"> <?=_("开始时间：")?></td>
            <td class="TableData">
                &nbsp;<span style="font-size:10pt"><?=$LEAVE_DATE1."(".get_week($LEAVE_DATE1).")"?></span>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" width=150> <?=_("占年休假：")?></td>
            <td class="TableData">
                &nbsp;<span style="font-size:10pt"><input type="text" size="4"  id="ANNUAL_LEAVE" name="ANNUAL_LEAVE"  value="<?=$ANNUAL_LEAVE?>"><?=_("天")?></span>
            </td>
            <td nowrap class="TableData"> <?=_("登记IP")?></td>
            <td class="TableData">
                &nbsp;<span style="font-size:10pt"><?=$REGISTER_IP?></span>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" width=150> <?=_("申请类型")?></td>
            <td class="TableData">
                &nbsp;<span style="font-size:10pt"><?if($ALLOW=="0")echo _("请假申请");else echo _("销假申请");?></span>
            </td>
            <td nowrap class="TableData"> <?=_("请假类型：")?></td>
            <td class="TableData">
                &nbsp;<span style="font-size:10pt"><?=$LEAVE_TYPE2?></span>
            </td>
        </tr>

        <tr>
            <td nowrap class="TableData" width=150> <?=_("请假时填写的结束时间：")?></td>
            <td class="TableData"  colspan="3">
                &nbsp;<span style="font-size:10pt"><?=$LEAVE_DATE2."(".get_week($LEAVE_DATE2).")"?></span>&nbsp;&nbsp;<input type="button" class="SmallButton" onClick="LEAVE_DATE2.value='<?=$LEAVE_DATE2?>';" value="<?=_("以此时间为准")?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("申请销假时间：")?></td>
            <td class="TableData"  colspan="3">
                &nbsp;<span style="font-size:10pt"><?=$DESTROY_TIME."(".get_week($DESTROY_TIME).")"?></span>&nbsp;&nbsp;<input type="button" class="SmallButton" onClick="LEAVE_DATE2.value='<?=$DESTROY_TIME?>';" value="<?=_("以此时间为准")?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("审批人确认请假结束时间：")?></td>
            <td class="TableData" colspan="3">
                <input type="hidden" id="start_time" value="<?=$LEAVE_DATE1?>">
                <input type="text" name="LEAVE_DATES2" size="20" maxlength="22" class="BigInput" readonly value="<?=$CONFIRM_DATE?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'#F{$dp.$D(\'start_time\')}'})">
            </td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan="4" nowrap>
                <input type="button"  onClick="CheckForm()" value="<?=_("确定")?>" class="BigButton">
                <input type="hidden" name="LEAVE_ID"  value="<?=$LEAVE_ID?>">
                <input type="hidden" name="USER_ID"  value="<?=$USER_ID?>">
                <input type="hidden" name="LEAVE_DATE1_I"  value="<?=$LEAVE_DATE1?>">
                <input type="hidden" name="ANNUAL_LEAVE_I"  value="<?=$ANNUAL_LEAVE?>">
                <input type="hidden" name="MOBILE_FLAG"  value="<?=$MOBILE_FLAG?>">
                <input type="hidden" name="OP"  value="1">
            </td>
        </tr>
    </table>
</form>
</body>
</html>
