<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

//加班确认处理
$CUR_TIME=date("Y-m-d H:i:s",time());

$query = "SELECT * from ATTENDANCE_OVERTIME where OVERTIME_ID='$OVERTIME_ID'";
$cursor= exequery(TD::conn(),$query);
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

<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

<body class="bodycolor attendance">
<h5 class="attendance-title"><span class="big3"> <?=_("加班确认")?></span></h5><br>
<br>
<div align="center">
    <form action="overtime_back_submit.php"  method="post" name="form1" class="big1">
        <table class="TableBlock" width="80%" align="center">
            <tr>
                <td nowrap class="TableData"> <?=_("加班开始时间：")?></td>
                <td class="TableData">
                    <input type="text" name="START_TIME" size="20" maxlength="5" class="BigStatic" readonly value="<?=$START_TIME?>">
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData"> <?=_("加班结束时间：")?></td>
                <td class="TableData">
                    <input type="text" name="END_TIME" size="20" maxlength="5" class="BigStatic" readonly value="<?=$END_TIME?>">
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData"> <?=_("加班时长：")?></td>
                <td class="TableData">
                    <input type="text" name="OVERTIME_HOURS" size="2" maxlength="2" class="BigStatic"  readonly value="<?=$OVERTIME_HOURS_ARRAY[0]?>"><?=_("小时")?>
                    <input type="text" name="OVERTIME_MINUTES" size="2" maxlength="2" class="BigStatic" readonly value="<?=$OVERTIME_MINUTES_ARRAY[0]?>"><?=_("分")?>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData"> <?=_("加班内容：")?></td>
                <td class="TableData">
                    <textarea name="OVERTIME_CONTENT" class="BigStatic" cols="60" rows="4" readonly><?=$OVERTIME_CONTENT?></textarea>
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
    <input type="hidden" name="OVERTIME_ID" value="<?=$OVERTIME_ID?>">
    <input type="hidden" name="APPROVE_ID" value="<?=$APPROVE_ID?>">
    <input type="submit" value="<?=_("保存")?>" class="btn btn-primary">&nbsp;
    <input type="button" value="<?=_("返回")?>" class="btn " onClick="location='index.php'">
</center>
</form>
</body>
</html>