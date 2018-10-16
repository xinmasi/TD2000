<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("考勤情况查询");
include_once("inc/header.inc.php");
?>


<style>
    .AutoNewline
    {
        word-break: break-all;/*必须*/
    }
</style>


<body class="bodycolor">

<?
//----------- 合法性校验 ---------
if($DATE1!="")
{
    $TIME_OK=is_date($DATE1);

    if(!$TIME_OK)
    { Message(_("错误"),_("起始日期格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

if($DATE2!="")
{
    $TIME_OK=is_date($DATE2);

    if(!$TIME_OK)
    { Message(_("错误"),_("截止日期格式不对，应形如 1999-1-2"));
        Button_Back();
        exit;
    }
}

if(compare_date($DATE1,$DATE2)==1)
{ Message(_("错误"),_("查询的起始日期不能晚于截止日期"));
    Button_Back();
    exit;
}

$CUR_DATE=date("Y-m-d",time());

$query = "SELECT to_days('$DATE2')-to_days('$DATE1') from ATTEND_CONFIG";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $DAY_TOTAL=$ROW[0]+1;

$MSG = sprintf(_("共%d天"), $DAY_TOTAL);
?>

<!------------------------------------- 上下班 ------------------------------->
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("上下班统计")?>
                <?=_("从")?> <?=format_date($DATE1)?> <?=_("至")?> <?=format_date($DATE2)?> <?=$MSG?>
    </span>
            &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?=_("导出")?>Excel" class="BigButton" onClick="location='export_duty.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE=<?=$DUTY_TYPE?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'" title="<?=_("导出下班统计信息")?>" name="button">
            &nbsp;&nbsp;<input type="button" value="<?=_("所有人详细记录")?>" class="BigButton" onClick="location='all_users_duty.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE1=<?=$DUTY_TYPE?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'" title="<?=_("所有人详细记录")?>" name="button">
            &nbsp;&nbsp;<input type="button" value="<?=_("导出详细记录")?>" class="BigButton" onClick="location='export_all_users.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE1=<?=$DUTY_TYPE?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'" title="<?=_("导出详细记录")?>" name="button">
        </td>
    </tr>
</table>

<table class="TableList" width="100">
    <tr class="TableHeader">
        <td nowrap align="center"><?=_("部门")?></td>
        <td nowrap align="center"><?=_("姓名")?></td>
        <td nowrap align="center"><?=_("全勤(天)")?></td>
        <td nowrap align="center"><?=_("时长")?></td>
        <td nowrap align="center"><?=_("迟到")?></td>
        <td nowrap align="center"><?=_("上班未登记")?></td>
        <td nowrap align="center"><?=_("早退")?></td>
        <td nowrap align="center"><?=_("下班未登记")?></td>
        <td nowrap align="center"><?=_("加班上班登记")?></td>
        <td nowrap align="center"><?=_("加班下班登记")?></td>
        <td nowrap align="center"><?=_("操作")?></td>
    </tr>
    <?
    $query = "SELECT * from SYS_PARA where PARA_NAME='NO_DUTY_USER'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $NO_DUTY_USER=$ROW["PARA_VALUE"];

    //---- 取所有子部门 ------
    if($DEPARTMENT1!="ALL_DEPT")
        $DET_LIST=substr(GetChildDeptId($DEPARTMENT1),0,-1);

    //---- 取规定上下班时间 -----
    $query1 = "SELECT * from ATTEND_CONFIG";
    $cursor1= exequery(TD::conn(),$query1);
    while($ROW=mysql_fetch_array($cursor1))
    {
        $DUTY_TYPE1=$ROW["DUTY_TYPE"];
        $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_NAME"]=$ROW["DUTY_NAME"];
        $ATTEND_CONFIG[$DUTY_TYPE1]["GENERAL"]=$ROW["GENERAL"];

        $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_TIME1"]=$ROW["DUTY_TIME1"];
        $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_TIME2"]=$ROW["DUTY_TIME2"];
        $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_TIME3"]=$ROW["DUTY_TIME3"];
        $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_TIME4"]=$ROW["DUTY_TIME4"];
        $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_TIME5"]=$ROW["DUTY_TIME5"];
        $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_TIME6"]=$ROW["DUTY_TIME6"];

        $REG_COUNT[$DUTY_TYPE1] = 0;
        for($I=1;$I<=6;$I++)
        {
            $cn = $I%2==0?"2":"1";
            $DUTY_TIME_I=$ROW["DUTY_TIME".$I];
            $DUTY_TYPE_I=$cn;
            if($DUTY_TIME_I=="")
                continue;

            $REG_COUNT[$DUTY_TYPE1]++;

            if($DUTY_TYPE_I==1)
                $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_ON_TIMES"]++;
            else
                $ATTEND_CONFIG[$DUTY_TYPE1]["DUTY_OFF_TIMES"]++;
        }
    }
    //---- 查询用户的上下班时间 -----
    $query = "SELECT * from USER,USER_PRIV,DEPARTMENT,USER_EXT where USER.UID=USER_EXT.UID and (USER.NOT_LOGIN = 0 or USER.NOT_MOBILE_LOGIN = 0) and not find_in_set(USER.USER_ID,'$NO_DUTY_USER') and DEPARTMENT.DEPT_ID=USER.DEPT_ID ";
    if($DEPARTMENT1!="ALL_DEPT")
        $query.=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";

    if($DUTY_TYPE!="ALL_TYPE")
        $query.=" and DUTY_TYPE='$DUTY_TYPE' ";

    $query.= " and  USER.USER_PRIV=USER_PRIV.USER_PRIV order by DEPT_NO,PRIV_NO,USER_NO,USER_NAME";

    $cursor= exequery(TD::conn(),$query, $connstatus);
    $LINE_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $ALL_HOURS = 0;
        $ALL_MINITES = 0;

        $USER_ID=$ROW["USER_ID"];
        $DEPT_ID=$ROW["DEPT_ID"];
        $USER_NAME=$ROW["USER_NAME"];
        $DUTY_TYPE_TMP = $DUTY_TYPE=$ROW["DUTY_TYPE"];
        $USER_DEPT_NAME=$ROW["DEPT_NAME"];

        $DUTY_NAME=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_NAME"];
        $GENERAL=$ATTEND_CONFIG[$DUTY_TYPE]["GENERAL"];
        $DUTY_ON_TIMES=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_ON_TIMES"];
        $DUTY_OFF_TIMES=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_OFF_TIMES"];

        $DUTY_TIME1=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_TIME1"];
        $DUTY_TIME2=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_TIME2"];
        $DUTY_TIME3=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_TIME3"];
        $DUTY_TIME4=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_TIME4"];
        $DUTY_TIME5=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_TIME5"];
        $DUTY_TIME6=$ATTEND_CONFIG[$DUTY_TYPE]["DUTY_TIME6"];

        $LINE_COUNT++;

        $PERFECT_COUNT="";
        $EARLY_COUNT="";
        $LATE_COUNT="";
        $DUTY_ON_COUNT="";
        $DUTY_OFF_COUNT="";
        $DUTY_ON_TOTAL="";
        $DUTY_OFF_TOTAL="";
        $OVER_ON_COUNT="";
        $OVER_OFF_COUNT="";

        for($J=$DATE1;$J<=$DATE2;$J=date("Y-m-d",strtotime($J)+24*3600))
        {
            $WEEK=date("w",strtotime($J));
            $HOLIDAY=0;
            $HOLIDAY1=0;

            if(find_id($GENERAL,$WEEK))
                $HOLIDAY=1;
            if($HOLIDAY==0)
            {
                $query="select count(*) from ATTEND_HOLIDAY where BEGIN_DATE <='$J' and END_DATE>='$J'";
                $cursor1= exequery(TD::conn(),$query);
                if($ROW=mysql_fetch_array($cursor1))
                {
                    $HOLIDAY=$ROW[0];
                    if($HOLIDAY > 0)
                        $HOLIDAY1=1;
                }
            }
            if($HOLIDAY==0)
            {
                $query="select count(*) from ATTEND_EVECTION where USER_ID='$USER_ID' and ALLOW='1' and to_days(EVECTION_DATE1)<=to_days('$J') and to_days(EVECTION_DATE2)>=to_days('$J')";
                $cursor1= exequery(TD::conn(),$query, $connstatus);
                if($ROW=mysql_fetch_array($cursor1))
                {
                    $HOLIDAY=$ROW[0];
                    if($HOLIDAY > 0)
                        $HOLIDAY1=1;
                }
            }
            if($HOLIDAY==0)
            {
                $query="select count(*) from ATTEND_LEAVE where USER_ID='$USER_ID' and ALLOW='1' and left(LEAVE_DATE1,10)<='$J' and left(LEAVE_DATE2,10)>='$J'";
                $cursor1= exequery(TD::conn(),$query, $connstatus);
                if($ROW=mysql_fetch_array($cursor1))
                {
                    $HOLIDAY=$ROW[0];
                    if($HOLIDAY > 0)
                        $HOLIDAY1=1;
                }
            }

            if($HOLIDAY==0)
            {
                $DUTY_ON_TOTAL+=$DUTY_ON_TIMES;
                $DUTY_OFF_TOTAL+=$DUTY_OFF_TIMES;
            }

            $PERFECT_FLAG=0;
            $query1 = "SELECT * from ATTEND_DUTY where USER_ID='$USER_ID' and to_days(REGISTER_TIME)=to_days('$J') order by REGISTER_TIME";
            $cursor1= exequery(TD::conn(),$query1, $connstatus);
            $ONE_DAY_COUNT = 0;
            while($ROW=mysql_fetch_array($cursor1))
            {
                $ONE_DAY_COUNT++;

                $ONE_DAY_REG[$ONE_DAY_COUNT] = $ROW["REGISTER_TIME"];
                if($ONE_DAY_COUNT == $REG_COUNT[$DUTY_TYPE_TMP] && $REG_COUNT[$DUTY_TYPE_TMP]%2==0 && $ONE_DAY_COUNT%2==0 && $REG_COUNT[$DUTY_TYPE_TMP] > 1 && $ONE_DAY_COUNT > 1)
                {
                    $cha1 = strtotime($ONE_DAY_REG[$REG_COUNT[$DUTY_TYPE_TMP]]) - strtotime($ONE_DAY_REG[$REG_COUNT[$DUTY_TYPE_TMP] - 1]);
                    $cha2=0;$cha3=0;
                    if($REG_COUNT[$DUTY_TYPE_TMP] - 2 > 1)
                        $cha2 = strtotime($ONE_DAY_REG[$REG_COUNT[$DUTY_TYPE_TMP] - 2]) - strtotime($ONE_DAY_REG[$REG_COUNT[$DUTY_TYPE_TMP] - 3]);
                    if($REG_COUNT[$DUTY_TYPE_TMP] - 4 > 1)
                        $cha3 = strtotime($ONE_DAY_REG[$REG_COUNT[$DUTY_TYPE_TMP] - 4]) - strtotime($ONE_DAY_REG[$REG_COUNT[$DUTY_TYPE_TMP] - 5]);

                    $ALL_MINITES += $cha1 + $cha2 + $cha3;

                }

                $REGISTER_TYPE=$ROW["REGISTER_TYPE"];
                $REGISTER_TIME=$ROW["REGISTER_TIME"];
                $SOME_DATE=strtok($REGISTER_TIME," ");
                $REGISTER_TIME=strtok(" ");

                $STR="DUTY_TIME".$REGISTER_TYPE;
                $DUTY_TIME=$$STR;

                $cn = $REGISTER_TYPE%2==0?"2":"1";
                $DUTY_TYPE=$cn;

                if($DUTY_TIME=="")
                    continue;

                if($DUTY_TYPE=="1")
                {
                    if(compare_time($REGISTER_TIME,$DUTY_TIME)< 1)
                        $PERFECT_FLAG++;

                    if($HOLIDAY>0 && $HOLIDAY1!=1)
                    {
                        $OVER_ON_COUNT++;
                        continue;
                    }

                    $DUTY_ON_COUNT++;
                    if(compare_time($REGISTER_TIME,$DUTY_TIME)==1)
                        $LATE_COUNT++;
                }

                if($DUTY_TYPE=="2")
                {
                    if(compare_time($REGISTER_TIME,$DUTY_TIME)>-1)
                        $PERFECT_FLAG++;

                    if($HOLIDAY>0 && $HOLIDAY1!=1)
                    {
                        $OVER_OFF_COUNT++;
                        continue;
                    }

                    $DUTY_OFF_COUNT++;
                    if(compare_time($REGISTER_TIME,$DUTY_TIME)==-1)
                        $EARLY_COUNT++;
                }
            }

            if($PERFECT_FLAG>=$DUTY_ON_TIMES+$DUTY_OFF_TIMES)
                $PERFECT_COUNT++;
        }

        if($LINE_COUNT%2==1)
            $TableLine="TableLine1";
        else
            $TableLine="TableLine2";

        $ALL_HOURS = floor($ALL_MINITES / 3600);
        $HOUR1 = $ALL_MINITES % 3600;
        $MINITE = floor($HOUR1 / 60);

        $ALL_HOURS_MINITES = 0;
        if($ALL_HOURS!=0 || $MINITE!=0)
            $ALL_HOURS_MINITES = $ALL_HOURS._("时").$MINITE._("分");

        ?>
        <tr class="<?=$TableLine?>">
            <td nowrap align="center"><?=$USER_DEPT_NAME?></td>
            <td nowrap align="center"><?=$USER_NAME?></td>
            <td nowrap align="center"><?=$PERFECT_COUNT?></td>
            <td nowrap align="center"><?=$ALL_HOURS_MINITES?></td>
            <td nowrap align="center"><?=$LATE_COUNT?></td>
            <td nowrap align="center"><? if($DUTY_ON_TOTAL-$DUTY_ON_COUNT < 0) echo "0";else echo $DUTY_ON_TOTAL-$DUTY_ON_COUNT;?></td>
            <td nowrap align="center"><?=$EARLY_COUNT?></td>
            <td nowrap align="center"><? if($DUTY_OFF_TOTAL-$DUTY_OFF_COUNT < 0) echo "0";else echo $DUTY_OFF_TOTAL-$DUTY_OFF_COUNT;?></td>
            <td nowrap align="center"><?=$OVER_ON_COUNT?></td>
            <td nowrap align="center"><?=$OVER_OFF_COUNT?></td>
            <td nowrap align="center"><a href="../user_manage/user_duty.php?USER_ID=<?=$USER_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>"><?=_("详细记录")?></a>&nbsp;
                <a href="../user_manage/user_duty_export.php?USER_ID=<?=$USER_ID?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>"><?=_("导出个人明细")?></a>&nbsp;</td>
        </tr>
        <?
    }
    ?>
</table>

<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
    <tr>
        <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
    </tr>
</table>
<!------------------------------------- 外出记录 ------------------------------->


<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("外出记录")?></span>
            &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?=_("导出")?>" class="BigButton" onClick="location='export_out.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE=<?=$DUTY_TYPE?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'" title="<?=_("导出外出记录")?>" name="button">
        </td>
    </tr>
</table>
<table class="TableList" width="100%">
    <tr class="TableHeader">
        <td width="5%" align="center" nowrap><?=_("部门")?></td>
        <td width="5%" align="center" nowrap><?=_("姓名")?></td>
        <td width="8%" align="center" nowrap><?=_("申请时间")?></td>
        <td width="33%" align="center" class="AutoNewline"><?=_("外出原因")?></td>
        <td width="7%" align="center" nowrap><?=_("登记")?>IP</td>
        <td width="6%" align="center" nowrap><?=_("外出日期")?></td>
        <td width="7%" align="center" nowrap><?=_("外出时间")?></td>
        <td width="6%" align="center" nowrap><?=_("归来时间")?></td>
        <td width="3%" align="center" nowrap><?=_("时长")?></td>
        <td width="6%" align="center" nowrap><?=_("审批人员")?></td>
        <td width="5%" align="center" nowrap><?=_("状态")?></td>
        <td width="9%" align="center" nowrap><?=_("操作")?></td>
    </tr>
    <?
    if($DEPARTMENT1!="ALL_DEPT")
        $WHERE_STR=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";

    $query = "SELECT * from ATTEND_OUT,USER,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID ".$WHERE_STR." and ATTEND_OUT.USER_ID=USER.USER_ID and to_days(SUBMIT_TIME)>=to_days('$DATE1') and to_days(SUBMIT_TIME)<=to_days('$DATE2') order by DEPT_NO,USER_NO,USER_NAME";
    $cursor= exequery(TD::conn(),$query, $connstatus);
    $OUT_COUNT=0;
    while($ROW=mysql_fetch_array($cursor))
    {
        $USER_ID=$ROW["USER_ID"];
        $USER_NAME=$ROW["USER_NAME"];
        $OUT_TYPE=$ROW["OUT_TYPE"];
        $SUBMIT_TIME=$ROW["SUBMIT_TIME"];
        $SUBMIT_DATE=substr($SUBMIT_TIME,0,10);
        $OUT_TIME1=$ROW["OUT_TIME1"];
        $OUT_TIME2=$ROW["OUT_TIME2"];
        $CREATE_DATE=$ROW["CREATE_DATE"];
        $ALLOW=$ROW["ALLOW"];
        $STATUS=$ROW["STATUS"];
        $DEPT_ID=$ROW["DEPT_ID"];
        $REGISTER_IP=$ROW["REGISTER_IP"];
        $LEADER_ID=$ROW["LEADER_ID"];

        $LEADER_NAME="";
        $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
        $cursor1= exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor1))
            $LEADER_NAME=$ROW["USER_NAME"];

        if(!is_dept_priv($DEPT_ID))
            continue;

        $OUT_COUNT++;

        if($STATUS=="0")
            $STATUS_DESC=_("外出");
        else if($STATUS=="1")
            $STATUS_DESC=_("已归来");
        if($ALLOW=='0')
            $STATUS_DESC=_("待批");
        if($ALLOW=='2')
            $STATUS_DESC=_("不批准");

        $DEPT_ID=intval($DEPT_ID);
        $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW=mysql_fetch_array($cursor1))
            $USER_DEPT_NAME=$ROW["DEPT_NAME"];

        if($OUT_COUNT>=1)
        {
            $ALL_HOURS3 = floor((strtotime($OUT_TIME2)-strtotime($OUT_TIME1)) / 3600);
            $HOUR13 = (strtotime($OUT_TIME2)-strtotime($OUT_TIME1)) % 3600;
            $MINITE3 = floor($HOUR13 / 60);
            ?>
            <tr class="TableData">
                <td nowrap align="center"><?=$USER_DEPT_NAME?></td>
                <td nowrap align="center"><?=$USER_NAME?></td>
                <td nowrap align="center"><?=$CREATE_DATE?></td>
                <td class="AutoNewline" width="400"><?=$OUT_TYPE?></td>
                <td nowrap align="center"><?=$REGISTER_IP?></td>
                <td nowrap align="center"><?=$SUBMIT_DATE?></td>
                <td nowrap align="center"><?=$OUT_TIME1?></td>
                <td nowrap align="center"><?=$OUT_TIME2?></td>
                <td nowrap align="center"><?=$ALL_HOURS3._("小时").$MINITE3._("分")?></td>
                <td nowrap align="center"><?=$LEADER_NAME?></td>
                <td nowrap align="center"><?=$STATUS_DESC?></td>
                <td nowrap align="center">
                    <?
                    if($_SESSION["LOGIN_USER_PRIV"]==1)
                    {
                        ?>
                        <a href="delete_out.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE=<?=$DUTY_TYPE?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&USER_ID=<?=$USER_ID?>&SUBMIT_TIME=<?=urlencode($SUBMIT_TIME)?>"><?=_("删除")?></a>
                        <?
                    }
                    ?>
                </td>
            </tr>
            <?
        }
    }
    if($OUT_COUNT<=0)
    {
        message("",_("无外出记录"));
    }
    ?>
