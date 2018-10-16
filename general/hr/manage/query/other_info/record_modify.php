<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");
$HTML_PAGE_TITLE = _("新增培训记录");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>

<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm()
{
    if(document.form1.T_PLAN_NAME.value == "")
    {
        alert("<?=_("培训计划名称不能为空")?>");
        return false;
    }
    return true;
}
function LoadWindow()
{
    URL="../../../training/record/record_select/?T_PLAN_NO=<?=$T_PLAN_NO?>";
    loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
    loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
    if(window.showModalDialog){
        window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:320px;dialogHeight:245px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
    }else{
        window.open(URL,"parent","height=245,width=320,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes,modal=yes,dependent=yes,dialog=yes,minimizable=no",true);
    }
}
</script>
<?
$query = "SELECT * from  HR_TRAINING_RECORD WHERE RECORD_ID='$RECORD_ID' ";
$cursor=exequery(TD::conn(),$query);
$COUNT = mysql_num_rows($cursor);

if($ROW=mysql_fetch_array($cursor))
{
    $REQUIREMENTS_COUNT++;

    $RECORD_ID=$ROW["RECORD_ID"];
    $STAFF_USER_ID=$ROW["STAFF_USER_ID"];
    $T_PLAN_NO=$ROW["T_PLAN_NO"];
    $T_PLAN_NAME=$ROW["T_PLAN_NAME"];
    $T_INSTITUTION_NAME=$ROW["T_INSTITUTION_NAME"];
    $TRAINNING_COST=$ROW["TRAINNING_COST"];
    $DUTY_SITUATION=$ROW["DUTY_SITUATION"];
    $TRAINNING_SITUATION=$ROW["TRAINNING_SITUATION"];
    $T_EXAM_RESULTS=$ROW["T_EXAM_RESULTS"];
    $T_EXAM_LEVEL=$ROW["T_EXAM_LEVEL"];
    $T_COMMENT=$ROW["T_COMMENT"];
    $REMARK=$ROW["REMARK"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];


    $query2= "SELECT USER_NAME from  USER WHERE USER_ID='$STAFF_USER_ID'";
    $cursor2=exequery(TD::conn(),$query2);
    if($ROW2=mysql_fetch_array($cursor2))
    {
        $STAFF_USER_NAME=$ROW2["USER_NAME"];
    }
}
?>
<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新增培训记录")?></span>&nbsp;&nbsp;
        </td>
    </tr>
</table>
<br>
<form enctype="multipart/form-data" action="record_update.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <table class="TableBlock" width="60%" align="center">
        <tr>
            <td nowrap class="TableData"><?=_("受训人：")?></td>
            <td class="TableData" >
                <input type="hidden" name="STAFF_USER_ID" value="<?=$STAFF_USER_ID?>">
                <INPUT type="text"name="STAFF_USER_NAME" class=BigStatic size="15" value="<?=$STAFF_USER_NAME?>"  readonly>
            <td nowrap class="TableData"><?=_("培训计划名称：")?></td>
            <td class="TableData" >
                <input type="hidden" name="T_PLAN_NO" value="">
                <INPUT type="text"name="T_PLAN_NAME" class=BigStatic size="20"  value="<?=$T_PLAN_NAME?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("培训机构：")?></td>
            <td class="TableData" >
                <input type="text" name="T_INSTITUTION_NAME" size="30" class="BigInput" value="<?=$T_INSTITUTION_NAME?>">
            </td>
            <td nowrap class="TableData"><?=_("培训费用：")?></td>
            <td class="TableData">
                <input type="text" name="TRAINNING_COST" size="10" class="BigInput" value="<?=$TRAINNING_COST?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("培训考核成绩：")?></td>
            <td class="TableData">
                <input type="text" name="T_EXAM_RESULTS" size="10" class="BigInput" value="<?=$T_EXAM_RESULTS?>">
            </td>
            <td nowrap class="TableData"><?=_("培训考核等级：")?></td>
            <td class="TableData">
                <input type="text" name="T_EXAM_LEVEL" size="10" class="BigInput" value="<?=$T_EXAM_LEVEL?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("出勤情况：")?></td>
            <td class="TableData" colspan=3>
                <textarea name="DUTY_SITUATION" cols="66" rows="5" class="BigInput" value=""><?=$DUTY_SITUATION?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("总结完成情况：")?></td>
            <td class="TableData" colspan=3>
                <textarea name="TRAINNING_SITUATION" cols="66" rows="5" class="BigInput" value=""><?=$TRAINNING_SITUATION?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("评论：")?></td>
            <td class="TableData" colspan=3>
                <textarea name="T_COMMENT" cols="66" rows="5" class="BigInput" value=""><?=$T_COMMENT?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("备注：")?></td>
            <td class="TableData" colspan=3>
                <textarea name="REMARK" cols="66" rows="5" class="BigInput" value=""><?=$REMARK?></textarea>
            </td>
        </tr>
        <tr class="TableData" id="attachment2">
            <td nowrap><?=_("附件文档：")?></td>
            <td nowrap colspan=3>
                <?
                if($ATTACHMENT_ID=="")
                    echo _("无附件");
                else
                    echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,0,1,1);
                ?>
            </td>
        </tr>
        <tr height="25" id="attachment1">
            <td nowrap class="TableData"><span id="ATTACH_LABEL"><?=_("附件上传：")?></span></td>
            <td class="TableData" colspan=3>
                <script>ShowAddFile();</script>
                <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
                <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
            </td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan=4 nowrap>
                <input type="hidden" name="RECORD_ID"  value="<?=$RECORD_ID?>">
                <input type="submit" value="<?=_("保存")?>" class="BigButton">
            </td>
        </tr>
    </table>
</form>
</body>
</html>