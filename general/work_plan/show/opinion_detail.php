<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("领导批注");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script>
function delete_opinion(DETAIL_ID,PLAN_ID)
{
    msg='<?=_("确认要删除该批注吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete_opinion.php?DETAIL_ID=" + DETAIL_ID+"&PLAN_ID=" + PLAN_ID;
        window.location=URL;
    }
}
function close_this_new()
{
    TJF_window_close();
}
</script>


<body class="bodycolor">

<?
//修改事务提醒状态--yc
update_sms_status('12',$PLAN_ID);


$query = "SELECT * from WORK_PLAN where PLAN_ID='$PLAN_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $NAME=$ROW['NAME'];

    $BEGIN_DATE1=$ROW['BEGIN_DATE'];
    $END_DATE1=$ROW['END_DATE'];
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/work_plan.gif" width="22" height="18" align="absMiddle"><span class="big3"> <?=_("领导批注")?>(<?=$NAME?> <?=format_date($BEGIN_DATE1)?> - <? if($END_DATE1!="0000-00-00") echo format_date($END_DATE1);?>)</span><br>
        </td>
    </tr>
</table>

<br>
<?
$query = "SELECT * from WORK_DETAIL where TYPE_FLAG='1'and PLAN_ID='$PLAN_ID' order by WRITE_TIME desc";
$cursor=exequery(TD::conn(),$query);
$DETAIL_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $DETAIL_COUNT++;
    $DETAIL_ID=$ROW["DETAIL_ID"];
    $WRITE_TIME=$ROW["WRITE_TIME"];
    $PROGRESS=$ROW["PROGRESS"];
    $PERCENT =$ROW["PERCENT"];
    $WRITER=$ROW["WRITER"];
    $ATTACHMENT_ID1=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME1=$ROW["ATTACHMENT_NAME"];

    $PROGRESS=str_replace("\n","<br>",$PROGRESS);
    $query1 = "SELECT * from USER where USER_ID='$WRITER'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW1=mysql_fetch_array($cursor1))
        $USER_NAME=$ROW1["USER_NAME"];

    if($DETAIL_COUNT==1)
    {
    ?>
<table class="TableList" width="95%" align="center">
    <tr class="TableHeader">
        <td nowrap align="center"><?=_("作者")?></td>
        <td nowrap align="center"><?=_("内容")?></td>
        <td nowrap align="center"><?=_("附件")?></td>
        <td nowrap align="center"><?=_("批注时间")?></td>
    </tr>
    <?
    }

    if($DETAIL_COUNT%2==1)
        $TableLine="TableLine1";
    else
        $TableLine="TableLine2";

    ?>
    <tr class="<?=$TableLine?>">
        <td nowrap align="center"><?=$USER_NAME?></td>
        <td align="left" style="word-break: break-all;"><?=$PROGRESS?></td>
        <td nowrap align="center"><?=attach_link($ATTACHMENT_ID1,$ATTACHMENT_NAME1,0,1,1)?></td>
        <td nowrap align="center"><?=$WRITE_TIME?></td>
    </tr>

    <?
    } //while

    if($DETAIL_COUNT==0)
    {
        Message("",_("无批注"));
        echo "<br><center><input type=\"button\" value=\""._("关闭")."\" class=\"BigButton\"  onclick=\"close_this_new();\"></center>";
        exit;
    }
    else
    {
    ?>
    <tr class="TableControl">
        <td align="center"colspan="7">
            <input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="close_this_new();">
        </td>
    </tr>
</table>
<?
}
?>
</body>
</html>
