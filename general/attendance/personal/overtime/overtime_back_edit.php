<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

//�Ӱ�ȷ�ϴ���
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
    $OVERTIME_HOURS_ARRAY=explode(_("Сʱ"),$OVERTIME_HOURS);
    $OVERTIME_MINUTES_ARRAY=explode(_("��"),$OVERTIME_HOURS_ARRAY[1]);
}

$HTML_PAGE_TITLE = _("�Ӱ�ȷ��");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>

<body class="bodycolor attendance">
<h5 class="attendance-title"><span class="big3"> <?=_("�Ӱ�ȷ��")?></span></h5><br>
<br>
<div align="center">
    <form action="overtime_back_submit.php"  method="post" name="form1" class="big1">
        <table class="TableBlock" width="80%" align="center">
            <tr>
                <td nowrap class="TableData"> <?=_("�Ӱ࿪ʼʱ�䣺")?></td>
                <td class="TableData">
                    <input type="text" name="START_TIME" size="20" maxlength="5" class="BigStatic" readonly value="<?=$START_TIME?>">
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData"> <?=_("�Ӱ����ʱ�䣺")?></td>
                <td class="TableData">
                    <input type="text" name="END_TIME" size="20" maxlength="5" class="BigStatic" readonly value="<?=$END_TIME?>">
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData"> <?=_("�Ӱ�ʱ����")?></td>
                <td class="TableData">
                    <input type="text" name="OVERTIME_HOURS" size="2" maxlength="2" class="BigStatic"  readonly value="<?=$OVERTIME_HOURS_ARRAY[0]?>"><?=_("Сʱ")?>
                    <input type="text" name="OVERTIME_MINUTES" size="2" maxlength="2" class="BigStatic" readonly value="<?=$OVERTIME_MINUTES_ARRAY[0]?>"><?=_("��")?>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData"> <?=_("�Ӱ����ݣ�")?></td>
                <td class="TableData">
                    <textarea name="OVERTIME_CONTENT" class="BigStatic" cols="60" rows="4" readonly><?=$OVERTIME_CONTENT?></textarea>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableData"> <?=_("�������ѣ�")?></td>
                <td class="TableData"> <?=sms_remind(6);?></td>
            </tr>
        </table>
</div>
<br><br><br>
<center>
    <input type="hidden" name="OVERTIME_ID" value="<?=$OVERTIME_ID?>">
    <input type="hidden" name="APPROVE_ID" value="<?=$APPROVE_ID?>">
    <input type="submit" value="<?=_("����")?>" class="btn btn-primary">&nbsp;
    <input type="button" value="<?=_("����")?>" class="btn " onClick="location='index.php'">
</center>
</form>
</body>
</html>