</table>
<br>

<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
    <tr>
        <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
    </tr>
</table>
<!------------------------------------- 请假记录 ------------------------------->


<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("请假记录")?></span>
            &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?=_("导出")?>" class="BigButton" onClick="location='export_leave.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE=<?=$DUTY_TYPE?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'" title="<?=_("导出请假记录")?>" name="button">
        </td>
    </tr>
</table>

<?
$query = "SELECT * from ATTEND_LEAVE,USER,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID  ".$WHERE_STR." and ATTEND_LEAVE.USER_ID=USER.USER_ID and ((to_days(LEAVE_DATE1)>=to_days('$DATE1') and to_days(LEAVE_DATE1)<=to_days('$DATE2')) or (to_days(LEAVE_DATE2)>=to_days('$DATE1') and to_days(LEAVE_DATE2)<=to_days('$DATE2')) or (to_days(LEAVE_DATE1)<=to_days('$DATE1') and to_days(LEAVE_DATE2)>=to_days('$DATE2'))) and allow in('1','3') order by DEPT_NO,USER_NO,USER_NAME";
$cursor= exequery(TD::conn(),$query, $connstatus);
$LEAVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $USER_ID=$ROW["USER_ID"];
    $DEPT_ID=$ROW["DEPT_ID"];
    $USER_NAME=$ROW["USER_NAME"];
    $LEAVE_ID=$ROW["LEAVE_ID"];
    $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
    $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
    $REGISTER_IP=$ROW["REGISTER_IP"];
    $ANNUAL_LEAVE=$ROW["ANNUAL_LEAVE"];
    $RECORD_TIME=$ROW["RECORD_TIME"];
    $LEAVE_TYPE=$ROW["LEAVE_TYPE"];
    $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
    $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");
    $LEADER_ID=$ROW["LEADER_ID"];
    $STATUS=$ROW["STATUS"];

    $LEADER_NAME="";
    $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
        $LEADER_NAME=$ROW["USER_NAME"];

    if(!is_dept_priv($DEPT_ID))
        continue;

    $LEAVE_COUNT++;

    if($STATUS==1)
        $STATUS=_("现行");
    else
        $STATUS=_("已销假");
    $DEPT_ID=intval($DEPT_ID);
    $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
        $USER_DEPT_NAME=$ROW["DEPT_NAME"];

    if($LEAVE_COUNT==1)
    {
    ?>

<table class="TableList"  width="95%">

    <?
    }
    $MSG2 = sprintf(_("%d天"), $ANNUAL_LEAVE);
    ?>
    <tr class="TableData">
        <td nowrap align="center"><?=$USER_DEPT_NAME?></td>
        <td nowrap align="center"><?=$USER_NAME?></td>
        <td class="AutoNewline" width="400" align="center"><?=$LEAVE_TYPE?></td>
        <td nowrap align="center"><?=$LEAVE_TYPE2?></td>
        <td nowrap align="center"><?=$RECORD_TIME?></td>
        <td align="center"><?=$MSG2?></td>
        <td nowrap align="center"><?=$REGISTER_IP?></td>
        <td nowrap align="center"><?=$LEAVE_DATE1?></td>
        <td nowrap align="center"><?=$LEAVE_DATE2?></td>
        <td nowrap align="center"><?=$LEADER_NAME?></td>
        <td nowrap align="center"><?=$STATUS?></td>
        <td nowrap align="center">
            <?
            if($_SESSION["LOGIN_USER_PRIV"]==1)
            {
                ?>
                <a href="delete_leave.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE=<?=$DUTY_TYPE?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&LEAVE_ID=<?=$LEAVE_ID?>"><?=_("删除")?></a>
                <?
            }
            ?>
        </td>
    </tr>
    <?
    }

    if($LEAVE_COUNT>0)
    {
    ?>
    <thead class="TableHeader">
    <td nowrap align="center"><?=_("部门")?></td>
    <td nowrap align="center"><?=_("姓名")?></td>
    <td class="AutoNewline" align="center"><?=_("请假原因")?></td>
    <td nowrap align="center"><?=_("请假类型")?></td>
    <td nowrap align="center"><?=_("申请时间")?></td>
    <td nowrap align="center"><?=_("占年休假")?></td>
    <td nowrap align="center"><?=_("登记IP")?></td>
    <td nowrap align="center"><?=_("开始日期")?></td>
    <td nowrap align="center"><?=_("结束日期")?></td>
    <td nowrap align="center"><?=_("审批人员")?></td>
    <td nowrap align="center"><?=_("状态")?></td>
    <td nowrap align="center"><?=_("操作")?></td>
    </thead>
</table>
<?
}
else
    message("",_("无请假记录"));
