<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/editor.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("新建人事调动信息");
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
    if(document.form1.TRANSFER_PERSON.value=="")
    {
        alert("<?=_("请选择调动人员！")?>");
        return (false);
    }
    if(document.form1.TRAN_DEPT_BEFORE.value=="")
    {
        alert("<?=_("调动前所在部门不能为空！")?>");
        return (false);
    }
    if(document.form1.TRAN_DEPT_AFTER.value=="")
    {
        alert("<?=_("调动后所在部门不能为空！")?>");
        return (false);
    }
    if(document.form1.TRANSFER_DATE.value!="" && document.form1.TRANSFER_EFFECTIVE_DATE.value!="" && document.form1.TRANSFER_DATE.value > document.form1.TRANSFER_EFFECTIVE_DATE.value)
    {
        alert("<?=_("调动生效日期不能小于调动日期！")?>");
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
        URL="delete_attach.php?TRANSFER_ID=<?=$TRANSFER_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
        window.location=URL;
    }
}

function LoadDialogWindowTSfer(URL, parent, loc_x, loc_y, width, height)
{
    if(window.showModalDialog)//window.open(URL);
        window.showModalDialog(URL,parent,"edge:raised;scroll:1;status:0;help:0;resizable:1;dialogWidth:"+width+"px;dialogHeight:"+height+"px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px",true);
    else
        window.open(URL,"load_dialog_win","height="+height+",width="+width+",status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes,modal=yes,dependent=yes,dialog=yes,minimizable=no",true);
}
function SelectUserSingleTSfer(MODULE_ID,TO_ID, TO_NAME, MANAGE_FLAG, FORM_NAME)
{
    URL="user_select_single_transfer?MODULE_ID="+MODULE_ID+"&TO_ID="+TO_ID+"&TO_NAME="+TO_NAME+"&MANAGE_FLAG="+MANAGE_FLAG+"&FORM_NAME="+FORM_NAME;
    loc_y=loc_x=200;
    if(is_ie)
    {
        loc_x=document.body.scrollLeft+event.clientX-100;
        loc_y=document.body.scrollTop+event.clientY+170;
    }
    LoadDialogWindow(URL,self,loc_x, loc_y, 400, 350);
}
function read_position(var_value)
{
    _get("dept_ajax.php","TRANSFER_PERSON="+var_value, show_position);
}
function show_position(req)
{
    if(req.status==200)
    {
        document.getElementById("TRAN_POSITION_BEFORE").value=req.responseText;
    }
}
</script>

<body class="bodycolor" topmargin="5">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建人事调动信息")?></span>&nbsp;&nbsp;
        </td>
    </tr>
