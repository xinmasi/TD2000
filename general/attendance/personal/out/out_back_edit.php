<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();

//修改结束时间
$query = "SELECT * from ATTEND_OUT where OUT_ID='$OUT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $OUT_TYPE=$ROW["OUT_TYPE"];
    $OUT_TIME1=$ROW["OUT_TIME1"];
    $OUT_TIME2=$ROW["OUT_TIME2"];
    $LEADER_ID=$ROW["LEADER_ID"];
    $SUBMIT_TIME=substr($ROW["SUBMIT_TIME"],0,10);
}

$HTML_PAGE_TITLE = _("修改外出记录");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function td_calendar(fieldname)
{
    myleft=document.body.scrollLeft+event.clientX-event.offsetX+120;
    mytop=document.body.scrollTop+event.clientY-event.offsetY+230;

    window.showModalDialog("/inc/calendar.php?FIELDNAME="+fieldname,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:280px;dialogHeight:215px;dialogTop:"+mytop+"px;dialogLeft:"+myleft+"px");
}

function td_clock(fieldname,pare)
{
    myleft=document.body.scrollLeft+event.clientX-event.offsetX+120;
    mytop=document.body.scrollTop+event.clientY-event.offsetY+230;
    window.showModalDialog("../../personal/clock.php?FIELDNAME="+fieldname,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:280px;dialogHeight:120px;dialogTop:"+mytop+"px;dialogLeft:"+myleft+"px");
}
</script>

<body class="bodycolor attendance">
<h5 class="attendance-title"><span class="big3"> <?=_("修改外出记录")?></span></h5><br>
<br>
<div align="center">
    <form action="out_back_edit_submit.php"  method="post" name="form1" class="big1">
        <table class="TableBlock" width="90%" align="center">
            <tr>
                <td nowrap class="TableData"> <?=_("外出原因：")?></td>
                <td class="TableData">
                    <textarea name="OUT_TYPE"  cols="60" rows="4" readonly><?=$OUT_TYPE?></textarea>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData"> <?=_("外出时间：")?></td>
                <td class="TableData">
                    <?=_("日期")?> <input type="text" class="input-small" name="OUT_DATE" size="15" maxlength="5"  readonly value="<?=$SUBMIT_TIME?>">
                    <?=_("从")?> <input type="text" class="input-small" id="start_time" name="OUT_TIME1" size="5" maxlength="5"  readonly value="<?=$OUT_TIME1?>">
                    <?=_("至")?> <input type="text" class="input-small" name="OUT_TIME2" size="5" maxlength="5"  value="<?=$OUT_TIME2?>" onClick="WdatePicker({dateFmt:'HH:mm',minDate:'#F{$dp.$D(\'start_time\')}'})"><br>
                </td>
            </tr>
        </table>
</div>
<br><br><br>
<center>
    <input type="hidden" name="OUT_ID" value="<?=$OUT_ID?>">
    <input type="hidden" name="LEADER_ID" value="<?=$LEADER_ID?>">
    <input type="submit" value="<?=_("保存")?>" class="btn btn-primary">&nbsp;&nbsp;
    <input type="button" value="<?=_("关闭")?>" class="btn" onClick="javascript:window.close();">
</center>
</form>
</body>
</html>