<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/header.inc.php");
$connstatus = ($connstatus) ? true : false;
//加班确认处理
$CUR_TIME=date("Y-m-d H:i:s",time());

$query="select * from SYS_PARA where PARA_NAME='SMS_REMIND'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $PARA_VALUE=$ROW["PARA_VALUE"];
$SMS_REMIND1=substr($PARA_VALUE,0,strpos($PARA_VALUE,"|"));
//$SMS2_REMIND1=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);
$SMS2_REMIND1_TMP=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);
$SMS2_REMIND1=substr($SMS2_REMIND1_TMP,0,strpos($SMS2_REMIND1_TMP,"|"));
if($OP=="1")
{
    if($OVERTIME_HOURS>99)
    {
        Message(_("错误"),_("加班时长只允许两位数字"));
        Button_Back();
        exit;
    }
    if(!is_numeric($OVERTIME_HOURS) || !is_numeric($OVERTIME_MINUTES))
    {
        Message(_("错误"),_("加班时长应该是数字！"));
        Button_Back();
        exit;
    }
    if($OVERTIME_HOURS<=0 || $OVERTIME_MINUTES<0)
    {
        Message(_("错误"),_("加班时长必须大于0！"));
        Button_Back();
        exit;
    }
    //加班时长
    if($OVERTIME_HOURS=="" && $OVERTIME_MINUTES=="")
    {
        $ALL_HOURS3 = floor((strtotime($END_TIME)-strtotime($START_TIME)) / 3600);
        $HOUR13 = (strtotime($END_TIME)-strtotime($START_TIME)) % 3600;
        $MINITE3 = floor($HOUR13 / 60);
        $OVERTIME_HOURS2 = $ALL_HOURS3._("小时").$MINITE3._("分");
    }
    else
    {
        $OVERTIME_HOURS=$OVERTIME_HOURS==""?0:$OVERTIME_HOURS;
        $OVERTIME_MINUTES=$OVERTIME_MINUTES==""?0:$OVERTIME_MINUTES;
        $OVERTIME_HOURS2 = $OVERTIME_HOURS._("小时").$OVERTIME_MINUTES._("分");
    }

    $query="update ATTENDANCE_OVERTIME set OVERTIME_HOURS='$OVERTIME_HOURS2',STATUS='1',CONFIRM_TIME='$CUR_TIME',CONFIRM_VIEW='$CONFIRM_VIEW',START_TIME='$START_TIME',END_TIME='$END_TIME' where OVERTIME_ID='$OVERTIME_ID'";
    exequery(TD::conn(),$query);

    //---------- 事务提醒 ----------
    $SMS_CONTENT=_("您的加班已确认！");
    $REMIND_URL="attendance/personal/overtime";
    if($SMS_REMIND=="on")
        send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID,6,$SMS_CONTENT,$REMIND_URL);

    if($MOBILE_FLAG=="1")
        if(find_id($SMS2_REMIND1,6))
            send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$USER_ID,$SMS_CONTENT,6);
    ?>
    <script>
        if(window.opener.location.href.indexOf("connstatus") < 0 ){
            window.opener.location.href = window.opener.location.href+"?connstatus=1";
        }else{
            window.opener.location.reload();
        }
        window.close();
    </script>
    <?
    exit;
}

$query = "SELECT * from ATTENDANCE_OVERTIME where OVERTIME_ID='$OVERTIME_ID'";
$cursor= exequery(TD::conn(),$query, $connstatus);
if($ROW=mysql_fetch_array($cursor))
{
    $START_TIME=$ROW["START_TIME"];
    $END_TIME=$ROW["END_TIME"];
    $OVERTIME_CONTENT=$ROW["OVERTIME_CONTENT"];
    $APPROVE_ID=$ROW["APPROVE_ID"];
    $OVERTIME_HOURS=$ROW["OVERTIME_HOURS"];
    $OVERTIME_HOURS_ARRAY=explode(_("小时"),$OVERTIME_HOURS);
    $OVERTIME_MINUTES_ARRAY=explode(_("分"),$OVERTIME_HOURS_ARRAY[1]);
}

$HTML_PAGE_TITLE = _("加班确认");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>

<script>
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

        jQuery("#start_time").blur(function(){
            count_time();
        });

        jQuery("#end_time").blur(function(){
            count_time();
        });
    });
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("加班确认")?></span><br>
        </td>
    </tr>
</table>
<br>
<div align="center">
    <form action="overtime_back_edit.php?connstatus=1"  method="post" name="form1" class="big1">
        <table class="TableBlock" width="90%" align="center">
            <tr>
                <td nowrap class="TableData"> <?=_("加班开始时间：")?></td>
                <td class="TableData">
                    <input type="text" id="start_time" name="START_TIME" size="20" maxlength="20" class=" validate[required]"  data-prompt-position="centerRight:0,-8" value="<?=$START_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData"> <?=_("加班结束时间：")?></td>
                <td class="TableData">
                    <input type="text" id="end_time" name="END_TIME" size="20" maxlength="20" class=" validate[required]"  data-prompt-position="centerRight:0,-8" value="<?=$END_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'#F{$dp.$D(\'start_time\')}'})"/>
                </td>
            </tr>

            <tr>
                <td nowrap class="TableData"> <?=_("加班时长：")?></td>
                <td class="TableData">
                    <input type="text" name="OVERTIME_HOURS" id="overtime_hours" size="2" maxlength="2" class="BigInput" value="<?=$OVERTIME_HOURS_ARRAY[0]?>"><?=_("小时")?>
                    <input type="text" name="OVERTIME_MINUTES" id="overtime_minutes" size="2" maxlength="2" class="BigInput" value="<?=$OVERTIME_MINUTES_ARRAY[0]?>"><?=_("分")?>
                    <?=_("(注：不填写则按加班结束时间与加班开始时间差值计算得到小时、分钟数)")?>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData"> <?=_("加班内容：")?></td>
                <td class="TableData">
                    <textarea name="OVERTIME_CONTENT" class="BigStatic" cols="60" rows="4" readonly><?=$OVERTIME_CONTENT?></textarea>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData"> <?=_("确认意见：")?></td>
                <td class="TableData">
                    <textarea name="CONFIRM_VIEW" class="BigInput" cols="60" rows="4"><?=$CONFIRM_VIEW?></textarea>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData"> <?=_("事务提醒：")?></td>
                <td class="TableData"> <?=sms_remind(6);?></td>
            </tr>
        </table>
</div>
<br><br><br>
<center>
    <input type="hidden" name="USER_ID"  value="<?=$USER_ID?>">
    <input type="hidden" name="MOBILE_FLAG"  value="<?=$MOBILE_FLAG?>">
    <input type="hidden" name="OP"  value="1">
    <input type="hidden" name="OVERTIME_ID" value="<?=$OVERTIME_ID?>">
    <input type="hidden" name="APPROVE_ID" value="<?=$APPROVE_ID?>">
    <input type="submit" value="<?=_("保存")?>" class="BigButton">&nbsp;&nbsp;
    <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="javascript:window.close();">
</center>
</form>
</body>
</html>