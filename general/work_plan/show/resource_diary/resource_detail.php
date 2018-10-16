<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

//修改事务提醒状态--yc
update_sms_status('12',$RPERSON_ID);

$query = "SELECT * FROM WORK_PERSON WHERE AUTO_PERSON='$RPERSON_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $WORK_PLAN_NAME=$ROW["PPLAN_CONTENT"];

$HTML_PAGE_TITLE = _("任务进度日志详情");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script>
function delete_diary(DETAIL_ID,PLAN_ID,ATTACHMENT_ID,ATTACHMENT_NAME)
{
    msg='<?=_("确认要删除该进度日志吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete_diary.php?DETAIL_ID=" + DETAIL_ID+"&PLAN_ID=" + PLAN_ID+"&ATTACHMENT_ID=" + ATTACHMENT_ID+"&ATTACHMENT_NAME=" + ATTACHMENT_NAME+"&FLAG=" + 1;
        window.location=URL;
    }
}
</script>


<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/work_plan.gif" width="22" height="18" align="absMiddle"><span class="big3"> <?=_("进度日志详情")?>(<?=$WORK_PLAN_NAME?>)</span><br>
        </td>
    </tr>
</table>
<br>
<?
$query = "SELECT * from WRESOURCE_DETAIL where RPERSON_ID='$RPERSON_ID' order by WRITE_TIME desc";
$cursor=exequery(TD::conn(),$query);
$DETAIL_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $DETAIL_COUNT++;
    $DETAIL_ID=$ROW["AUTO_DETAIL"];
    $WRITE_TIME=$ROW["WRITE_TIME"];
    $PROGRESS=$ROW["PROGRESS"];
    $PERCENT =$ROW["PERCENT"];
    $WRITER=$ROW["WRITER"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

    $PROGRESS=str_replace("\n","<br>",$PROGRESS);
    $query1 = "SELECT * from USER where USER_ID='$WRITER'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW1=mysql_fetch_array($cursor1))
        $USER_NAME=$ROW1["USER_NAME"];

    if($DETAIL_COUNT==1)
    {
    ?>
<table class="TableList"  width="95%" align="center">
    <tr class="TableHeader">
        <td nowrap align="center"><?=_("作者")?></td>
        <td nowrap align="center"><?=_("内容")?></td>
        <td nowrap align="left"><?=_("附件")?></td>
        <td nowrap align="center"><?=_("日志时间")?></td>
        <td nowrap align="center"><?=_("进度百分比")?></td>
        <td nowrap align="center"><?=_("操作")?></td>
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
        <td nowrap align="left"><?=$PROGRESS?></td>
        <td nowrap align="left">
            <?
            if($ATTACHMENT_ID=="")
                echo _("无附件");
            else
                echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,0,0,1,0,0);
            ?>

        </td>
        <td nowrap align="center"><?=$WRITE_TIME?></td>
        <td nowrap align="center"><?=$PERCENT?>%</td>
        <td nowrap align="center">
            <?
            if($_SESSION["LOGIN_USER_ID"]==$WRITER or $_SESSION["LOGIN_USER_PRIV"]==1)
            {
                ?>
                <a href="edit_resource.php?DETAIL_ID=<?=$DETAIL_ID?>&PLAN_ID=<?=$PLAN_ID?>"> <?=_("修改")?></a>
                <a href="javascript:delete_diary('<?=$DETAIL_ID?>','<?=$RPERSON_ID?>','<?=$ATTACHMENT_ID?>','<?=$ATTACHMENT_NAME?>');"> <?=_("删除")?></a>
                <?
            }
            ?>
        </td>
    </tr>

    <?
    } //while

    if($DETAIL_COUNT==0)
    {
        Message("",_("无进度日志"));
        echo  "<br><center><input type=\"button\" value=\""._("关闭")."\" class=\"BigButton\"  onclick=\"window.close();\"></center>";
        exit;
    }
    else
    {
    ?>
    <tr class="TableControl">
        <td align="center"colspan="7">
            <input type="button" value="<?=_("关闭")?>" class="BigButton"  onclick="window.close();">
        </td>
    </tr>
</table>
<?
}
?>
</body>
</html>
