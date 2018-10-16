<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("会议纪要");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="/static/js/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.min.css">


<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm(type)
{
	document.form1.savetype.value = type;
	if(type == 'save')
	{
		if(getEditorHtml('SUMMARY')=="")
        { 
             alert("<?=_("请填写会议纪要！")?>");
             return false;
        }
        document.form1.action="summary_submit.php?approve_type=2&fromwhere=1";
         
	}else if(type =='approve')
    {
		if(document.form1.APPROVE_NAME.value=="")
		{
            alert("<?=_("请选择审批人！")?>");
            return (false);
		}
		if(getEditorHtml('SUMMARY')=="")
        { 
            alert("<?=_("请填写会议纪要！")?>");
            return (false);
        }
		document.form1.action="summary_submit.php?approve_type=2&fromwhere=1";
	}else if(type =='publish'){
        if(getEditorHtml('SUMMARY')=="")
        {
            alert("<?=_("请填写会议纪要！")?>");
            return (false);
        }
        document.form1.action="summary_submit.php?approve_type=2&fromwhere=1";
    }else
	{
		var MEETING_OPERATOR = document.getElementById('MEETING_OPERATOR').value;
		document.form1.action="summary_submit.php?approve_type=4&fromwhere=1";
		if(MEETING_OPERATOR == '')
		{
			alert('请先设置会议管理员再提交审批！');
			document.form1.action="summary_submit.php?approve_type=0&fromwhere=1";
		}
	}
	//alert(document.form1.action);
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
function showModal(){
	$('#myModal').modal('show');
}
function close_this_new()
{
  TJF_window_close();
}
</script>
<style>
<!--弹框-->
element.style 
{
    display: block;
}
.modal.fade.in 
{
    top: 10%;
}
.fade.in 
{
    opacity: 1;
}
.modal.fade 
{
    webkit-transition: opacity .3s linear,top .3s ease-out;
    moz-transition: opacity .3s linear,top .3s ease-out;
    o-transition: opacity .3s linear,top .3s ease-out;
    transition: opacity .3s linear,top .3s ease-out;
    top: -25%;
}
.fade 
{
    opacity: 0;
    webkit-transition: opacity 0.15s linear;
    moz-transition: opacity 0.15s linear;
    o-transition: opacity 0.15s linear;
    transition: opacity 0.15s linear;
}
.hide {
    display: none;
}
.modal 
{
    position: fixed;
    top: 10%;
    left: 50%;
    z-index: 1050;
    width: 560px;
    margin-left: -280px;
    background-color: #ffffff;
    border: 1px solid #999;
    border: 1px solid rgba(0,0,0,0.3);
    webkit-border-radius: 6px;
    moz-border-radius: 6px;
    border-radius: 6px;
    webkit-box-shadow: 0 3px 7px rgba(0,0,0,0.3);
    moz-box-shadow: 0 3px 7px rgba(0,0,0,0.3);
    box-shadow: 0 3px 7px rgba(0,0,0,0.3);
    webkit-background-clip: padding-box;
    moz-background-clip: padding-box;
    background-clip: padding-box;
    outline: none;
}
user agent stylesheetdiv 
{
    display: block;
}
Inherited from body.bodycolor
body 
{
    margin: 0;
    font-family: Simsun,Arial,sans-serif;
    font-size: 13px;
    line-height: 20px;
    color: #333333;
    background-color: #ffffff;
}
body, button, input, select, textarea 
{
    color: #393939;
}
Inherited from html
html 
{
    font-size: 100%;
    webkit-text-size-adjust: 100%;
    ms-text-size-adjust: 100%;
}
</style>
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
	$MEETING_OPERATOR = explode(',', $ROW1['MEETING_OPERATOR']);
	$MEETING_OPERATOR = $MEETING_OPERATOR[0];
}
$USER_ID = rtrim($USER_ID,",").',';
$query = "SELECT * from MEETING where M_ID='$M_ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
    $M_NAME             = $ROW["M_NAME"];
	$APPROVE_NAME       = $ROW["APPROVE_NAME"];//审批人员
    $SUMMARY            = $ROW["SUMMARY"];
    $READ_PEOPLE_ID     = $ROW["READ_PEOPLE_ID"];//指定读者 
    $ATTACHMENT_ID      = $ROW["ATTACHMENT_ID1"];
    $ATTACHMENT_NAME    = $ROW["ATTACHMENT_NAME1"];
    $M_FACT_ATTENDEE    = $ROW["M_FACT_ATTENDEE"];
    $SUMMARY_STATUS     = $ROW["SUMMARY_STATUS"];
	$RECORDER     		= $ROW["RECORDER"];
	$M_STATUS     		= $ROW["M_STATUS"];
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