</table>
<br>
<form enctype="multipart/form-data" action="transfer_add.php"  method="post" name="form1" onSubmit="return CheckForm();">
    <table class="TableBlock" width="80%" align="center">
        <tr>
            <td nowrap class="TableData"><?=_("调动人员：")?></td>
            <td class="TableData">
                <?
                $TRANSFER_PERSON =$USER_ID;
                $TRANSFER_PERSON_NAME = substr( getUserNameById($USER_ID), 0, -1);
                ?>
                <input type="text" name="TRANSFER_PERSON_NAME" size="15" class="BigStatic" readonly value="<?=_("$TRANSFER_PERSON_NAME")?>">&nbsp;
                <input type="hidden" name="TRANSFER_PERSON" value="<?=$TRANSFER_PERSON?>" onPropertyChange="read_position(this.value);">
                <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','TRANSFER_PERSON', 'TRANSFER_PERSON_NAME','1')"><?=_("选择")?></a>
            </td>
            <td nowrap class="TableData"> <?=_("调动类型：")?></td>
            <td class="TableData" >
                <select name="TRANSFER_TYPE" style="background: white;" title="<?=_("调动类型可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
                    <option value=""><?=_("请选择")?></option>
                    <?=hrms_code_list("HR_STAFF_TRANSFER","")?>
                </select>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("调动日期：")?></td>
            <td class="TableData">
                <input type="text" name="TRANSFER_DATE" size="15" maxlength="10" class="BigInput" value="<?=$TRANSFER_DATE?>" onClick="WdatePicker()"/>
            </td>
            <td nowrap class="TableData"><?=_("调动生效日期：")?></td>
            <td class="TableData">
                <input type="text" name="TRANSFER_EFFECTIVE_DATE" size="15" maxlength="10" class="BigInput" value="<?=$TRANSFER_EFFECTIVE_DATE?>" onClick="WdatePicker()"/>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("调动前单位：")?></td>
            <td class="TableData">
                <INPUT type="text"name="TRAN_COMPANY_BEFORE" class=BigInput size="15" value="<?=$TRAN_COMPANY_BEFORE?>">
            </td>
            <td nowrap class="TableData"><?=_("调动后单位：")?></td>
            <td class="TableData">
                <INPUT type="text"name="TRAN_COMPANY_AFTER" class=BigInput size="15" value="<?=$TRAN_COMPANY_AFTER?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("调动前职务：")?></td>
            <td class="TableData">
                <INPUT type="text"name="TRAN_POSITION_BEFORE" id="TRAN_POSITION_BEFORE" class=BigInput size="15" value="<?=$TRAN_POSITION_BEFORE?>">
            </td>
            <td nowrap class="TableData"><?=_("调动后职务：")?></td>
            <td class="TableData">
                <INPUT type="text"name="TRAN_POSITION_AFTER" class=BigInput size="15" value="<?=$TRAN_POSITION_AFTER?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("调动前部门：")?></td>
            <td class="TableData" >
                <input type="hidden" name="TRAN_DEPT_BEFORE">
                <input type="text" name="TRAN_DEPT_BEFORE_NAME" value="" class=BigStatic size=15 maxlength=100 readonly>
                <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','TRAN_DEPT_BEFORE','TRAN_DEPT_BEFORE_NAME')"><?=_("选择")?></a>
            </td>
            <td nowrap class="TableData"><?=_("调动后部门：")?></td>
            <td class="TableData">
                <input type="hidden" name="TRAN_DEPT_AFTER">
                <input type="text" name="TRAN_DEPT_AFTER_NAME" value="" class=BigStatic size=15 maxlength=100 readonly>
                <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','TRAN_DEPT_AFTER','TRAN_DEPT_AFTER_NAME')"><?=_("选择")?></a>
            </td>
        </tr>

        <tr>
            <td nowrap class="TableData"><span ><?=_("调动后角色：")?></td>
            <td nowrap class="TableData">
                <select name="role" style="background: white;" title="<?=_("角色可在“系统管理”->“组织机构设置”->“角色与权限管理”模块设置。")?>">
                    <option value="">请选择角色</option>
                    <?
                    $query = "SELECT USER_PRIV,PRIV_NAME from  user_priv;";
                    $cursor= exequery(TD::conn(),$query, true);
                    while($ROW=mysql_fetch_array($cursor))
                    {
                        if($_SESSION["LOGIN_USER_PRIV"]=="1")
                        {

                            ?>
                            <option value=<?=$ROW["USER_PRIV"]?>><?=$ROW["PRIV_NAME"]?>&nbsp&nbsp;</option>
                            <?
                        }
                        else
                        {
                            $query2 = "SELECT * from  hr_role_manage WHERE FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',HR_ROLE_MANAGE);";
                            $cursor2= exequery(TD::conn(),$query2);
                            while($ROW2=mysql_fetch_array($cursor2))
                            {
                                $NEW_NUMBER=strpos($ROW2["HR_USER_PRIV"],$ROW["USER_PRIV"]);
                                if($NEW_NUMBER > -1)
                                {
                                    $NEW_NAME="USER_P".$ROW["USER_PRIV"];
                                    $$NEW_NAME=1;
                                }
                            }
                            ?>
                            <option value=<?=$ROW["USER_PRIV"]?> class="<?$NEW_NAME="USER_P".$ROW["USER_PRIV"]; if($$NEW_NAME != 1) echo _("xinxiyinchneg") ?>"><?=$ROW["PRIV_NAME"]?>&nbsp&nbsp;</option>
                            <?
                        }
                    }
                    ?>
                </select>
            </td>
            <td nowrap class="TableData"></td>
            <td nowrap class="TableData"></td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("调动手续办理：")?></td>
            <td class="TableData" colspan=3>
                <textarea name="MATERIALS_CONDITION" cols="70" rows="3" class="BigInput" value=""><?=$MATERIALS_CONDITION?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("备注：")?></td>
            <td class="TableData" colspan=3>
                <textarea name="REMARK" cols="70" rows="3" class="BigInput" value=""><?=$REMARK?></textarea>
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
        <tr>
            <td nowrap class="TableData"> <?=_("提醒：")?></td>
            <td class="TableData" colspan=3>
                <?=sms_remind(56);?>
            </td>
        </tr>
        <tr id="EDITOR">
            <td class="TableData" colspan="4"> <?=_("调动原因：")?>
                <?
                $editor = new Editor('TRAN_REASON') ;
                $editor->Height = '200';
                $editor->Config = array("EditorAreaStyles" => "body{font-size:20pt;}","model_type" => "14");
                $editor->Value = $TRAN_REASON ;
                //$editor->Config = array('model_type' => '14') ;
                $editor->Create() ;
                ?>
            </td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan=4 nowrap>
                <input type="submit" value="<?=_("保存")?>" class="BigButton">
            </td>
        </tr>
    </table>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $(".xinxiyinchneg").remove();
});
</script>
</body>
</html>