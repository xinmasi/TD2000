<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("进度日志");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/rating.js"></script>
<script>
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function delete_log(LOG_ID)
{
    msg='<?=_("确认要删除该进度日志吗？")?>';
    if(window.confirm(msg))
    {
        URL="delete_log.php?LOG_ID=" + LOG_ID+"&PROJ_ID=<?=$PROJ_ID?>&TASK_ID=<?=$TASK_ID?>";
        window.location=URL;
    }
}

function CheckForm()
{
    if(document.form1.PERCENT.value=="")
    {
        alert("<?=_("请输入工作进度")?>");
        return false;
    }
    if(parseFloat(document.form1.PERCENT.value)>100 || parseFloat(document.form1.PERCENT.value)<0)
    {
        alert("<?=_("请输入正确的数值范围！")?>");
        return false;
    }
    /*
     if(parseFloat(document.getElementById("PERCENT").value)<100 && document.getElementById("TASK_STATUS").value=="1")
     {
     msg="该任务完成进度未达到100%，确认完成？";
     if(window.confirm(msg))
     {
     return (true);
     }
     else
     return (false);
     }*/
    <?
    if(!$LOG_ID)
    {
    ?>
    if(parseFloat(document.form1.PERCENT.value) < parseFloat(document.form1.PERCENT_MAX.value))
    {
        alert("<?=_("进度百分比数值不能小于上一次的数值")?>");
        return false;
    }
    <?
    }
    ?>
    document.form1.submit();
    document.getElementById('butn').disabled='disabled';
    document.form1.action="";
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
    var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
    if(window.confirm(msg))
    {
        URL="delete_attach.php?LOG_ID1=<?=$LOG_ID1?>&TASK_ID=<?=$TASK_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
        window.location=URL;
    }
    }

</script>


<style>
#myModal{
    height:100px;
    overflow-y: scroll;

}
table.TableList td, table.TableList th {
    vertical-align: top;
}
table.TableList td .attach_link {
  display: block;
}
</style>
<body class="bodycolor">
<?
$CUR_DATE=date("Y-m-d",time());

//修改事务提醒状态--yc
update_sms_status('42',$PROJ_ID);

$query = "SELECT * from PROJ_TASK where TASK_ID='$TASK_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{

    $TASK_NAME=$ROW['TASK_NAME'];

    $TASK_START_TIME =$ROW['TASK_START_TIME'];
    $TASK_END_TIME=$ROW['TASK_END_TIME'];
    $MANAGER=$ROW["MANAGER"];
    $PARTICIPATOR=$ROW["PARTICIPATOR"];
    $SUSPEND_FLAG=$ROW["SUSPEND_FLAG"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];
    $TASK_STATUS=$ROW["TASK_STATUS"];
    $TASK_USER = $ROW["TASK_USER"];
}
if($_SESSION["LOGIN_USER_ID"] !== $TASK_USER){
    Message("",_("这不是您的任务,不能查看"));
    exit;
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/project.gif" align="absmiddle"><span class="big3"> <?=_("进度日志详情")?>(<?=$TASK_NAME?> [<?=$TASK_START_TIME?><?=_("至")?><?=$TASK_END_TIME?>])</span>
        </td>
    </tr>
</table>
<?

$query1 = "SELECT TASK_PERCENT_COMPLETE from PROJ_TASK where TASK_ID='$TASK_ID'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW1=mysql_fetch_array($cursor1))
    $PERCENT_MAX=$ROW1["TASK_PERCENT_COMPLETE"];

$query = "SELECT LOG_ID,LOG_TIME,PERCENT,LOG_USER,LOG_CONTENT,ATTACHMENT_ID,ATTACHMENT_NAME from PROJ_TASK_LOG where TASK_ID='$TASK_ID' order by LOG_USER,LOG_TIME asc";
$cursor=exequery(TD::conn(),$query);
$LOG_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $LOG_COUNT++;
    $LOG_ID1=$ROW["LOG_ID"];
    $LOG_TIME1=$ROW["LOG_TIME"];
    $LOG_CONTENT1=$ROW["LOG_CONTENT"];
    $PERCENT1 =$ROW["PERCENT"];
    $LOG_USER1=$ROW["LOG_USER"];

    $ATTACHMENT_ID1=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME1=$ROW["ATTACHMENT_NAME"];

    $query1 = "SELECT * from USER where USER_ID='$LOG_USER1'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW1=mysql_fetch_array($cursor1))
        $USER_NAME=$ROW1["USER_NAME"];

    if($LOG_COUNT==1)
    {
    ?>
<table class="TableList" width="95%"  align="center">
<colgroup><col width="100"><col width="300"><col width="auto"><col width="100"><col width="100"><col width="100"></colgroup>
    <tr class="TableHeader">
        <td nowrap align="center"><?=_("任务负责人")?></td>
        <td nowrap align="center"><?=_("内容")?></td>
        <td nowrap align="center"><?=_("附件")?></td>
        <td nowrap align="center"><?=_("日志时间")?></td>
        <td nowrap align="center"><?=_("进度百分比")?></td>
        <td nowrap align="center"><?=_("操作")?></td>
    </tr>
    <?
    }

    if($LOG_COUNT%2==1)
        $TableLine="TableLine1";
    else
        $TableLine="TableLine2";

    ?>
    <tr class="<?=$TableLine?>">
        <td nowrap align="center"><?=$USER_NAME?></td>
        <td align="left" style="table-layout:fixed;word-break:break-all;"><?=str_replace("\n","<br>",$LOG_CONTENT1)?></td>
        <td nowrap align="left"><?=attach_link($ATTACHMENT_ID1,$ATTACHMENT_NAME1,0,1,1)?></td>
        <td nowrap align="center"><?=$LOG_TIME1?></td>
        <td nowrap align="center"><?=$PERCENT1?>%</td>
        <td nowrap align="center">
            <?
            if(($_SESSION["LOGIN_USER_ID"]==$LOG_USER1 or $_SESSION["LOGIN_USER_PRIV"]==1) && $TASK_STATUS != "1")//未结束的任务才可以操作 by dq 090629
            {
                ?>
                <a href="detail.php?LOG_ID=<?=$LOG_ID1?>&PROJ_ID=<?=$PROJ_ID?>&TASK_ID=<?=$TASK_ID?>"> <?=_("修改")?></a>
                <a href="#" onclick="delete_log('<?=$LOG_ID1?>')"> <?=_("删除")?></a>
                <?
            }
            else
            {
                echo _("任务已结束");
            }
            ?>
        </td>
    </tr>

    <?
    } //while

    if($LOG_COUNT==0)
    {
        Message("",_("无进度日志"));
    }
    else
    {
    ?>
</table>
<?
}
if($LOG_ID!="")
    $TITLE=_("编辑进度日志");
