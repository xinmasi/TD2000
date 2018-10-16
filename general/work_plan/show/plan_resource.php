<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = $USER_NAME;
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm()
{
    if(document.form1.PPLAN_CONTENT.value=="")
    { alert("<?=_("计划任务不能为空！")?>");
        return (false);
    }

    return true;
}

function save()
{
    if(CheckForm())
    {
        document.form1.submit();
    }
}
function delete_resource(AUTO_PERSON,USER_ID,PLAN_ID,USER_NAME,NAME,URL_BEGIN_DATE,URL_END_DATE,ATTACHMENT_ID,ATTACHMENT_NAME)
{
    msg='<?=_("确认要删除该计划任务吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete_resource.php?AUTO_PERSON="+AUTO_PERSON+"&USER_ID="+USER_ID+"&PLAN_ID="+PLAN_ID+"&USER_NAME="+USER_NAME+"&URL_BEGIN_DATE="+URL_BEGIN_DATE+"&URL_END_DATE="+URL_END_DATE+"&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
        window.location=URL;
    }
}
</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/work_plan.gif" width="22" height="18" align="absMiddle"><span class="big3"> <?=$USER_NAME?> - <?=_("计划任务")?> - <?=$NAME?>(<?=format_date($URL_BEGIN_DATE)?> - <?=format_date($URL_END_DATE)?>)</span>
        </td>
    </tr>
</table>

<?
//修改事务提醒状态--yc
update_sms_status('12',$PLAN_ID);

$query = "SELECT CREATOR,MANAGER,PARTICIPATOR from WORK_PLAN where PLAN_ID='$PLAN_ID'";
//echo $query;
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $CREATOR=$ROW["CREATOR"];
    $MANAGER=$ROW["MANAGER"];
    $PARTICIPATOR=$ROW["PARTICIPATOR"];
}

if(find_id($MANAGER.$CREATOR,$_SESSION["LOGIN_USER_ID"]))
    $HAVE_RIGHT=1;

$CUR_DATE=date("Y-m-d",time());
$query = "SELECT AUTO_PERSON,PBEGEI_DATE,PEND_DATE,PPLAN_CONTENT,PUSE_RESOURCE,PUSER_ID,ATTACHMENT_ID,ATTACHMENT_NAME from WORK_PERSON where PLAN_ID='$PLAN_ID' and PUSER_ID='$USER_ID'";
$cursor= exequery(TD::conn(),$query);
$COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $COUNT++;

    $AUTO_PERSON=$ROW["AUTO_PERSON"];
    $PBEGEI_DATE=$ROW["PBEGEI_DATE"];
    $PEND_DATE=$ROW["PEND_DATE"];
    $ATTACHMENT_ID1=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME1=$ROW["ATTACHMENT_NAME"];
    $PUSER_ID=$ROW["PUSER_ID"];
    $PUSER_ID_STR.=$PUSER_ID.",";

    $PPLAN_CONTENT=str_replace("\n","<br>",$ROW["PPLAN_CONTENT"]);
    $PUSE_RESOURCE=str_replace("\n","<br>",$ROW["PUSE_RESOURCE"]);

    if($PEND_DATE=="0000-00-00")
        $PEND_DATE="";

    if($COUNT==1)
    {
    ?>
<table class="TableList" width="95%" align="center">
    <tr class="TableHeader">
        <td nowrap align="center" width="15%"><?=_("开始时间")?></td>
        <td nowrap align="center" width="15%"><?=_("结束时间")?></td>
        <td nowrap align="center" width="30%"><?=_("计划任务")?></td>
        <td nowrap align="center"><?=_("附件")?></td>
        <td nowrap align="center" width="30%"><?=_("相关资源")?></td>
        <?
        if($HAVE_RIGHT==1)
        {
            ?>
            <td nowrap align="center" width="10%"><?=_("操作")?></td>
            <?
        }
        ?>
    </tr>
    <?
    }

    if($COUNT%2==1)
        $TableLine="TableLine1";
    else
        $TableLine="TableLine2";

    ?>
    <tr class="<?=$TableLine?>">
        <td nowrap align="center"><?=$PBEGEI_DATE?></td>
        <td nowrap align="center"><?=$PEND_DATE?></td>
        <td align="left"><?=$PPLAN_CONTENT?></td>
        <td nowrap align="center"><?=attach_link($ATTACHMENT_ID1,$ATTACHMENT_NAME1,0,1,1)?></td>
        <td align="left"><?=$PUSE_RESOURCE?></td>
        <td nowrap align="center">
            <?if (find_id($PUSER_ID_STR.$MANAGER, $_SESSION["LOGIN_USER_ID"]))
            {
                ?>
                <a href="resource_diary/add_resource.php?AUTO_PERSON=<?=$AUTO_PERSON?>"> <?=_("撰写进度日志")?></a>
            <?}

            if ($HAVE_RIGHT==1)
            {
                ?>
                <a href="modify_resource.php?AUTO_PERSON=<?=$AUTO_PERSON?>&USER_ID=<?=$USER_ID?>&PLAN_ID=<?=$PLAN_ID?>&USER_NAME=<?=urlencode($USER_NAME)?>&NAME=<?=urlencode($NAME)?>&URL_BEGIN_DATE=<?=$URL_BEGIN_DATE?>&URL_END_DATE=<?=$URL_END_DATE?>"> <?=_("修改")?></a>
                <a href="javascript:delete_resource('<?=$AUTO_PERSON?>','<?=$USER_ID?>','<?=$PLAN_ID?>','<?=$USER_NAME?>','<?=$NAME?>','<?=$URL_BEGIN_DATE?>','<?=$URL_END_DATE?>','<?=$ATTACHMENT_ID1?>','<?=$ATTACHMENT_NAME1?>');"> <?=_("删除")?></a>
            <?}?>
        </td>

    </tr>
    <?
    } //while

    if($COUNT==0)
    {
        Message("",$USER_NAME._("无计划任务"));
    }
    else
    {
    ?>
</table>
<?
}