<form enctype="multipart/form-data"  action="summary_submit.php?" method="post" name="form1" >
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
            <textarea name="COPY_TO_NAME" class="BigStatic" cols="50" rows="3" style="width:400px; height:70px; class="BigStatic" wrap="yes" readonly><?=$USER_NAME2?></textarea>
            <a href="javascript:;" class="orgAdd" onClick="SelectUser('138','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("添加")?></a>
            <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableContent" width="80"><?=_("实际参会人员：")?></td>
        <td class="TableData" colspan="3">
            <input type="hidden" name="M_FACT_ATTENDEE_ID" value="<?=$M_FACT_ATTENDEE?>">
            <textarea name="M_FACT_ATTENDEE_NAME" class="BigStatic" cols="50" rows="3" style="width:400px; height:70px; class="BigStatic" wrap="yes" readonly><?=$M_FACT_ATTENDEE_NAME?></textarea>
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
	<?  
		$query6 = "SELECT * from USER where USER_ID='$APPROVE_NAME'";
		$cursor6 = exequery(TD::conn(),$query6);
		if($ROW = mysql_fetch_array($cursor6))
		{
			$APPROVE_NAME2 = $ROW["USER_NAME"];
		}
    //0 不需要审核 1 需要审核
    $SUMMARY_APPROVE_ARRAY = get_sys_para("SUMMARY_APPROVE",false);
    if($SUMMARY_APPROVE_ARRAY["SUMMARY_APPROVE"] == "1"){
	?>
    <tr>
        <td nowrap class="TableData" width="80"><?=_("选择审批人：")?></td>
        <td class="TableData" colspan="3">
			<input type="text" name="APPROVE_NAME1" size="15" class="BigStatic" readonly disabled="disabled" value="<?=$APPROVE_NAME2?>">&nbsp;
			<input type="hidden" name="APPROVE_NAME" value="<?=$APPROVE_NAME?>">
			<a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','APPROVE_NAME', 'APPROVE_NAME1','1')"><?=_("选择")?></a>
        </td>
    </tr>
	<tr>
        <td nowrap class="TableData" width="80"><?=_("提醒审批人员：")?></td>
        <td class="TableData" colspan="3">
            <?=sms_remind(8);?>
        </td>
    </tr>
    <?}?>
    <tr class="TableControl">
        <td align="center" colspan="4">
            <input type="hidden" value="<?=$M_ID?>" name="M_ID">
            <input type="hidden" value="<?=$MEETING_OPERATOR?>" id='MEETING_OPERATOR' name="MEETING_OPERATOR">
			<input type="hidden" value="<?=$STAFF_NAME?>" id='$STAFF_NAME' name="STAFF_NAME">
            <input type="hidden" value="<?=rtrim($USER_ID,',')?>" name="USER_ID">
            <?
            if($SUMMARY_APPROVE_ARRAY["SUMMARY_APPROVE"] == "1") {
                if ($M_STATUS == 4 && ($SUMMARY_STATUS == 3 || $SUMMARY_STATUS == 0) || $_SESSION['LOGIN_USER_ID'] == 'admin') {
                    ?>
                    <input name="approval" type="button" onclick="CheckForm('approve')" value="<?= _("提交审批") ?>"
                           class="BigButton" title="<?= _("审批会议纪要") ?>">&nbsp;
                <? }
            }else{
                ?>
                <input name="publish" type="button" onclick="CheckForm('publish')" class="BigButton" value="<?=_("发布")?>">&nbsp;
                <?
            }
            if($SUMMARY_STATUS == 0 || $SUMMARY_STATUS == 3)
            {
            ?>
            <input name="cache" type="button" onclick="CheckForm('save')" class="BigButton" value="<?=_("暂存")?>">&nbsp;
            <? }?>
            <input type="button" class="BigButton" value="<?=_("关闭")?>" onclick="close_this_new();"">
			<?
			if($SUMMARY_STATUS == 3)
			{
			?>
			<input type="button" value="<?=_("驳回信息")?>" class="BigButton" onclick="showModal();" />
			<?	
			}
			?>
			<input type="hidden" name="savetype" value="">
        </td>
    </tr>
</table>
</form>
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel"><?= _("查看驳回信息");?></h3>
    </div>
	<div class="modal-body">
		<?
			$query = "SELECT * from MEETING_REFUSAL where MEETING_ID='$M_ID'";
			$cursor= exequery(TD::conn(),$query);
			while($ROW=mysql_fetch_array($cursor))
			{
		?>
		<?=$ROW[R_TIME];?>
		<textarea name="reject_content" id="CONTENT" style="width:97%; height:20px; resize:none;"disabled="disabled">驳回原因:<?=$ROW[R_CONTENT];?></textarea>
		<?
			}
		?>
	</div>
	<div class="modal-footer">
		<input type="hidden" value="<?=$M_ID?>" name="M_ID">	
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?= _("关闭")?></button>
	</div>
</div>
</body>
</html>
