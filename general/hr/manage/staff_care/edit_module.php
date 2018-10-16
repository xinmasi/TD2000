<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("模板管理");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function mysubmit()
{
    if(document.form1.MODULE_NAME.value == "")
    {
        alert("<?=_("模板名称不能为空")?>");
        return;
    }
    else if(document.form1.TO_NAME.value == "")
    {
        alert("<?=_("用户不能为空")?>");
        return;
    }
    else if(document.form1.GREETING.value == "")
    {
        alert("<?=_("问候语不能为空")?>");
        return;
    }
    document.form1.submit();
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <? if($MODULE_ID!="") echo _("编辑"); else echo _("新建");?><?=_("生日贺卡模板")?></span></td>
    </tr>
</table>
<br>
<?
$query="select * from HR_CARD_MODULE where MODULE_ID='$MODULE_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $MODULE_ID=$ROW["MODULE_ID"];
    $MODULE_NAME=$ROW["MODULE_NAME"];
    $CREATE_TIME=$ROW["CREATE_TIME"];
    $GREETING=$ROW["GREETING"];
    $TO_ID=$ROW["SUIT_USERS"];
    $USER_NAME_STR=GetUserNameById($TO_ID);
    $ATTACH =$ROW["ATTACH"];
    $ATTACH_ARRAY = explode(",",$ATTACH);
    $ATTACH_ID =$ATTACH_ARRAY[0];
    $ATTACH_NAME =$ATTACH_ARRAY[1];
}
?>
<form enctype="multipart/form-data" action="update_module.php"  method="post" name="form1">
    <table class="TableBlock" width="60%" align="center">
        <tr>
            <td nowrap class="TableContent" align="left"><?=_("模板名称：")?></td>
            <td class="TableData">
                <input type="text" size=40 name="MODULE_NAME" class="BigInput" value="<?=$MODULE_NAME?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableContent" align="left"><?=_("问候语：")?></td>
            <td class="TableData">
                <input type="text" size=40 name="GREETING" class="BigInput" value="<?=$GREETING?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableContent"" align="left"><?=_("适用员工：")?></td>
            <td class="TableData">
                <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
                <textarea cols=40 name="TO_NAME" rows=4 class="BigStatic" wrap="yes" readonly><?=$USER_NAME_STR?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','TO_ID', 'TO_NAME')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID', 'TO_NAME')"><?=_("清空")?></a>
            </td>
        </tr>
        <?
        if($ATTACH_NAME!="")
        {
            ?>
            <tr>
                <td nowrap class="TableContent" align="left"><?=_("模板文件：")?></td>
                <td class="TableData"><?=attach_link($ATTACH_ID,$ATTACH_NAME,1,1,0,1,0,0,0,0,"")?></td>
            </tr>
            <?
        }
        ?>
        <tr>
            <td nowrap class="TableContent" align="left"><?=_("上传模板：")?></td>
            <td class="TableData"><input type="file" name="ATTACHMENT" size=30 class="BigInput"> *<?=_("必须上传一个后缀为swf的flash文件")?></td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan="2" nowrap>
                <input type="hidden" name="MODULE_ID" value="<?=$MODULE_ID?>">
                <input type="hidden" name="ATTACH_ID_OLD" value="<?=$ATTACH_ID?>">
                <input type="hidden" name="ATTACH_NAME_OLD" value="<?=$ATTACH_NAME?>">
                <input type="hidden" name="ACTION" value="<?=$MODULE_ID ? 'update' : 'insert'?>">
                <input type="button" value="<?=_("保存")?>" class="BigButton" onclick="mysubmit();">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="history.back();">
            </td>
        </tr>
    </table>
</form>
</body>
</html>