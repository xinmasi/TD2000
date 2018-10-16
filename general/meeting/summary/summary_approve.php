<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("会议纪要");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm(type)
{
	if(type == 'approve')
	{	
		if(getEditorHtml('SUMMARY')=="")
	    {
	        alert("<?=_("请填写会议纪要！")?>");
	        return (false);
	    }
		document.form1.action="summary_submit.php?approve_type=2";
	}else
	{
		document.form1.action="summary_submit.php?approve_type=3";
	}
    document.form1.submit();
}

function InsertImage(src)
{
    AddImage2Editor('SUMMARY', src);
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
    var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
    if(window.confirm(msg))
    {
        URL="delete_attach.php?M_ID=<?=$M_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
        window.location=URL;
    }
}
</script>

<body class="bodycolor" >
    <table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
        <tr>
            <td class="Big">
                <img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" width="22" height="18">
                <span class="big3"> <?=_("会议纪要")?></span>
            </td>
        </tr>
    </table>
<br>

<?
$sql = "SELECT MEETING_OPERATOR from meeting_rule";
$result = exequery(TD::conn(),$sql);
while($ROW1 = mysql_fetch_array($result))
{
	$USER_ID .=$ROW1['MEETING_OPERATOR'].",";
}
$USER_ID = rtrim($USER_ID,",").',';
$query = "SELECT * from MEETING where M_ID='$M_ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
    $M_NAME             = $ROW["M_NAME"];
    $SUMMARY            = $ROW["SUMMARY"];
    $READ_PEOPLE_ID     = $ROW["READ_PEOPLE_ID"];
    $ATTACHMENT_ID      = $ROW["ATTACHMENT_ID1"];
    $ATTACHMENT_NAME    = $ROW["ATTACHMENT_NAME1"];
    $M_FACT_ATTENDEE    = $ROW["M_FACT_ATTENDEE"];
    $SUMMARY_STATUS     = $ROW["SUMMARY_STATUS"];
    $USER_ID .= $ROW["M_MANAGER"].",";
}

$TOK = strtok($READ_PEOPLE_ID,",");
while($TOK != "")
{
    $query2 = "SELECT * from USER where USER_ID='$TOK'";
    $cursor2 = exequery(TD::conn(),$query2);
    if($ROW = mysql_fetch_array($cursor2))
    {
        $USER_NAME2 .= $ROW["USER_NAME"].",";
    }
    
    $TOK = strtok(",");
}

$TOK2 = strtok($M_FACT_ATTENDEE,",");
while($TOK2 != "")
{
    $query3 = "SELECT * from USER where USER_ID='$TOK2'";
    $cursor3 = exequery(TD::conn(),$query3);
    if($ROW = mysql_fetch_array($cursor3))
    {
        $M_FACT_ATTENDEE_NAME .= $ROW["USER_NAME"].",";
    }
    
    $TOK2=strtok(",");
}
?>

<form enctype="multipart/form-data"  method="post" name="form1" >
<table class="TableBlock" width="90%" align="center">
    <tr>
        <td nowrap class="TableContent" width="80"><?=_("会议名称：")?></td>
        <td class="TableData" colspan="3">
            <input type="text" name="M_NAME" size="40" maxlength="100" class="BigStatic" readonly value="<?=$M_NAME?>">
        </td>
    </tr>
    <tr>
        <td nowrap class="TableContent" width="80"><?=_("指定读者：")?></td>
        <td class="TableData" colspan="3">
            <input type="hidden" name="COPY_TO_ID" value="<?=$READ_PEOPLE_ID?>">
            <textarea name="COPY_TO_NAME" class="BigStatic" cols="50" rows="3" class="BigStatic" wrap="yes" readonly><?=$USER_NAME2?></textarea>
            <a href="javascript:;" class="orgAdd" onClick="SelectUser('138','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("添加")?></a>
            <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableContent" width="80"><?=_("实际参会人员：")?></td>
        <td class="TableData" colspan="3">
            <input type="hidden" name="M_FACT_ATTENDEE_ID" value="<?=$M_FACT_ATTENDEE?>">
            <textarea name="M_FACT_ATTENDEE_NAME" class="BigStatic" cols="50" rows="3" class="BigStatic" wrap="yes" readonly><?=$M_FACT_ATTENDEE_NAME?></textarea>
            <a href="javascript:;" class="orgAdd" onClick="SelectUser('138','','M_FACT_ATTENDEE_ID', 'M_FACT_ATTENDEE_NAME')"><?=_("添加")?></a>
            <a href="javascript:;" class="orgClear" onClick="ClearUser('M_FACT_ATTENDEE_ID', 'M_FACT_ATTENDEE_NAME')"><?=_("清空")?></a>
        </td>
    </tr>
    <tr>
        <td valign="top" nowrap class="TableContent" width="80"><?=_("纪要内容：")?></td>
        <td class="TableData" colspan="3">
<?
$editor = new Editor('SUMMARY') ;
$editor->Height = '280';
$editor->Config = array('model_type' => '08');
$editor->ToolbarSet = 'Basic' ;
$editor->Value = $SUMMARY ;
$editor->Create() ;
?>
        </td>
    </tr>
<?
if($ATTACHMENT_ID!="")
{
?>
    <tr>
        <td class="TableContent"><?=_("附件文件")?>:</td><td class="TableData"><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1)?></td>
    </tr>
<?
}
?>
    <tr height="25" id="attachment1">
        <td nowrap class="TableContent"><span id="ATTACH_LABEL"><?=_("附件上传：")?></span></td>
        <td class="TableData">
            <script>ShowAddFile();</script>
            <!-------------
            <script>$("ATTACHMENT_upload_div").innerHTML='<a href="javascript:upload_attach();">'+<?=_("上传附件")?>+'</a>'</script>
            !------>
            <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
            <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="80"><?=_("提醒参会人员与指定读者：")?></td>
        <td class="TableData" colspan="3">
            <?=sms_remind(8);?>
        </td>
    </tr>
    <tr class="TableControl">
        <td align="center" colspan="4">
            <input type="hidden" value="<?=$M_ID?>" name="M_ID">
            <input type="hidden" value="<?=rtrim($USER_ID,',')?>" name="USER_ID">
            <input type="button" onclick="CheckForm('approve')" value="<?=_("批准")?>" class="BigButton" title="<?=_("审批会议纪要")?>">&nbsp;
            <input type="button" onclick="CheckForm('back')" value="<?=_("驳回")?>" class="BigButton" title="<?=_("审批会议纪要")?>">&nbsp;
            <input type="button" class="BigButton" value="<?=_("关闭")?>" onClick="window.close()">
        </td>
    </tr>
</table>
</form>

</body>
</html>
