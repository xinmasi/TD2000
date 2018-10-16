<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/editor.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("�½�����ϵ");
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
    if(document.form1.MEMBER.value=="")
    {
        alert("<?=_("��Ա��������Ϊ�գ�")?>");
        return (false);
    }
    if(document.form1.RELATIONSHIP.value=="")
    {
        alert("<?=_("��ѡ���뱾�˹�ϵ��")?>");
        return (false);
    }
    if(document.form1.WORK_UNIT.value=="")
    {
        alert("<?=_("������λ����Ϊ�գ�")?>");
        return (false);
    }
    if(document.form1.UNIT_ADDRESS.value=="")
    {
        alert("<?=_("��λ��ַ����Ϊ�գ�")?>");
        return (false);
    }
    if(document.form1.PERSONAL_TEL.value=="" && document.form1.OFFICE_TEL.value=="" && document.form1.HOME_TEL.value=="")
    {
        alert("<?=_("���ˡ���λ�ͼ�ͥ����ϵ�绰����ȫΪ�գ�")?>");
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
        URL="delete_attach.php?REINSTATEMENT_ID=<?=$REINSTATEMENT_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
        window.location=URL;
    }
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½�����ϵ")?></span>&nbsp;&nbsp;
        </td>
    </tr>
</table>
<br>
<form enctype="multipart/form-data" action="relatives_add.php"  method="post" name="form1" onsubmit="return CheckForm();">
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
            <td nowrap class="TableData"><?=_("��Ա������")?></td>
            <td class="TableData">
                <INPUT type="text"name="MEMBER" class=BigInput size="15" value="<?=$MEMBER?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("�뱾�˹�ϵ��")?></td>
            <td class="TableData" >
                <select name="RELATIONSHIP" style="background: white;" title="<?=_("�뱾�˹�ϵ���ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
                    <option value=""><?=_("�뱾�˹�ϵ")?>&nbsp&nbsp&nbsp</option>
                    <?=hrms_code_list("HR_STAFF_RELATIVES","")?>
                </select>
            </td>
            <td nowrap class="TableData"><?=_("�������ڣ�")?></td>
            <td class="TableData">
                <input type="text" name="BIRTHDAY" size="15" maxlength="10" class="BigInput" value="<?=$BIRTHDAY?>" onClick="WdatePicker()"/>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("������ò��")?></td>
            <td class="TableData" >
                <select name="POLITICS" style="background: white;" title="<?=_("������ò�ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
                    <option value=""><?=_("������ò")?>&nbsp&nbsp&nbsp&nbsp&nbsp</option>
                    <?=hrms_code_list("STAFF_POLITICAL_STATUS",$POLITICS)?>
                </select>
            </td>
            <td nowrap class="TableData"> <?=_("ְҵ��")?></td>
            <td class="TableData">
                <INPUT type="text"name="JOB_OCCUPATION" class=BigInput size="15" value="<?=$JOB_OCCUPATION?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("����ְ��")?></td>
            <td class="TableData">
                <INPUT type="text"name="POST_OF_JOB" class=BigInput size="15" value="<?=$POST_OF_JOB?>">
            </td>
            <td nowrap class="TableData"> <?=_("��ϵ�绰�����ˣ���")?></td>
            <td class="TableData">
                <INPUT type="text"name="PERSONAL_TEL" class=BigInput size="15" value="<?=$PERSONAL_TEL?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("��ϵ�绰����ͥ����")?></td>
            <td class="TableData">
                <INPUT type="text"name="HOME_TEL" class=BigInput size="15" value="<?=$HOME_TEL?>">
            </td>
            <td nowrap class="TableData"> <?=_("��ϵ�绰����λ����")?></td>
            <td class="TableData">
                <INPUT type="text"name="OFFICE_TEL" class=BigInput size="15" value="<?=$OFFICE_TEL?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("������λ��")?></td>
            <td class="TableData" colspan=3>
                <textarea name="WORK_UNIT" cols="78" rows="2" class="BigInput" value=""><?=$WORK_UNIT?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("��λ��ַ��")?></td>
            <td class="TableData" colspan=3>
                <textarea name="UNIT_ADDRESS" cols="78" rows="2" class="BigInput" value=""><?=$UNIT_ADDRESS?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("��ͥסַ��")?></td>
            <td class="TableData" colspan=3>
                <textarea name="HOME_ADDRESS" cols="78" rows="2" class="BigInput" value=""><?=$HOME_ADDRESS?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("��ע��")?></td>
            <td class="TableData" colspan=3>
                <textarea name="REMARK" cols="78" rows="2" class="BigInput" value=""><?=$REMARK?></textarea>
            </td>
        </tr>
        <tr height="25" id="attachment1">
            <td nowrap class="TableData" ><span id="ATTACH_LABEL"><?=_("�����ϴ���")?></span></td>
            <td class="TableData"colspan=3>
                <script>ShowAddFile();</script>
                <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
                <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
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