<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");
$STAFF_NAME1=$STAFF_NAME;
$STAFF_NAME=GetUserNameById($STAFF_NAME);

$HTML_PAGE_TITLE = _("�½�ѧϰ������Ϣ");
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
    if(document.form1.START_DATE.value!="" && document.form1.END_DATE.value!="" && document.form1.START_DATE.value > document.form1.END_DATE.value)
    {
        alert("<?=_("�������ڲ���С�ڿ�ʼ���ڣ�")?>");
        return (false);
    }
    if(document.getElementById("academy_degree").value=="")
    {
        alert("<?=_("ѧ������Ϊ�գ�")?>");
        return(false);
    }
    if(document.getElementById("degree").value=="")
    {
        alert("<?=_("ѧλ����Ϊ�գ�")?>");
        return(false);
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
        URL="delete_attach.php?L_EXPERIENCE_ID=<?=$L_EXPERIENCE_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
        window.location=URL;
    }
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½�ѧϰ������Ϣ")?></span>&nbsp;&nbsp;
        </td>
    </tr>
</table>
<br>
<form enctype="multipart/form-data" action="experience_add.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <table class="TableBlock" width="80%" align="center">
        <tr>
            <td nowrap class="TableData"><?=_("��λԱ����")?></td>
            <td class="TableData">
                <?
                $STAFF_NAME =$USER_ID;
                $STAFF_NAME1 = substr( getUserNameById($USER_ID), 0, -1);
                ?>
                <input type="text" name="STAFF_NAME1" value="<?=_("$STAFF_NAME1")?>" size="15" class="BigStatic" readonly>&nbsp;
                <input type="hidden" name="STAFF_NAME" value="<?=$STAFF_NAME?>">
                <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','STAFF_NAME', 'STAFF_NAME1','1')"><?=_("ѡ��")?></a>
            </td>
            <td nowrap class="TableData"><?=_("��ѧרҵ��")?></td>
            <td class="TableData">
                <INPUT type="text"name="MAJOR" class=BigInput size="15" value="<?=$MAJOR?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("��ʼ���ڣ�")?></td>
            <td class="TableData">
                <input type="text" name="START_DATE" size="15" maxlength="10" class="BigInput" value="<?=$START_DATE?>" onClick="WdatePicker()"/>
            </td>
            <td nowrap class="TableData"><?=_("�������ڣ�")?></td>
            <td class="TableData">
                <input type="text" name="END_DATE" size="15" maxlength="10" class="BigInput" value="<?=$END_DATE?>" onClick="WdatePicker()"/>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("����ѧ����")?></td>
            <td class="TableData">
                <!--   <INPUT type="text"name="ACADEMY_DEGREE" class=BigInput size="15" value="<?=$ACADEMY_DEGREE?>">-->
                <select name="ACADEMY_DEGREE" class="BigSelect" id="academy_degree" title="<?=_("��ְ״̬���ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
                    <option value="" <? if($ACADEMY_DEGREE=="") echo "selected";?>></option>
                    <?=hrms_code_list("STAFF_HIGHEST_SCHOOL",$ACADEMY_DEGREE);?>
                </select>
            </td>
            <td nowrap class="TableData"><?=_("����ѧλ��")?></td>
            <td class="TableData">
                <!--<INPUT type="text"name="DEGREE" class=BigInput size="15" value="<?=$DEGREE?>">-->
                <select name="DEGREE" class="BigSelect" id="degree" title="<?=_("��ְ״̬���ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
                    <option value="" <? if($DEGREE=="") echo "selected";?>></option>
                    <?=hrms_code_list("EMPLOYEE_HIGHEST_DEGREE",$DEGREE);?>
                </select>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("���ΰ�ɣ�")?></td>
            <td class="TableData">
                <INPUT type="text"name="POSITION" class=BigInput size="15" value="<?=$POSITION?>">
            </td>
            <td nowrap class="TableData"><?=_("֤���ˣ�")?></td>
            <td class="TableData">
                <INPUT type="text"name="WITNESS" class=BigInput size="15" value="<?=$WITNESS?>">
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("����ԺУ��")?></td>
            <td class="TableData" colspan=3>
                <textarea name="SCHOOL" cols="78" rows="3" class="BigInput" value=""><?=$SCHOOL?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("ԺУ���ڵأ�")?></td>
            <td class="TableData" colspan=3>
                <textarea name="SCHOOL_ADDRESS" cols="78" rows="3" class="BigInput" value=""><?=$SCHOOL_ADDRESS?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("�������")?></td>
            <td class="TableData" colspan=3>
                <textarea name="AWARDING" cols="78" rows="3" class="BigInput" value=""><?=$AWARDING?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("����֤�飺")?></td>
            <td class="TableData" colspan=3>
                <textarea name="CERTIFICATES" cols="78" rows="3" class="BigInput" value=""><?=$CERTIFICATES?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("��ע��")?></td>
            <td class="TableData" colspan=3>
                <textarea name="REMARK" cols="78" rows="3" class="BigInput" value=""><?=$REMARK?></textarea>
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