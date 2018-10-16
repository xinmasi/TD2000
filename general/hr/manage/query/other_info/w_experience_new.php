<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");
include_once("inc/editor.php");

$HTML_PAGE_TITLE = _("�½�����������Ϣ");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm()
{
    if(document.form1.STAFF_NAME.value=="")
    {
        alert("<?=_("Ա����������Ϊ�գ�")?>");
        return (false);
    }
    if(document.form1.START_DATE.value!="" && document.form1.END_DATE.value!="" && document.form1.START_DATE.value >= document.form1.END_DATE.value)
    {
        alert("<?=_("������ʼ���ڲ���С�ڹ����������ڣ�")?>");
        return (false);
    }
    return (true);
}
function upload_attach()
{
    if(CheckForm())
    {
        document.form1.submit();
    }
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
    var msg = sprintf("<?=_("ȷ��Ҫɾ���ļ� '%s' ��")?>", ATTACHMENT_NAME);
    if(window.confirm(msg))
    {
        URL="delete_attach.php?W_EXPERIENCE_ID=<?=$W_EXPERIENCE_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
        window.location=URL;
    }
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½�����������Ϣ")?></span>&nbsp;&nbsp;
        </td>
    </tr>
</table>
<br>
<form enctype="multipart/form-data" action="w_experience_add.php"  method="post" name="form1" onSubmit="return CheckForm();">
    <table class="TableBlock" width="80%" align="center">
        <tr>
            <td nowrap class="TableData"><?=_("��λԱ����")?></td>
            <td class="TableData">
                <?
                $STAFF_NAME =$USER_ID;
                $STAFF_NAME1 = substr( getUserNameById($USER_ID), 0, -1);
                ?>
                <input type="text" name="STAFF_NAME1" size="15" class="BigStatic" readonly value="<?=_("$STAFF_NAME1")?>">&nbsp;
                <input type="hidden" name="STAFF_NAME" value="<?=$STAFF_NAME?>">
                <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','STAFF_NAME', 'STAFF_NAME1','1')"><?=_("ѡ��")?></a>
            </td>
            <td nowrap class="TableData"><?=_("����ְ��")?></td>
            <td class="TableData"><INPUT type="text"name="POST_OF_JOB" class=BigInput size="15" value="<?=$POST_OF_JOB?>"></td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("���ڲ��ţ�")?>
            </td><td class="TableData"><INPUT type="text"name="WORK_BRANCH" class=BigInput size="15" value="<?=$WORK_BRANCH?>"></td>
            <td nowrap class="TableData"><?=_("֤���ˣ�")?></td>
            <td class="TableData"><INPUT type="text"name="WITNESS" class=BigInput size="15" value="<?=$WITNESS?>"></td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("��ʼ���ڣ�")?></td>
            <td class="TableData"><input type="text" name="START_DATE" size="15" maxlength="10" class="BigInput" value="<?=$START_DATE?>" onClick="WdatePicker()"/></td>
            <td nowrap class="TableData"><?=_("�������ڣ�")?></td>
            <td class="TableData"><input type="text" name="END_DATE" size="15" maxlength="10" class="BigInput" value="<?=$END_DATE?>" onClick="WdatePicker()"/></td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("��ҵ���")?></td>
            <td class="TableData" colspan=3><textarea name="MOBILE" cols="78" rows="3" class="BigInput" value=""><?=$MOBILE?></textarea></td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("������λ��")?></td>
            <td class="TableData" colspan=3><textarea name="WORK_UNIT" cols="78" rows="3" class="BigInput" value=""><?=$WORK_UNIT?></textarea></td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("�������ݣ�")?></td>
            <td class="TableData" colspan=3><textarea name="WORK_CONTENT" cols="78" rows="3" class="BigInput" value=""><?=$WORK_CONTENT?></textarea></td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("��ְԭ��")?></td>
            <td class="TableData" colspan=3><textarea name="REASON_FOR_LEAVING" cols="78" rows="3" class="BigInput" value=""><?=$REASON_FOR_LEAVING?></textarea></td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("��ע��")?></td>
            <td class="TableData" colspan=3><textarea name="REMARK" cols="78" rows="3" class="BigInput" value=""><?=$REMARK?></textarea></td>
        </tr>
        <tr height="25" id="attachment1">
            <td nowrap class="TableData" ><span id="ATTACH_LABEL"><?=_("�����ϴ���")?></span></td>
            <td class="TableData"colspan=3>
                <script>ShowAddFile();</script>
                <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
                <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
            </td>
        </tr>
        <tr id="EDITOR">
            <td class="TableData" colspan="4"> <?=_("��Ҫҵ����")?>
                <?
                $editor = new Editor('KEY_PERFORMANCE') ;
                $editor->Height = '300';
                $editor->Config = array("EditorAreaStyles" => "body{font-size:20pt;}","model_type" => "14");
                $editor->Value = $KEY_PERFORMANCE ;
                //$editor->Config = array('model_type' => '14') ;
                $editor->Create() ;
                ?>
            </td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan=4 nowrap>
                <input type="submit" value="<?=_("����")?>" class="BigButton">
            </td>
        </tr>
    </table>
</form>
</body>
</html>