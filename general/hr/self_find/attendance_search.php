<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");
include_once("check_func.func.php");

$HTML_PAGE_TITLE = _("考勤情况查询");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script>
function out_edit(OUT_ID)
{
    URL="out_edit.php?OUT_ID="+OUT_ID;
    myleft=(screen.availWidth-780)/2;
    mytop=100;
    mywidth=650;
    myheight=400;
    window.open(URL,"out_edit","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
function overtime_edit(OVERTIME_ID)
{
    URL="overtime_edit.php?OVERTIME_ID="+OVERTIME_ID;
    myleft=(screen.availWidth-780)/2;
    mytop=100;
    mywidth=650;
    myheight=400;
    window.open(URL,"overtime_edit","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
function leave_edit(LEAVE_ID)
{
    URL="leave_edit.php?LEAVE_ID="+LEAVE_ID;
    myleft=(screen.availWidth-780)/2;
    mytop=100;
    mywidth=650;
    myheight=400;
    window.open(URL,"leave_edit","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
function evection_edit(EVECTION_ID)
{
    URL="evection_edit.php?EVECTION_ID="+EVECTION_ID;
    myleft=(screen.availWidth-780)/2;
    mytop=100;
    mywidth=650;
    myheight=400;
    window.open(URL,"evection_edit","height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}
</script>

<body class="" style="margin-left: 20px;">
<?
//----------- 合法性校验 ---------
if($DATE1!="")
{
    $TIME_OK=is_date($DATE1);
    if(!$TIME_OK)
    {
        Message(_("错误"),_("起始日期格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

if($DATE2!="")
{
    $TIME_OK=is_date($DATE2);
    if(!$TIME_OK)
    {
        Message(_("错误"),_("截止日期格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

if(compare_date($DATE1,$DATE2)==1)
{
    Message(_("错误"),_("查询的起始日期不能晚于截止日期"));
    Button_Back();
    exit;
}

$USER_ID = $_SESSION['LOGIN_USER_ID'];
$UID     = $_SESSION['LOGIN_UID'];

/*if(!is_dept_priv($DEPT_ID) && $_SESSION["LOGIN_USER_PRIV"]!=1)
{
    Message(_("错误"),_("不属于管理范围内的用户").$DEPT_ID);
    exit;
}*/

$CUR_DATE=date("Y-m-d",time());

$query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $DAY_TOTAL=$ROW[0]+1;

$MSG = sprintf(_("共 %d 天"), $DAY_TOTAL);
?>

<!-- ----------------------------------- 上下班 ----------------------------- -->
<h5 class="attendance-title"><?=_("上下班统计")?>
    (<?=$_SESSION['LOGIN_USER_NAME']?> )<?=_("从")?> <?=format_date($DATE1)?> <?=_("至")?> <?=format_date($DATE2)?> <?=$MSG?></h5>


<?
$h    = 0;
$k    = 0;
$p    = 0;
$this_day = date("Y-m-d",time());

//获取免签人员
$SYS_PARA_ARRAY = get_sys_para("NO_DUTY_USER",false);
$NO_DUTY_USER   = $SYS_PARA_ARRAY["NO_DUTY_USER"];
$NO_DUTY_USER   = td_trim($NO_DUTY_USER);

for($J=$DATE1;$J<=$DATE2;$J=date("Y-m-d",strtotime($J)+24*3600))
{
    $DUTY_ARR      = array();
    $DUTY_TYPE     = "";

    $user_duty = "";
    //查询当天是否有排班
    $sql = "SELECT duty_type FROM user_duty WHERE uid = '$UID' AND duty_date = '$J'";
    $cursor= exequery(TD::conn(),$sql);
    if($row=mysql_fetch_array($cursor))
    {
        $user_duty = $row[0];
    }
    if(empty($user_duty))
    {
        $k++;
        continue;
    }
    $h++;

    //免签人员只统计应出勤和休息天数
    if($NO_DUTY_USER!="" && find_id($NO_DUTY_USER,$USER_ID))
    {
        continue;
    }

    $sql = "SELECT * FROM attend_duty WHERE date(REGISTER_TIME) = '$J' AND USER_ID = '$USER_ID'";
    $cursor= exequery(TD::conn(),$sql);
    if(mysql_affected_rows()>0)
    {
        while($row=mysql_fetch_array($cursor))
        {
            $DUTY_ARR[$row["REGISTER_TYPE"]]=array(
                "duty_type"     => $row["DUTY_TYPE"],
                "register_time" => $row["REGISTER_TIME"],
                "register_ip"   => $row["REGISTER_IP"],
                "time_late"     => $row["TIME_LATE"],
                "time_early"    => $row["TIME_EARLY"],
                "duty_time"     => $row["DUTY_TIME"],
            );
        }

    }else
    {
        //排除请假、出差
        if(($IS_EVECTION = check_evection($USER_ID,$J))!=0)
            continue;

        $condition = 0;
        //是否有请假/外出记录
        $sqlleave = "SELECT * FROM attend_leave WHERE USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and to_days(LEAVE_DATE1)<=to_days('$J') and to_days(LEAVE_DATE2)>=to_days('$J')";
        $cleave   = exequery(TD::conn(),$sqlleave);
        if(mysql_affected_rows()>0)
        {
            $condition = 1;
        }
        $sqlout = "SELECT * FROM attend_out where USER_ID='$USER_ID' and ALLOW='1' and to_days(SUBMIT_TIME)=to_days('$J')";
        $cout   = exequery(TD::conn(),$sqlout);
        if(mysql_affected_rows()>0)
        {
            $condition = 1;
        }

        if($J!=$this_day && $condition!=1)
        {
            $p++;
            continue;
        }
    }

    $sql1 = "SELECT * FROM attend_config WHERE DUTY_TYPE = '$user_duty'";
    $cursor1= exequery(TD::conn(),$sql1);
    if($row1=mysql_fetch_array($cursor1))
    {
        $DUTY_NAME    = $row1["DUTY_NAME"];
        $GENERAL      = $row1["GENERAL"];
        $DUTY_TYPE_ARR = array();
        for($I=1;$I<=6;$I++)
        {
            $cn = $I%2==0?"2":"1";
            if($row1["DUTY_TIME".$I]!="")
                $DUTY_TYPE_ARR[$I]=array( "DUTY_TIME" => $row1["DUTY_TIME".$I] ,"DUTY_TYPE" => $cn);
        }
    }

    $IS_ALL   = 1;//全勤
    $OUGHT_TO = 1;//应该考勤登记
    $ISEVECTION = 1;
    //if(($IS_HOLIDAY = check_holiday($J))!=0)
    //$OUGHT_TO = 0;

    if(($IS_EVECTION = check_evection($USER_ID,$J))!=0) {
        $ISEVECTION = 0;
    }

    foreach($DUTY_TYPE_ARR as $REGISTER_TYPE => $DUTY_TYPE_ONE)//遍历按排班需要登记的
    {
        $START_OR_END     = $DUTY_TYPE_ONE["DUTY_TYPE"];  //上下班：1：上班，2：下班。
        $DUTY_TIME_OUGHT  = $DUTY_TYPE_ONE["DUTY_TIME"];  //设定的考勤时间。
        $DUTY_ONE_ARR     = $DUTY_ARR[$REGISTER_TYPE];    //相应的登记记录

        $HAS_DUTY=0;
        if(is_array($DUTY_ONE_ARR) && !empty($DUTY_ONE_ARR))
        {
            foreach($DUTY_ONE_ARR as $KEY => $VALUE)
                $$KEY=$VALUE;
            $HAS_DUTY=1;
        }

        if($ISEVECTION==1)
        {
            if(($IS_LEAVE = check_leave($USER_ID,$J,$DUTY_TYPE_ARR[$REGISTER_TYPE]["DUTY_TIME"]))!="0")//是否请假
            {
                $OUGHT_TO=0;
            }
            else if(($IS_OUT = check_out($USER_ID,$J,$DUTY_TYPE_ARR[$REGISTER_TYPE]["DUTY_TIME"]))!="0")//是否外出
            {
                $OUGHT_TO=0;
            }else
            {
                $OUGHT_TO=1;
            }
        }
        else
        {
            $OUGHT_TO=0;
        }

        if($HAS_DUTY==1)//已经登记（都计算）
        {
            $REGISTER_TIME  = $DUTY_ONE_ARR["register_time"];
            $REGISTER_TIME  = strtok($REGISTER_TIME," ");
            $REGISTER_TIME  = strtok(" ");

            //兼容老数据
            if($DUTY_ONE_ARR['duty_time']!="")
            {
                //早退
                $str1 = this_compare_time($REGISTER_TIME,$DUTY_ONE_ARR['duty_time'],$DUTY_ONE_ARR['time_early'],1);
                //迟到
                $str2 = this_compare_time($REGISTER_TIME,$DUTY_ONE_ARR['duty_time'],$DUTY_ONE_ARR['time_late'],0);
            }else
            {
                if($START_OR_END=="1" && compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)==1)
                    $str2 = 1;
                if($START_OR_END=="2" && compare_time($REGISTER_TIME,$DUTY_TIME_OUGHT)==-1)
                    $str1 = -1;
            }

            if($START_OR_END=="1" && $str2==1)
            {
                if($OUGHT_TO!=0)
                {
                    $DUTY_TIME_OUGHT = substr($DUTY_TIME_OUGHT,0,strlen($DUTY_TIME_OUGHT)-3);
                    $late_time[]  = $DUTY_TIME_OUGHT;

                    $COUNT_ARRAY[5]++;//迟到
                }
                $IS_ALL=0;
            }

            if($START_OR_END=="2" && $str1==-1)
            {
                $IS_ALL=0;
                if($OUGHT_TO!=0)
                {
                    $DUTY_TIME_OUGHT = substr($DUTY_TIME_OUGHT,0,strlen($DUTY_TIME_OUGHT)-3);
                    $leave_time[]  = $DUTY_TIME_OUGHT;
                    $COUNT_ARRAY[7]++;//早退
                }
            }
        }
        else if($HAS_DUTY==0 && $OUGHT_TO==1)//应该登记，没有登记的
        {
            $times = strtotime(date("Y-m-d H:i:s",time()));
            if($START_OR_END=="1")//上班未登记
            {
                $DUTY_TIME_OUGHT = substr($DUTY_TIME_OUGHT,0,strlen($DUTY_TIME_OUGHT)-3);

                $DUTY_TIMES = $J." ".$DUTY_TIME_OUGHT;
                if(strtotime($DUTY_TIMES)<=$times)
                    $COUNT_ARRAY[6]++;
            }
            if($START_OR_END=="2")//下班未登记
            {
                $DUTY_TIME_OUGHT = substr($DUTY_TIME_OUGHT,0,strlen($DUTY_TIME_OUGHT)-3);
                $DUTY_TIMES = $J." ".$DUTY_TIME_OUGHT;
                if(strtotime($DUTY_TIMES)<=$times)
                    $COUNT_ARRAY[8]++;
            }
            $IS_ALL=0;
        }
        else
            $IS_ALL=0;
    }
    if($IS_ALL==1 && !empty($DUTY_TYPE_ARR))
    {
        $COUNT_ARRAY[3]++;//全勤的
    }
}

$leave       = $COUNT_ARRAY[7];//早退
$late        = $COUNT_ARRAY[5];//迟到
$to_work     = $COUNT_ARRAY[6];//上班未登记
$off_work    = $COUNT_ARRAY[8];//下班未登记
$attendance  = $COUNT_ARRAY[3];//全勤
$rest_count  = $k;//休息天数
$stay_count  = $p;//旷工天数
$attends     = $h;//应出勤天数

if($NO_DUTY_USER!="" && find_id($NO_DUTY_USER,$USER_ID))
{
    $late = $leave = $to_work = $off_work = $stay_count = $attendance = "-";
}
?>

<table class="table table-bordered"  align="center" >
    <tr class="">
        <th nowrap align="center"><?=_("应出勤(天)")?></th>
        <th nowrap align="center"><?=_("全勤(天)")?></th>
        <th nowrap align="center"><?=_("休(天)")?></th>
        <th nowrap align="center"><?=_("旷工(天)")?></th>
        <th nowrap align="center"><?=_("迟到")?></th>
        <th nowrap align="center"><?=_("上班未登记")?></th>
        <th nowrap align="center"><?=_("早退")?></th>
        <th nowrap align="center"><?=_("下班未登记")?></th>
    </tr>
    <tr class="">
        <td nowrap align="center"><?=$attends==""?0:$attends?></td>
        <td nowrap align="center"><?=$attendance==""?0:$attendance?></td>
        <td nowrap align="center"><?=$rest_count==""?0:$rest_count?></td>
        <td nowrap align="center"><?=$stay_count==""?0:$stay_count?></td>
        <td nowrap align="center"><?=$late==""?0:$late?></td>
        <td nowrap align="center"><?=$to_work==""?0:$to_work?></td>
        <td nowrap align="center"><?=$leave==""?0:$leave?></td>
        <td nowrap align="center"><?=$off_work==""?0:$off_work?></td>
    </tr>
    <tr class="">
        <td style="text-align:center;" colspan=8>
            <input type="button"  value="<?=_("查看上下班登记详情")?>" class="btn btn-primary" onClick="location='attendance_user_duty.php?USER_ID=<?=$USER_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'">
        </td>
    </tr>
</table>




<!-- ----------------------------------- 外出记录 ----------------------------- -->

<h5 class="attendance-title"><?=_("外出记录")?></h5>

<?
$query = "SELECT * from ATTEND_OUT where USER_ID='$USER_ID' and to_days(SUBMIT_TIME)>=to_days('$DATE1') and to_days(SUBMIT_TIME)<=to_days('$DATE2') and ALLOW='1' order by SUBMIT_TIME";
$cursor= exequery(TD::conn(),$query, $connstatus);
$OUT_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $OUT_COUNT++;

    $OUT_ID=$ROW["OUT_ID"];
    $OUT_TYPE=$ROW["OUT_TYPE"];
    $SUBMIT_TIME=$ROW["SUBMIT_TIME"];
    $CREATE_DATE=$ROW["CREATE_DATE"];
    $SUBMIT_DATE=substr($SUBMIT_TIME,0,10);
    $OUT_TIME1=$ROW["OUT_TIME1"];
    $OUT_TIME2=$ROW["OUT_TIME2"];
    $ALLOW=$ROW["ALLOW"];
    $STATUS=$ROW["STATUS"];
    $REGISTER_IP=$ROW["REGISTER_IP"];
    $LEADER_ID=$ROW["LEADER_ID"];

    $LEADER_NAME="";
    $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
        $LEADER_NAME=$ROW["USER_NAME"];

    if($ALLOW=="0" && $STATUS=="0")
        $STATUS_DESC=_("待审批");
    else if($ALLOW=="1" && $STATUS=="0")
        $STATUS_DESC="<font color=green>"._("已批准")."</font>";
    else if($ALLOW=="2" && $STATUS=="0")
        $STATUS_DESC="<font color=red>"._("未批准")."</font>";
    else if($ALLOW=="1" && $STATUS=="1")
        $STATUS_DESC=_("已归来");

    if($OUT_COUNT==1)
    {
?>

<table class="table table-bordered" align="center">
    <tr class="">
        <th nowrap align="center"><?=_("申请时间")?></th>
        <th nowrap align="center"><?=_("外出原因")?></th>
        <th nowrap align="center"><?=_("登记")?>IP</th>
        <th nowrap align="center"><?=_("外出日期")?></th>
        <th nowrap align="center"><?=_("外出时间")?></th>
        <th nowrap align="center"><?=_("归来时间")?></th>
        <th nowrap align="center"><?=_("审批人员")?></th>
        <th nowrap align="center"><?=_("状态")?></th>
    </tr>
    <?
    }
    ?>
    <tr class="">
        <td nowrap align="center"><?=$CREATE_DATE?></td>
        <td width="400" align="center"><?=$OUT_TYPE?></td>
        <td nowrap align="center"><?=$REGISTER_IP?></td>
        <td nowrap align="center"><?=$SUBMIT_DATE?></td>
        <td nowrap align="center"><?=$OUT_TIME1?></td>
        <td nowrap align="center"><?=$OUT_TIME2?></td>
        <td nowrap align="center"><?=$LEADER_NAME?></td>
        <td nowrap align="center"><?=$STATUS_DESC?></td>
    </tr>
    <?
    }

    if($OUT_COUNT>0)
    {
    ?>
</table>
<?
}
else
    Message("",_("无外出记录"));
?>




<!-- ----------------------------------- 请假记录 ----------------------------- -->


<h5 class="attendance-title"><?=_("请假记录")?></h5>

<?
$query = "SELECT * from ATTEND_LEAVE where USER_ID='$USER_ID' and ((to_days(LEAVE_DATE1)>=to_days('$DATE1') and to_days(LEAVE_DATE1)<=to_days('$DATE2')) or (to_days(LEAVE_DATE2)>=to_days('$DATE1') and to_days(LEAVE_DATE2)<=to_days('$DATE2')) or (to_days(LEAVE_DATE1)<=to_days('$DATE1') and to_days(LEAVE_DATE2)>=to_days('$DATE2'))) and allow in('1','3')";
$cursor= exequery(TD::conn(),$query, $connstatus);
$LEAVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $LEAVE_COUNT++;

    $LEAVE_ID=$ROW["LEAVE_ID"];
    $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
    $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
    $ANNUAL_LEAVE=$ROW["ANNUAL_LEAVE"];
    $LEAVE_TYPE=$ROW["LEAVE_TYPE"];
    $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
    $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");
    $LEAVE_TYPE=str_replace("<","&lt",$LEAVE_TYPE);
    $LEAVE_TYPE=str_replace(">","&gt",$LEAVE_TYPE);
    $LEAVE_TYPE=stripslashes($LEAVE_TYPE);

    $RECORD_TIME=$ROW["RECORD_TIME"];
    $ALLOW=$ROW["ALLOW"];
    $STATUS=$ROW["STATUS"];
    $REGISTER_IP=$ROW["REGISTER_IP"];
    $LEADER_ID=$ROW["LEADER_ID"];

    $LEADER_NAME="";
    $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
        $LEADER_NAME=$ROW["USER_NAME"];

    if($ALLOW=="0" && $STATUS=="1")
        $ALLOW_DESC=_("待审批");
    else if($ALLOW=="1" && $STATUS=="1")
        $ALLOW_DESC="<font color=green>"._("已批准")."</font>";
    else if($ALLOW=="2" && $STATUS=="1")
        $ALLOW_DESC="<font color=red>"._("未批准")."</font>";
    else if($ALLOW=="3" && $STATUS=="1")
        $ALLOW_DESC=_("申请销假");
    else if($ALLOW=="3" && $STATUS=="2")
        $ALLOW_DESC=_("已销假");

    if($LEAVE_COUNT==1)
    {
?>

<table class="table table-bordered" align="center">
    <tr class="">
        <th nowrap align="center"><?=_("请假原因")?></th>
        <th nowrap align="center"><?=_("请假类型")?></th>
        <th nowrap align="center"><?=_("申请时间")?></th>
        <th nowrap align="center"><?=_("占年休假")?></th>
        <th nowrap align="center"><?=_("登记")?>IP</th>
        <th nowrap align="center"><?=_("开始日期")?></th>
        <th nowrap align="center"><?=_("结束日期")?></th>
        <th nowrap align="center"><?=_("审批人员")?></th>
        <th nowrap align="center"><?=_("状态")?></th>
    </tr>
    <?
    }
    ?>
    <tr class="">
        <td width="400" align="center"><?=$LEAVE_TYPE?></td>
        <td nowrap align="center"><?=$LEAVE_TYPE2?></td>
        <td nowrap align="center"><?=$RECORD_TIME?></td>
        <td align="center"><?=$ANNUAL_LEAVE?><?=_("天")?></td>
        <td nowrap align="center"><?=$REGISTER_IP?></td>
        <td nowrap align="center"><?=$LEAVE_DATE1?></td>
        <td nowrap align="center"><?=$LEAVE_DATE2?></td>
        <td nowrap align="center"><?=$LEADER_NAME?></td>
        <td nowrap align="center"><?=$ALLOW_DESC?></td>
    </tr>
    <?
    }

    if($LEAVE_COUNT>0)
    {
    ?>

</table>
<?
}
else
    Message("",_("无请假记录"));
?>





<!-- ----------------------------------- 出差记录 ---------------------------- - -->


<h5 class="attendance-title"><?=_("出差记录")?></h5>
<?
$query = "SELECT * from ATTEND_EVECTION where USER_ID='$USER_ID' and ((to_days(EVECTION_DATE1)>=to_days('$DATE1') and to_days(EVECTION_DATE1)<=to_days('$DATE2')) or (to_days(EVECTION_DATE2)>=to_days('$DATE1') and to_days(EVECTION_DATE2)<=to_days('$DATE2')) or (to_days(EVECTION_DATE1)<=to_days('$DATE1') and to_days(EVECTION_DATE2)>=to_days('$DATE2'))) and ALLOW='1'";
$cursor= exequery(TD::conn(),$query, $connstatus);
$EVECTION_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $EVECTION_COUNT++;

    $REGISTER_IP=$ROW["REGISTER_IP"];
    $EVECTION_ID=$ROW["EVECTION_ID"];
    $EVECTION_DATE1=$ROW["EVECTION_DATE1"];
    $EVECTION_DATE1=strtok($EVECTION_DATE1," ");
    $EVECTION_DATE2=$ROW["EVECTION_DATE2"];
    $EVECTION_DATE2=strtok($EVECTION_DATE2," ");
    $EVECTION_DEST=$ROW["EVECTION_DEST"];
    $LEADER_ID=$ROW["LEADER_ID"];
    $STATUS=$ROW["STATUS"];
    $ALLOW=$ROW["ALLOW"];
    $REASON=$ROW["REASON"];
    $RECORD_TIME=$ROW["RECORD_TIME"]=="0000-00-00 00:00:00" ? $EVECTION_DATE1 : $ROW["RECORD_TIME"];

    $LEADER_NAME="";
    $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
        $LEADER_NAME=$ROW["USER_NAME"];

    if($ALLOW=="0" && $STATUS=="1")
        $ALLOW_DESC=_("待审批");
    else if($ALLOW=="1" && $STATUS=="1")
        $ALLOW_DESC="<font color=green>"._("已批准")."</font>";
    else if($ALLOW=="2" && $STATUS=="1")
        $ALLOW_DESC="<font color=red>"._("未批准")."</font>";
    else if($ALLOW=="1" && $STATUS=="2")
        $ALLOW_DESC=_("已归来");

    if($EVECTION_COUNT==1)
    {
?>

<table class="table table-bordered" align="center">
    <tr class="">
        <th nowrap align="center"><?=_("申请时间")?></th>
        <th nowrap align="center"><?=_("出差地点")?></th>
        <th nowrap align="center"><?=_("出差原因")?></th>
        <th nowrap align="center"><?=_("登记")?>IP</th>
        <th nowrap align="center"><?=_("开始日期")?></th>
        <th nowrap align="center"><?=_("结束日期")?></th>
        <th nowrap align="center"><?=_("审批人员")?></th>
        <th nowrap align="center"><?=_("状态")?></th>
    </tr>
    <?
    }
    ?>
    <tr class="">
        <td nowrap align="center"><?=$RECORD_TIME?></td>
        <td nowrap align="center"><?=$EVECTION_DEST?></td>
        <td width="400" align="center"><?=$REASON?></td>
        <td nowrap align="center"><?=$REGISTER_IP?></td>
        <td nowrap align="center"><?=$EVECTION_DATE1?></td>
        <td nowrap align="center"><?=$EVECTION_DATE2?></td>
        <td nowrap align="center"><?=$LEADER_NAME?></td>
        <td nowrap align="center"><?=$ALLOW_DESC?></td>
    </tr>
    <?
    }

    if($EVECTION_COUNT>0)
    {
    ?>

</table>
<?
}
else
    Message("",_("无出差记录"));
?>





<!-- ----------------------------------- 加班记录 ----------------------------- -->

<h5 class="attendance-title"><?=_("加班记录")?></h5>

<?
$query = "SELECT * from ATTENDANCE_OVERTIME where USER_ID='$USER_ID' and ((to_days(START_TIME)>=to_days('$DATE1') and to_days(START_TIME)<=to_days('$DATE2')) or (to_days(END_TIME)>=to_days('$DATE1') and to_days(END_TIME)<=to_days('$DATE2')) or (to_days(START_TIME)<=to_days('$DATE1') and to_days(END_TIME)>=to_days('$DATE2')))and allow in('1','3') order by RECORD_TIME desc";
$cursor= exequery(TD::conn(),$query, $connstatus);
$OVERTIME_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $OVERTIME_COUNT++;

    $OVERTIME_ID=$ROW["OVERTIME_ID"];
    $USER_ID=$ROW["USER_ID"];
    $APPROVE_ID=$ROW["APPROVE_ID"];
    $RECORD_TIME=$ROW["RECORD_TIME"];
    $START_TIME=$ROW["START_TIME"];
    $END_TIME=$ROW["END_TIME"];
    $OVERTIME_HOURS=$ROW["OVERTIME_HOURS"];
    $OVERTIME_CONTENT=$ROW["OVERTIME_CONTENT"];
    $CONFIRM_TIME=$ROW["CONFIRM_TIME"];
    $CONFIRM_VIEW=$ROW["CONFIRM_VIEW"];
    $ALLOW=$ROW["ALLOW"];
    $STATUS=$ROW["STATUS"];
    $REGISTER_IP=$ROW["REGISTER_IP"];
    $REASON=$ROW["REASON"];

    $APPROVE_NAME="";
    $query = "SELECT * from USER where USER_ID='$APPROVE_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
        $APPROVE_NAME=$ROW["USER_NAME"];

    if($ALLOW=="0" && $STATUS=="0")
        $ALLOW_DESC=_("待审批");
    else if($ALLOW=="1" && $STATUS=="0")
        $ALLOW_DESC="<font color=green>"._("已批准")."</font>";
    else if($ALLOW=="2" && $STATUS=="0")
        $ALLOW_DESC= "<font color=\"red\">"._("未批准")."</font>";
    else if($ALLOW=="3" && $STATUS=="0")
        $ALLOW_DESC=_("申请确认");
    else if($ALLOW=="3" && $STATUS=="1")
        $ALLOW_DESC=_("已确认");

    if($OVERTIME_COUNT==1)
    {
?>
<table class="table table-bordered" align="center">
    <tr class="">
        <th nowrap align="center"><?=_("申请时间")?></th>
        <th nowrap align="center"><?=_("加班内容")?></th>
        <th nowrap align="center"><?=_("登记")?>IP</th>
        <th nowrap align="center"><?=_("加班开始时间")?></th>
        <th nowrap align="center"><?=_("加班结束时间")?></th>
        <th nowrap align="center"><?=_("时长")?></th>
        <th nowrap align="center"><?=_("审批人员")?></th>
        <th nowrap align="center"><?=_("状态")?></th>
    </tr>
    <?
    }
    ?>
    <tr class="">
        <td nowrap align="center"><?=$RECORD_TIME?></td>
        <td width="400" align="center">
            <?
            echo $OVERTIME_CONTENT;
            if($CONFIRM_VIEW!="")
            {
                echo "<br>";
                echo _("<font color=blue>确认意见：$CONFIRM_VIEW</font>");
            }
            ?>
        </td>
        <td nowrap align="center"><?=$REGISTER_IP?></td>
        <td nowrap align="center"><?=$START_TIME?></td>
        <td nowrap align="center"><?=$END_TIME?></td>
        <td nowrap align="center"><?=$OVERTIME_HOURS?></td>
        <td nowrap align="center"><?=$APPROVE_NAME?></td>
        <td nowrap align="center"><?=$ALLOW_DESC?></td>
    </tr>
    <?
    }

    if($OVERTIME_COUNT>0)
    {
    ?>

</table>
<?
}
else
    Message("",_("无加班记录"));
?>

<br>

<div align="center">
  <input type="button"  value="<?=_("返回")?>" class="btn" onClick="location='attendance_list.php?USER_ID=<?=$USER_ID?>';">
</div>
</body>
</html>
