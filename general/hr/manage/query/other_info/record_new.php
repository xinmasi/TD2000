<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");
$HTML_PAGE_TITLE = _("������ѵ��¼");
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
    if(document.form1.T_JOIN_PERSON.value == "")
    {
        alert("<?=_("��ѵ�˲���Ϊ��")?>");
        return false;
    }
    if(document.form1.T_PLAN_NAME.value == "")
    {
        alert("<?=_("��ѵ�ƻ����Ʋ���Ϊ��")?>");
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
<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("������ѵ��¼")?></span>&nbsp;&nbsp;
        </td>
    </tr>
</table>
<br>
<form enctype="multipart/form-data" action="record_add.php"  method="post" name="form1" onSubmit="return CheckForm();">
    <table class="TableBlock" width="60%" align="center">
        <tr>
            <td nowrap class="TableData"><?=_("��ѵ�ƻ����ƣ�")?></td>
            <td class="TableData" colspan=3>
                <input type="hidden" name="T_PLAN_NO" value="">
                <input type="text"name="T_PLAN_NAME" class=BigStatic size="46" readonly value="">
                <a href="javascript:;" class="orgAdd" onClick="LoadWindow()"><?=_("ѡ��")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("��ѵ�ˣ�")?></td>
            <td class="TableData" colspan=3>
                <!--<input type="hidden" name="T_JOIN_PERSON">
                <textarea cols=45 name="T_JOIN_PERSON_NAME" rows=2 class="BigInput" wrap="yes" readonly></textarea>-->
                <input type="hidden" name="T_JOIN_PERSON">
                <textarea cols=45 name="T_JOIN_PERSON_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','5','T_JOIN_PERSON','T_JOIN_PERSON_NAME','1')"><?=_("���")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('T_JOIN_PERSON','T_JOIN_PERSON_NAME')"><?=_("���")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("��ѵ������")?></td>
            <td class="TableData">
                <input type="text" name="T_INSTITUTION_NAME" size="20" class="BigInput" >
            </td>
            <td nowrap class="TableData"><?=_("��ѵ���ã�")?></td>
            <td class="TableData">
                <input type="text" name="TRAINNING_COST" size="10" class="BigInput" >
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("��ѵ������")?></td>
            <td class="TableData">
                <input type="text" name="T_INSTITUTION_NAME" size="20" class="BigInput" >
            </td>
            <td nowrap class="TableData"><?=_("��ѵ���ã�")?></td>
            <td class="TableData">
                <input type="text" name="TRAINNING_COST" size="10" class="BigInput" >
            </td>
        </tr>
        <tr height="25">
            <td nowrap class="TableData"><?=_("����ѡ��")?></td>
            <td class="TableData" colspan="6">
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