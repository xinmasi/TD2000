<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("会议室设置");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script Language="JavaScript">
function CheckForm()
{
    if(document.form1.MR_NAME.value=="")
    { alert("<?=_("请指定会议室名称！")?>");
        return (false);
    }
    form1.submit();
}
</script>
<body class="bodycolor">

<?
$APPLY_WEEKDAYS_ARR=array();
if($MR_ID!="")
{
    $query = "SELECT * from MEETING_ROOM where MR_ID='$MR_ID'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $MR_NAME        = $ROW["MR_NAME"];
        $MR_CAPACITY    = $ROW["MR_CAPACITY"];
        $MR_DEVICE      = $ROW["MR_DEVICE"];
        $MR_DESC        = $ROW["MR_DESC"];
        $MR_PLACE       = $ROW["MR_PLACE"];
        $OPERATOR       = $ROW['OPERATOR'];
        $TO_ID          = $ROW["TO_ID"];
        $SECRET_TO_ID   = $ROW["SECRET_TO_ID"];
        $VIDEO_TYPE     = $ROW["VIDEO_TYPE"];

        $APPLY_WEEKDAYS = $ROW["APPLY_WEEKDAYS"];
        if($APPLY_WEEKDAYS!="")
            $APPLY_WEEKDAYS_ARR=explode(",",$APPLY_WEEKDAYS);
        $query = "SELECT USER_NAME from USER where find_in_set(USER_ID,'".$OPERATOR."')";
        $cursor= exequery(TD::conn(),$query);
        while($ROW=mysql_fetch_array($cursor))
            $OPERATOR_NAME.=$ROW["USER_NAME"].",";

        $query1 = "SELECT USER_NAME from USER where find_in_set(USER_ID,'".$SECRET_TO_ID."')";
        $cursor1= exequery(TD::conn(),$query1);
        while($ROW1=mysql_fetch_array($cursor1))
        {
            $SECRET_TO_NAME.=$ROW1["USER_NAME"].",";
        }
        $TOK=strtok($TO_ID,",");
        while($TOK!="")
        {
            $query2 = "SELECT DEPT_NAME from DEPARTMENT where DEPT_ID='$TOK'";
            $cursor2= exequery(TD::conn(),$query2);
            if($ROW=mysql_fetch_array($cursor2))
                $DEPT_NAME.=$ROW["DEPT_NAME"].",";
            $TOK=strtok(",");
        }

        if($TO_ID=="ALL_DEPT")
            $DEPT_NAME=_("全体部门");
    }
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建会议室")?></span>
        </td>
    </tr>
