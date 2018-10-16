<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/editor.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("新建社会关系");
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
        alert("<?=_("员工姓名不能为空！")?>");
        return (false);
    }
    if(document.form1.MEMBER.value=="")
    {
        alert("<?=_("成员姓名不能为空！")?>");
        return (false);
    }
    if(document.form1.RELATIONSHIP.value=="")
    {
        alert("<?=_("请选择与本人关系！")?>");
        return (false);
    }
    if(document.form1.WORK_UNIT.value=="")
    {
        alert("<?=_("工作单位不能为空！")?>");
        return (false);
    }
    if(document.form1.UNIT_ADDRESS.value=="")
    {
        alert("<?=_("单位地址不能为空！")?>");
        return (false);
    }
    if(document.form1.PERSONAL_TEL.value=="" && document.form1.OFFICE_TEL.value=="" && document.form1.HOME_TEL.value=="")
    {
        alert("<?=_("个人、单位和家庭的联系电话不能全为空！")?>");
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
    var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
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
        <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建社会关系")?></span>&nbsp;&nbsp;
        </td>
    </tr>
</table>
<br>
<form enctype="multipart/form-data" action="relatives_add.php"  method="post" name="form1" onsubmit="return CheckForm();">
    <table class="TableBlock" width="80%" align="center">
        <tr>
            <td nowrap class="TableData"><?=_("单位员工：")?></td>
            <td class="TableData">
                <?
                $STAFF_NAME =$USER_ID;
                $STAFF_NAME1 = substr( getUserNameById($USER_ID), 0, -1);
                ?>
                <input type="text" name="STAFF_NAME1" size="15" class="BigStatic" readonly value="<?=_("$STAFF_NAME1")?>">&nbsp;
                <input type="hidden" name="STAFF_NAME" value="<?=$STAFF_NAME?>">
                <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','STAFF_NAME', 'STAFF_NAME1','1')"><?=_("选择")?></a>
            </td>
            <td nowrap class="TableData"><?=_("成员姓名：")?></td>
            <td class="TableData">
                <INPUT type="text"name="MEMBER" class=BigInput size="15" value="<?=$MEMBER?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("与本人关系：")?></td>
            <td class="TableData" >
                <select name="RELATIONSHIP" style="background: white;" title="<?=_("与本人关系可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
                    <option value=""><?=_("与本人关系")?>&nbsp&nbsp&nbsp</option>
                    <?=hrms_code_list("HR_STAFF_RELATIVES","")?>
                </select>
            </td>
            <td nowrap class="TableData"><?=_("出生日期：")?></td>
            <td class="TableData">
                <input type="text" name="BIRTHDAY" size="15" maxlength="10" class="BigInput" value="<?=$BIRTHDAY?>" onClick="WdatePicker()"/>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("政治面貌：")?></td>
            <td class="TableData" >
                <select name="POLITICS" style="background: white;" title="<?=_("政治面貌在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
                    <option value=""><?=_("政治面貌")?>&nbsp&nbsp&nbsp&nbsp&nbsp</option>
                    <?=hrms_code_list("STAFF_POLITICAL_STATUS",$POLITICS)?>
                </select>
            </td>
            <td nowrap class="TableData"> <?=_("职业：")?></td>
            <td class="TableData">
                <INPUT type="text"name="JOB_OCCUPATION" class=BigInput size="15" value="<?=$JOB_OCCUPATION?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("担任职务：")?></td>
            <td class="TableData">
                <INPUT type="text"name="POST_OF_JOB" class=BigInput size="15" value="<?=$POST_OF_JOB?>">
            </td>
            <td nowrap class="TableData"> <?=_("联系电话（个人）：")?></td>
            <td class="TableData">
                <INPUT type="text"name="PERSONAL_TEL" class=BigInput size="15" value="<?=$PERSONAL_TEL?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("联系电话（家庭）：")?></td>
            <td class="TableData">
                <INPUT type="text"name="HOME_TEL" class=BigInput size="15" value="<?=$HOME_TEL?>">
            </td>
            <td nowrap class="TableData"> <?=_("联系电话（单位）：")?></td>
            <td class="TableData">
                <INPUT type="text"name="OFFICE_TEL" class=BigInput size="15" value="<?=$OFFICE_TEL?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("工作单位：")?></td>
            <td class="TableData" colspan=3>
                <textarea name="WORK_UNIT" cols="78" rows="2" class="BigInput" value=""><?=$WORK_UNIT?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("单位地址：")?></td>
            <td class="TableData" colspan=3>
                <textarea name="UNIT_ADDRESS" cols="78" rows="2" class="BigInput" value=""><?=$UNIT_ADDRESS?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("家庭住址：")?></td>
            <td class="TableData" colspan=3>
                <textarea name="HOME_ADDRESS" cols="78" rows="2" class="BigInput" value=""><?=$HOME_ADDRESS?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("备注：")?></td>
            <td class="TableData" colspan=3>
                <textarea name="REMARK" cols="78" rows="2" class="BigInput" value=""><?=$REMARK?></textarea>
            </td>
        </tr>
        <tr height="25" id="attachment1">
            <td nowrap class="TableData" ><span id="ATTACH_LABEL"><?=_("附件上传：")?></span></td>
            <td class="TableData"colspan=3>
                <script>ShowAddFile();</script>
                <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
                <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
            </td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan=4 nowrap>
                <input type="submit" value="<?=_("保存")?>" class="BigButton">
            </td>
        </tr>
    </table>
</form>
</body>
</html>