?>

<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
    <tr>
        <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
    </tr>
</table>


<!------------------------------------- 出差记录 ------------------------------->


<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("出差记录")?></span>
            &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?=_("导出")?>" class="BigButton" onClick="location='export_evection.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE=<?=$DUTY_TYPE?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>'" title="<?=_("导出出差记录")?>" name="button">
        </td>
    </tr>
</table>

<?
$query = "SELECT * from ATTEND_EVECTION,USER,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID  ".$WHERE_STR." and ATTEND_EVECTION.USER_ID=USER.USER_ID and ((to_days(EVECTION_DATE1)>=to_days('$DATE1') and to_days(EVECTION_DATE1)<=to_days('$DATE2')) or (to_days(EVECTION_DATE2)>=to_days('$DATE1') and to_days(EVECTION_DATE2)<=to_days('$DATE2')) or (to_days(EVECTION_DATE1)<=to_days('$DATE1') and to_days(EVECTION_DATE2)>=to_days('$DATE2'))) and ALLOW='1' order by DEPT_NO,USER_NO,USER_NAME";
$cursor= exequery(TD::conn(),$query, $connstatus);
$EVECTION_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
$USER_ID=$ROW["USER_ID"];
$DEPT_ID=$ROW["DEPT_ID"];
$USER_NAME=$ROW["USER_NAME"];
$EVECTION_ID=$ROW["EVECTION_ID"];
$EVECTION_DATE1=$ROW["EVECTION_DATE1"];
$EVECTION_DATE1=strtok($EVECTION_DATE1," ");
$EVECTION_DATE2=$ROW["EVECTION_DATE2"];
$EVECTION_DATE2=strtok($EVECTION_DATE2," ");
$EVECTION_DEST=$ROW["EVECTION_DEST"];
$STATUS=$ROW["STATUS"];
$REGISTER_IP=$ROW["REGISTER_IP"];
$LEADER_ID=$ROW["LEADER_ID"];

