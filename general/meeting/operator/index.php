<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("ָ���������Ա");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/editor.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/mouse_mon.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script>
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm()
{
    return (true);
}

function InsertImage(src)
{
    AddImage2Editor('MR_RULE', src);
}
function upload_attach2()
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
        URL="delete_attach.php?ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
        window.location=URL;
    }
}
</script>
<?
$query="select * from MEETING_RULE";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $RULE_ID            = $ROW['RULE_ID'];
    $MR_RULE            = $ROW['MEETING_ROOM_RULE'];
    $MEETING_OPERATOR   = $ROW['MEETING_OPERATOR'];
    $IS_APPROVE         = $ROW['MEETING_IS_APPROVE'];
    $ATTACHMENT_ID      = $ROW['ATTACHMENT_ID'];
    $ATTACHMENT_NAME    = $ROW['ATTACHMENT_NAME'];
    $SUMMARY_APPROVE    = $ROW['SUMMARY_APPROVE'];
}
$query = "SELECT USER_NAME from USER where find_in_set(USER_ID,'".$MEETING_OPERATOR."')";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
    $MANAGERS_USER_NAME.=$ROW["USER_NAME"].",";

if($IS_APPROVE=="1")
    $message="<font color=red><b>(��ǰ״̬Ϊ����Ҫ���)</b></font>";
else
    $message="<font color=red><b>(��ǰ״̬Ϊ������Ҫ���)</b></font>";

if($SUMMARY_APPROVE=="1")
    $message1="<font color=red><b>(��ǰ״̬Ϊ����Ҫ���)</b></font>";
else
    $message1="<font color=red><b>(��ǰ״̬Ϊ������Ҫ���)</b></font>";

?>
<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("ָ������Ա�������ҹ����ƶ�")?></span>
    </td>
    </tr>
</table>
<br><br>
<form enctype="multipart/form-data" action="submit.php"  method="post" name="form1" onSubmit="return CheckForm();">
    <table class="TableList" width="70%" align="center">
        <tr>
            <td nowrap class="TableData" colspan=2><?=_("�����������Ƿ���Ҫ��ˣ�")?>&nbsp;&nbsp;<?=$message?>
            </td>
        </tr>
        <tr>
        <tr>
            <td class="TableData"colspan=2>
                <input type="checkbox" name="IS_APPROVE" id="IS_APPROVE" value="1" checked /><label for="IS_APPROVE"><?=_("��Ҫ���")?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <font color=red><b><?=_("ע��")?></b></font><?=_("�����ѡ����Ҫ��ˣ��ᵼ��Ա������������Ҫ��ˣ���Ϊ������׼��������ѡ��")?>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" colspan=2><?=_("�����Ҫ�Ƿ���Ҫ��ˣ�")?>&nbsp;&nbsp;<?=$message1?>
            </td>
        </tr>
        <tr>
        <tr>
            <td class="TableData"colspan=2>
                <input type="checkbox" name="SUMMARY_APPROVE" id="SUMMARY_APPROVE" value="1" checked /><label for="SUMMARY_APPROVE"><?=_("��Ҫ���")?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <font color=red><b><?=_("ע��")?></b></font><?=_("�����ѡ����Ҫ��ˣ��ᵼ��Ա����д�����Ҫ����Ҫ��ˣ���Ϊ������׼��������ѡ��")?>
            </td>
        </tr>
        <tr bgcolor="#CCCCCC">
            <td nowrap class="TableData" colspan=2><?=_("�������Ա��")?></td>
        </tr>
        <tr>
            <td class="TableData" colspan=2>
                <input type="hidden" name="COPY_TO_ID" value="<?=$MEETING_OPERATOR?>">
                <textarea cols=45 name="COPY_TO_NAME" rows=3 class="BigStatic" wrap="yes" readonly><?=$MANAGERS_USER_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('137','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData" colspan=2><?=_("�����ҹ����ƶȣ�")?></td>
        </tr>
        <tr id="EDITOR">
            <td class="TableData" colspan=2>
                <?
                $editor = new Editor('MR_RULE') ;
                $editor->ToolbarSet = 'Basic';
                $editor->Config = array('model_type' => '08');
                $editor->Height = '240';
                $editor->Value = $MR_RULE ;
                $editor->Create() ;
                ?>
            </td>
        </tr>
        <?
        if($ATTACHMENT_ID!="" && $ATTACHMENT_NAME!="")
        {
            ?>
            <tr>
                <td nowrap class="TableData"><?=_("������")?></td>
                <td class="TableData">
                    <?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1)?></td>
            </tr>
            <?
        }
        ?>
        <tr height="25">
            <td nowrap class="TableData"><?=_("����ѡ��")?></td>
            <td class="TableData">
                <script>ShowAddFile();</script>
                <script>$("ATTACHMENT_upload_div").innerHTML='<a href="javascript:upload_attach2();"><?=_("�ϴ�����")?></a>'</script>
            </td>
        </tr>
        <tr class="TableControl">
            <td align="center" valign="top" colspan="3">
                <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
                <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
                <input type="hidden" name="RULE_ID" value="<?=$RULE_ID?>">
                <input type="submit" class="BigButton" value="<?=_("����")?>">&nbsp;&nbsp;
            </td>
        </tr>
    </table>
</form>
</body>
</html>