</table><br>
<table class="TableBlock" align="center" width="70%">
    <form enctype="multipart/form-data" action="add.php" method="post" name="form1">
        <tr>
            <td nowrap class="TableData" width="80"><?=_("会议室名称：")?></td>
            <td class="TableData" colspan="3">
                <input type="text" name="MR_NAME" size="40" maxlength="100" class="BigInput" value="<?=$MR_NAME?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" width="80"><?=_("会议室描述：")?></td>
            <td class="TableData" colspan="3">
                <textarea name="MR_DESC" class="BigInput" cols="45" rows="3"><?=$MR_DESC?></textarea>
            </td>
        </tr>
        <tr bgcolor="#CCCCCC">
            <td class="TableData"><?=_("会议室管理员：")?></td>
            <td class="TableData">
                <input type="hidden" name="COPY_TO_ID" value="<?=$OPERATOR?>">
                <textarea cols=45 name="COPY_TO_NAME" rows=3 class="BigStatic" wrap="yes" readonly><?=$OPERATOR_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('89','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
            </td>
        </tr>
        <tr>
        <tr bgcolor="#CCCCCC">
            <td class="TableData"><?=_("申请权限(部门)：")?></td>
            <td class="TableData">
                <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
                <textarea cols=45 name="TO_NAME" rows=3 class="BigStatic" wrap="yes" readonly><?=$DEPT_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectDept()"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
            </td>
        </tr>
        <tr>
        <tr bgcolor="#CCCCCC">
            <td class="TableData"><?=_("申请权限(人员)：")?></td>
            <td class="TableData">
                <input type="hidden" name="SECRET_TO_ID" value="<?=$SECRET_TO_ID?>">
                <textarea cols=45 name="SECRET_TO_NAME" rows=3 class="BigStatic" wrap="yes" readonly><?=$SECRET_TO_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('89','','SECRET_TO_ID', 'SECRET_TO_NAME')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('SECRET_TO_ID', 'SECRET_TO_NAME')"><?=_("清空")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" width="80"><?=_("可容纳人数：")?></td>
            <td class="TableData" colspan="3">
                <input type="text" name="MR_CAPACITY" size="5" maxlength="100" class="BigInput" value="<?=$MR_CAPACITY?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" width="80"><?=_("是否支持视频会议：")?></td>
            <td class="TableData" colspan="3">
                <input type="radio" name="VIDEO_TYPE" id="VIDEO_TYPE1" value="1" <?if($VIDEO_TYPE==1) echo "checked";?>><label for="VIDEO_TYPE1"><?=_("是")?></label>
                <input type="radio" name="VIDEO_TYPE" id="VIDEO_TYPE2" value="0" <?if($VIDEO_TYPE==0) echo "checked";?>><label for="VIDEO_TYPE2"><?=_("否")?></label>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" width="80"><?=_("可申请时间：")?></td>
            <td class="TableData" colspan="3">
                <input type="checkbox" name="sun" <?=in_array('0',$APPLY_WEEKDAYS_ARR)?"checked":"";?> value="0" id="sun" /><label for="sun"><?=_("星期日")?></label>
                <input type="checkbox" name="mon" <?=in_array('1',$APPLY_WEEKDAYS_ARR)||empty($APPLY_WEEKDAYS_ARR)?"checked":"";?> value="1" id="mon" /><label for="mon"><?=_("星期一")?></label>
                <input type="checkbox" name="tue" <?=in_array('2',$APPLY_WEEKDAYS_ARR)||empty($APPLY_WEEKDAYS_ARR)?"checked":"";?> value="2" id="tue" /><label for="tue"><?=_("星期二")?></label>
                <input type="checkbox" name="wed" <?=in_array('3',$APPLY_WEEKDAYS_ARR)||empty($APPLY_WEEKDAYS_ARR)?"checked":"";?> value="3" id="wed" /><label for="wed"><?=_("星期三")?></label>
                <input type="checkbox" name="thu" <?=in_array('4',$APPLY_WEEKDAYS_ARR)||empty($APPLY_WEEKDAYS_ARR)?"checked":"";?> value="4" id="thu" /><label for="thu"><?=_("星期四")?></label>
                <input type="checkbox" name="fri" <?=in_array('5',$APPLY_WEEKDAYS_ARR)||empty($APPLY_WEEKDAYS_ARR)?"checked":"";?> value="5" id="fri" /><label for="fri"><?=_("星期五")?></label>
                <input type="checkbox" name="sat" <?=in_array('6',$APPLY_WEEKDAYS_ARR)?"checked":"";?> value="6" id="sat" /><label for="sat"><?=_("星期六")?></label>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" width="80"><?=_("设备情况：")?></td>
            <td class="TableData" colspan="3">
                <textarea name="MR_DEVICE" class="BigInput" cols="45" rows="3"><?=$MR_DEVICE?></textarea><br>
                <?=_("说明：除了该处的“设备情况”外，还有会议室设备管理模块，可以对会议室添加设备。申请人申请的时候，可以选择需要使用的设备。")?>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" width="80"><?=_("地址：")?></td>
            <td class="TableData" colspan="3">
                <input type="text" name="MR_PLACE" size="40" maxlength="100" class="BigInput" value="<?=$MR_PLACE?>">
            </td>
        </tr>
        <tr class="TableControl">
            <td nowrap colspan="4" align="center">
                <input type="hidden" value="<?=$MR_ID?>" name="MR_ID">
                <input type="button" value="<?=_("保存")?>" class="BigButton" onclick="CheckForm();">&nbsp;&nbsp;
                <?
                if($MR_ID!="")
                {
                    ?>
                    <input type="button" class="BigButton" value="<?=_("返回")?>" onclick="location='index.php';">
                    <?
                }
                else
                {
                    ?>
                    <input type="button" class="BigButton" value="<?=_("返回")?>" onclick="location='index.php';">
                    <?
                }
                ?>
            </td>
        </tr>
    </form>
</table>

</body>
</html>