$LEADER_NAME="";
$query = "SELECT * from USER where USER_ID='$LEADER_ID'";
$cursor1= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor1))
    $LEADER_NAME=$ROW["USER_NAME"];

if(!is_dept_priv($DEPT_ID))
    continue;

$EVECTION_COUNT++;

if($STATUS=="1")
    $STATUS=_("在外");
else
    $STATUS=_("归来");
$DEPT_ID=intval($DEPT_ID);
$query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW=mysql_fetch_array($cursor1))
    $USER_DEPT_NAME=$ROW["DEPT_NAME"];


if($EVECTION_COUNT==1)
{
?>

<table class="TableList" width="95%">

    <?
    }
    ?>
    <tr class="TableData">
        <td nowrap align="center"><?=$USER_DEPT_NAME?></td>
        <td nowrap align="center"><?=$USER_NAME?></td>
        <td class="AutoNewline" width="400" align="center"><?=$EVECTION_DEST?></td>
        <td nowrap align="center"><?=$REGISTER_IP?></td>
        <td nowrap align="center"><?=$EVECTION_DATE1?></td>
        <td nowrap align="center"><?=$EVECTION_DATE2?></td>
        <td nowrap align="center"><?=$LEADER_NAME?></td>
        <td nowrap align="center"><?=$STATUS?></td>
        <td nowrap align="center">
            <?
            if($_SESSION["LOGIN_USER_PRIV"]==1)
            {
                ?>
                <a href="delete_evection.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE=<?=$DUTY_TYPE?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&EVECTION_ID=<?=$EVECTION_ID?>"><?=_("删除")?></a>
                <?
            }
            ?>
        </td>
    </tr>
    <?
    }

    if($EVECTION_COUNT>0)
    {
    ?>
    <thead class="TableHeader">
    <td nowrap align="center"><?=_("部门")?></td>
    <td nowrap align="center"><?=_("姓名")?></td>
    <td nowrap align="center"><?=_("出差地点")?></td>
    <td nowrap align="center"><?=_("登记")?>IP</td>
    <td nowrap align="center"><?=_("开始日期")?></td>
    <td nowrap align="center"><?=_("结束日期")?></td>
    <td nowrap align="center"><?=_("审批人员")?></td>
    <td nowrap align="center"><?=_("状态")?></td>
    <td nowrap align="center"><?=_("操作")?></td>
    </thead>
</table>
<?
}
else
    message("",_("无出差记录"));
