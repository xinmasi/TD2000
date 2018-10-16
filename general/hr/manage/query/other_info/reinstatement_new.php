<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/editor.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("�½�Ա����ְ��Ϣ");
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
    if(document.form1.REINSTATEMENT_PERSON.value=="")
    {
        alert("<?=_("��ѡ��ְ��Ա��")?>");
        return (false);
    }
    if(document.form1.REAPPOINTMENT_DEPT.value=="")
    {
        alert("<?=_("��ѡ��ְ���ţ�")?>");
        return (false);
    }
    if(getEditorHtml('REAPPOINTMENT_STATE') == "" && getEditorText('REAPPOINTMENT_STATE').length == 0)
    {
        alert("<?=_("��ְ˵������Ϊ�գ�")?>");
        return (false);
    }
    if(document.form1.FIRST_SALARY_TIME.value!="" && document.form1.REAPPOINTMENT_TIME_FACT.value!="" && document.form1.REAPPOINTMENT_TIME_FACT.value >= document.form1.FIRST_SALARY_TIME.value)
    {
        alert("<?=_("���ʻָ����ڲ���С��ʵ�ʸ�ְ���ڣ�")?>");
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
function selectuser(FUNC_ID,MODULE_ID,TO_ID, TO_NAME, MANAGE_FLAG, FORM_NAME)
{
    URL="user_select_single_reinstatement?FUNC_ID="+FUNC_ID+"&MODULE_ID="+MODULE_ID+"&TO_ID="+TO_ID+"&TO_NAME="+TO_NAME+"&MANAGE_FLAG="+MANAGE_FLAG+"&FORM_NAME="+FORM_NAME;
    loc_y=loc_x=200;
    if(is_ie)
    {
        loc_x=document.body.scrollLeft+event.clientX-100;
        loc_y=document.body.scrollTop+event.clientY+170;
    }
    LoadDialogWindow(URL,self,loc_x, loc_y, 500, 350);
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½�Ա����ְ��Ϣ")?></span>&nbsp;&nbsp;
        </td>
    </tr>
</table>
<br>
<form enctype="multipart/form-data" action="reinstatement_add.php"  method="post" name="form1" onSubmit="return CheckForm();">
    <table class="TableBlock" width="80%" align="center">
        <tr>
            <td nowrap class="TableData"><?=_("��ְ��Ա��")?></td>
            <td class="TableData">
                <?
                $REINSTATEMENT_PERSON =$USER_ID;
                $REINSTATEMENT_PERSON_NAME = substr( getUserNameById($USER_ID), 0, -1);
                ?>
                <input type="text" name="REINSTATEMENT_PERSON_NAME" size="15" class="BigStatic" readonly value="<?=_("$REINSTATEMENT_PERSON_NAME")?>">&nbsp;
                <input type="hidden" name="REINSTATEMENT_PERSON" value="<?=$REINSTATEMENT_PERSON?>">
                <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','REINSTATEMENT_PERSON', 'REINSTATEMENT_PERSON_NAME')"><?=_("ѡ��")?></a>
            </td>
            <td nowrap class="TableData"> <?=_("��ְ���ͣ�")?></td>
            <td class="TableData" >
                <select name="REAPPOINTMENT_TYPE" style="background: white;" title="<?=_("��ְ���Ϳ��ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
                    <option value=""><?=_("��ְ����")?>&nbsp&nbsp&nbsp&nbsp&nbsp;</option>
                    <?=hrms_code_list("HR_STAFF_REINSTATEMENT","")?>
                </select>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("�������ڣ�")?></td>
            <td class="TableData">
                <input type="text" name="APPLICATION_DATE" size="15" maxlength="10" class="BigInput" value="<?=$APPLICATION_DATE?>" onClick="WdatePicker()"/>
            </td>
            <td nowrap class="TableData"><?=_("����ְ��")?></td>
            <td class="TableData">
                <INPUT type="text"name="NOW_POSITION" class=BigInput size="15" value="<?=$NOW_POSITION?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("�⸴ְ���ڣ�")?></td>
            <td class="TableData">
                <input type="text" name="REAPPOINTMENT_TIME_PLAN" size="15" maxlength="10" class="BigInput" value="<?=$REAPPOINTMENT_TIME_PLAN?>" onClick="WdatePicker()"/>
            </td>
            <td nowrap class="TableData"> <?=_("ʵ�ʸ�ְ���ڣ�")?></td>
            <td class="TableData">
                <input type="text" name="REAPPOINTMENT_TIME_FACT" size="15" maxlength="10" class="BigInput" value="<?=$REAPPOINTMENT_TIME_FACT?>" onClick="WdatePicker()"/>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("���ʻָ����ڣ�")?></td>
            <td class="TableData">
                <input type="text" name="FIRST_SALARY_TIME" size="15" maxlength="10" class="BigInput" value="<?=$FIRST_SALARY_TIME?>" onClick="WdatePicker()"/>
            </td>
            <td nowrap class="TableData"><?=_("��ְ���ţ�")?></td>
            <td class="TableData" colspan=3>
                <input type="hidden" name="REAPPOINTMENT_DEPT">
                <input type="text" name="REAPPOINTMENT_DEPT_NAME" value="" class=BigStatic size=15 maxlength=100 readonly>
                <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','REAPPOINTMENT_DEPT','REAPPOINTMENT_DEPT_NAME')"><?=_("ѡ��")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("��ְ��������")?></td>
            <td class="TableData" colspan=3>
                <textarea name="MATERIALS_CONDITION" cols="70" rows="3" class="BigInput" value=""><?=$MATERIALS_CONDITION?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("��ע��")?></td>
            <td class="TableData" colspan=3>
                <textarea name="REMARK" cols="70" rows="3" class="BigInput" value=""><?=$REMARK?></textarea>
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
        <tr id="EDITOR">
            <td class="TableData" colspan="4"> <?=_("��ְ˵����")?>
                <?
                $editor = new Editor('REAPPOINTMENT_STATE') ;
                $editor->Height = '200';
                $editor->Config = array("EditorAreaStyles" => "body{font-size:20pt;}","model_type" => "14");
                $editor->Value = $REAPPOINTMENT_STATE ;
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