else
    $TITLE=_("添加进度日志");
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=$TITLE?></span>
        </td>
    </tr>
</table>

<?
//只有在项目审核通过的时候才可以添加任务进度
$sqll = "SELECT PROJ_STATUS FROM proj_project WHERE PROJ_ID = ".$PROJ_ID;
$cursorl= exequery(TD::conn(),$sqll);
$ROWl=mysql_fetch_array($cursorl);
$PROJ_STATUS = $ROWl['PROJ_STATUS'];
if($PROJ_STATUS != '2')
{
    Message("",_("项目未审核,不能更新进度日志"));
    exit;
}

$CUR_TIME=date("Y-m-d H:i:s",time());
if($LOG_ID!="")
{
    $SAVE_FLAG=1;
    $query = "SELECT * from PROJ_TASK_LOG where LOG_ID='$LOG_ID'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $LOG_CONTENT=$ROW["LOG_CONTENT"];
        $PERCENT =$ROW["PERCENT"];
        $LOG_USER=$ROW["LOG_USER"];
        $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
        $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
    }
}

?>
<form action="submit.php"  method="post" name="form1" enctype="multipart/form-data" >
    <table class="TableList"  width="95%" align="center" >
        <tr>
            <td nowrap class="TableContent" width="90"><?=_("当前时间：")?></td>
            <td class="TableData">
                <input type="text" name="LOG_TIME" size="19" readonly maxlength="100" class="BigStatic" value="<?=$CUR_TIME?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableContent"> <?=_("完成百分比：")?></td>
            <td class="TableData" colspan="1">
                <input type="text" name="PERCENT" size="3" class="BigInput" value="<?=$PERCENT?>" onkeyup="value=value.replace(/[^\d]/g,'')"><font size="3"> %</font>
                <input type="hidden" name="PERCENT_MAX" size="2" class="BigInput" value="<?=$PERCENT_MAX?>">
                <div class="PERCENT_CONTRL" style="cursor:w-resize"></div>
                <?=_("历史完成进度：")?><?if(is_null($PERCENT_MAX))echo "0";else echo $PERCENT_MAX;?>%   <?=_("（注：估计完成量与总工作量的百分比）")?>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableContent"> <?=_("进度详情描述：")?></td>
            <td class="TableData" colspan="1">
                <textarea name="LOG_CONTENT" class="BigInput" cols="55" rows="5"><?=$LOG_CONTENT?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableContent"><?=_("附件文档：")?></td>
            <td nowrap class="TableData">
                <?
                if($ATTACHMENT_ID=="")
                    echo _("无附件");
                else
                    echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,0,0);
                ?>
            </td>
        </tr>
        <tr height="25">
            <td nowrap class="TableContent"><?=_("附件选择：")?></td>
            <td class="TableData">
                <script>ShowAddFile();</script>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableContent"> <?=_("提醒：")?></td>
            <td class="TableData">
                <?=sms_remind(42);?>
            </td>
        </tr>
        <tr>
            <td nowrap  class="TableControl" colspan="2" align="center">
                <input type="hidden" value="<?=$TASK_ID?>" name="TASK_ID">
                <input type="hidden" value="<?=$PROJ_ID?>" name="PROJ_ID">
                <input type="hidden" value="<?=$LOG_ID?>" name="LOG_ID">
                <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
                <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
                <input type="button" id="butn" value="<?=_("确定")?>" class="BigButton" onClick='CheckForm();'>&nbsp;&nbsp;
                <!--input type="button" value="<?=_("返回")?>" class="BigButton" onclick="parent.location='task_doing.php'" -->&nbsp;&nbsp;
            </td>
    </table>
</form>
</body>
<script>
jQuery.noConflict();
jQuery('.PERCENT_CONTRL').slidy({
    theme: {
        image: '<?=MYOA_STATIC_SERVER?>/static/images/bluerating.png',
        width: 168,
        height: 36
    },
    width: 402,
    minval: 1,
    maxval: 100,
    interval: 1,
    defaultValue: <?=$PERCENT_MAX?$PERCENT_MAX:1?>,
//   finishedCallback: function (val) {
//     document.form1.PERCENT.value= val ;
//   }//,
    moveCallback: function (val) {
        document.form1.PERCENT.value= val ;
    }
});
</script>
</html>