?>

<br>

<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
    <tr>
        <td background="<?=MYOA_STATIC_SERVER?>/static/images/dian1.gif" width="100%"></td>
    </tr>
</table>
<!------------------------------------- 加班记录 ------------------------------->


<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("加班记录")?></span>
            &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="<?=_("导出")?>" class="BigButton" onClick="location='export_overtime.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE=<?=$DUTY_TYPE?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&OVERTIME_ID=<?=$OVERTIME_ID?>'" title="<?=_("导出加班记录")?>" name="button">
        </td>
    </tr>
</table>

<?
if($DEPARTMENT1!="ALL_DEPT")
    $WHERE_STR=" and DEPARTMENT.DEPT_ID in ($DET_LIST) ";

$query = "SELECT * from ATTENDANCE_OVERTIME,USER,DEPARTMENT where DEPARTMENT.DEPT_ID=USER.DEPT_ID ".$WHERE_STR." and ATTENDANCE_OVERTIME.USER_ID=USER.USER_ID and ((to_days(START_TIME)>=to_days('$DATE1') and to_days(START_TIME)<=to_days('$DATE2')) or (to_days(END_TIME)>=to_days('$DATE1') and to_days(END_TIME)<=to_days('$DATE2')) or (to_days(START_TIME)<=to_days('$DATE1') and to_days(END_TIME)>=to_days('$DATE2')))and allow in('1','3') order by DEPT_NO,USER_NO,USER_NAME";
$cursor= exequery(TD::conn(),$query, $connstatus);
$OVERTIME_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $OVERTIME_ID=$ROW["OVERTIME_ID"];
    $USER_NAME=$ROW["USER_NAME"];
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
    $query8 = "SELECT * from USER where USER_ID='$APPROVE_ID'";
    $cursor8= exequery(TD::conn(),$query8);
    if($ROW8=mysql_fetch_array($cursor8))
        $APPROVE_NAME=$ROW8["USER_NAME"];

    if(!is_dept_priv($DEPT_ID))
        continue;

    $OVERTIME_COUNT++;

    if($STATUS=="0")
        $STATUS_DESC=_("未确认");
    else if($STATUS=="1")
        $STATUS_DESC=_("已确认");
    $DEPT_ID=intval($DEPT_ID);
    $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
        $USER_DEPT_NAME=$ROW["DEPT_NAME"];

    if($OVERTIME_COUNT==1)
    {
    ?>

<table class="TableList" width="95%">

    <?
    }

    $ALL_HOURS3 = floor((strtotime($END_TIME)-strtotime($START_TIME)) / 3600);
    $HOUR13 = (strtotime($END_TIME)-strtotime($START_TIME)) % 3600;
    $MINITE3 = floor($HOUR13 / 60);
    ?>
    <tr class="TableData">
        <td nowrap align="center"><?=$USER_DEPT_NAME?></td>
        <td nowrap align="center"><?=$USER_NAME?></td>
        <td nowrap align="center"><?=$RECORD_TIME?></td>
        <td class="AutoNewline" width="400" align="center">
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
        <td nowrap align="center"><?=$ALL_HOURS3._("小时").$MINITE3._("分")?></td>
        <td nowrap align="center"><?=$APPROVE_NAME?></td>
        <td nowrap align="center"><?=$STATUS_DESC?>	</td>
        <td nowrap align="center">
            <?
            if($_SESSION["LOGIN_USER_PRIV"]==1)
            {
                ?>
                <a href="delete_overtime.php?DEPARTMENT1=<?=$DEPARTMENT1?>&DUTY_TYPE=<?=$DUTY_TYPE?>&DATE1=<?=$DATE1?>&DATE2=<?=$DATE2?>&OVERTIME_ID=<?=$OVERTIME_ID?>"><?=_("删除")?></a>
                <?
            }
            ?>
        </td>
    </tr>
    <?
    }

    if($OVERTIME_COUNT>0)
    {
    ?>
    <thead class="TableHeader">
    <td nowrap align="center"><?=_("部门")?></td>
    <td nowrap align="center"><?=_("姓名")?></td>
    <td nowrap align="center"><?=_("申请时间")?></td>
    <td nowrap align="center"><?=_("加班内容")?></td>
    <td nowrap align="center"><?=_("登记")?>IP </td>
    <td nowrap align="center"><?=_("开始日期")?></td>
    <td nowrap align="center"><?=_("结束时间")?></td>
    <td nowrap align="center"><?=_("时长")?></td>
    <td nowrap align="center"><?=_("审批人员")?></td>
    <td nowrap align="center"><?=_("状态")?></td>
    <td nowrap align="center"><?=_("操作")?></td>
    </thead>
</table>
<?
}
else
    message("",_("无加班记录"));
?>

<div align="center">
    <input type="button"  value="<?=_("返回")?>" class="BigButton" onClick="location='index1.php';">
</div>

</body>
</html>