if($HAVE_RIGHT==1)
{
    ?>
    <br>
    <table border="0" width="95%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" align="absmiddle" width="22" height="20"><span class="big3"> <?=_("增加计划任务")?>(<?=$USER_NAME?>)</span>
            </td>
        </tr>
    </table>
    <form enctype="multipart/form-data" action="sub_resource.php"  method="post" name="form1">
        <table class="TableBlock" width="95%" align="center">
            <tr>
                <td class="TableContent" width="10%"> <?=_("开始时间：")?></td>
                <td class="TableData" width="20%">
                    <input type="text" id="start_time" name="PBEGEI_DATE" size="10" maxlength="10" class="BigInput" value="<?=$CUR_DATE?>" onClick="WdatePicker()">

                </td>
                <td class="TableContent" width="10%"> <?=_("结束时间：")?></td>
                <td class="TableData">
                    <input type="text" name="PEND_DATE" size="10" maxlength="10" class="BigInput" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})">

                </td>
            </tr>
            <tr>
                <td class="TableContent"> <?=_("计划任务：")?></td>
                <td class="TableData" colspan="3">
                    <textarea cols=65 name="PPLAN_CONTENT" rows=5 class="BigINPUT" wrap="yes"></textarea>
                </td>
            </tr>
            <tr>
                <td class="TableContent"> <?=_("相关资源：")?></td>
                <td class="TableData" colspan="3">
                    <textarea cols=65 name="PUSE_RESOURCE" rows=5 class="BigINPUT" wrap="yes"></textarea>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableContent"><?=_("附件选择：")?></td>
                <td class="TableData" colspan="3">
                    <script>ShowAddFile();</script>
                </td>
            </tr>
            <tr>
                <td nowrap class="TableContent"> <?=_("提醒：")?></td>
                <td class="TableData" colspan="3">
                    <?=sms_remind(12);?>
                </td>
            </tr>
            <tr align="center" class="TableControl">
                <td colspan="4">
                    <input type="hidden" value="<?=$PLAN_ID?>" name="PLAN_ID">
                    <input type="hidden" value="<?=$NAME?>" name="NAME">
                    <input type="hidden" value="<?=$URL_BEGIN_DATE?>" name="URL_BEGIN_DATE">
                    <input type="hidden" value="<?=$URL_END_DATE?>" name="URL_END_DATE">
                    <input type="hidden" value="<?=$USER_ID?>" name="USER_ID">
                    <input type="hidden" value="<?=$USER_NAME?>" name="USER_NAME">
                    <input type="button" value="<?=_("保存")?>" class="BigButton" onclick="save();">&nbsp;&nbsp;
                    <input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="window.close();">&nbsp;&nbsp;
                </td>
            </tr>
        </table>
    </form>
    <?
}
else
    echo "<br><center><input type=\"button\" value=\""._("关闭")."\" class=\"BigButton\" onclick=\"window.close();\"></center>";
?>
</body>
</html>