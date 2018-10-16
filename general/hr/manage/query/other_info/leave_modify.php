<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/editor.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("员工离职信息修改");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css" />
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
    if(document.form1.LEAVE_PERSON.value=="")
    {
        alert("<?=_("请选择离职人员！")?>");
        return (false);
    }

    if(document.form1.LAST_SALARY_TIME.value!="" && document.form1.QUIT_TIME_FACT.value!="" && document.form1.LAST_SALARY_TIME.value > document.form1.QUIT_TIME_FACT.value)
    {
        alert("<?=_("实际离职日期不能小于工资截止日期！")?>");
        return (false);
    }
    if(document.form1.APPLICATION_DATE.value!="" && document.form1.QUIT_TIME_PLAN.value!="" && document.form1.APPLICATION_DATE.value > document.form1.QUIT_TIME_PLAN.value)
    {
        alert("<?=_("拟离职日期不能小于申请日期！")?>");
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
        URL="delete_attach.php?LEAVE_ID=<?=$LEAVE_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
        window.location=URL;
    }
}
function toggle()
{
    if(document.form1.NOTIFY.checked==true)
        document.getElementById("show").style.display="";
    else
        document.getElementById("show").style.display="none";
}

function LoadDialogWindowTSfer(URL, parent, loc_x, loc_y, width, height)
{
    if(window.showModalDialog)
        window.showModalDialog(URL,parent,"edge:raised;scroll:1;status:0;help:0;resizable:1;dialogWidth:"+width+"px;dialogHeight:"+height+"px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px",true);
    else
        window.open(URL,"load_dialog_win","height="+height+",width="+width+",status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes,modal=yes,dependent=yes,dialog=yes,minimizable=no",true);
}
function SelectUserSingleTSfer(MODULE_ID,TO_ID, TO_NAME, MANAGE_FLAG, FORM_NAME)
{
    URL="user_select_single?MODULE_ID="+MODULE_ID+"&TO_ID="+TO_ID+"&TO_NAME="+TO_NAME+"&MANAGE_FLAG="+MANAGE_FLAG+"&FORM_NAME="+FORM_NAME;
    loc_y=loc_x=200;
    if(is_ie)
    {
        loc_x=document.body.scrollLeft+event.clientX-100;
        loc_y=document.body.scrollTop+event.clientY+170;
    }
    LoadDialogWindow(URL,self,loc_x, loc_y, 400, 350);
}
</script>

<?
$query="select * from HR_STAFF_LEAVE where LEAVE_ID='$LEAVE_ID'";
$cursor= exequery(TD::conn(),$query, $connstatus);
if($ROW=mysql_fetch_array($cursor))
{
    $LEAVE_ID=$ROW["LEAVE_ID"];
    $USER_ID=$ROW["USER_ID"];
    $DEPT_ID=$ROW["DEPT_ID"];
    $QUIT_TIME_PLAN=$ROW["QUIT_TIME_PLAN"];
    $QUIT_TYPE=$ROW["QUIT_TYPE"];
    $QUIT_REASON=$ROW["QUIT_REASON"];
    $LAST_SALARY_TIME=$ROW["LAST_SALARY_TIME"];
    $TRACE=$ROW["TRACE"];
    $REMARK=$ROW["REMARK"];
    $QUIT_TIME_FACT=$ROW["QUIT_TIME_FACT"];
    $LEAVE_PERSON=$ROW["LEAVE_PERSON"];
    $MATERIALS_CONDITION=$ROW["MATERIALS_CONDITION"];
    $POSITION=$ROW["POSITION"];
    $ADD_TIME=$ROW["ADD_TIME"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME =$ROW["ATTACHMENT_NAME"];
    $APPLICATION_DATE =$ROW["APPLICATION_DATE"];
    $LEAVE_DEPT =$ROW["LEAVE_DEPT"];
    $SALARY = $ROW["SALARY"];

    $LEAVE_PERSON_NAME=substr(GetUserNameById($LEAVE_PERSON),0,-1);
    $LEAVE_DEPT_NAME=substr(GetDeptNameById($LEAVE_DEPT),0,-1);

    if($QUIT_TIME_PLAN=="0000-00-00")
        $QUIT_TIME_PLAN="";
    if($QUIT_TIME_FACT=="0000-00-00")
        $QUIT_TIME_FACT="";
    if($LAST_SALARY_TIME=="0000-00-00")
        $LAST_SALARY_TIME="";
    if($APPLICATION_DATE=="0000-00-00")
        $APPLICATION_DATE="";
}

?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("编辑员工离职信息")?></span>&nbsp;&nbsp;
        </td>
    </tr>
</table>
<form action="leave_update.php"  method="post" name="form1" enctype="multipart/form-data" onSubmit="return CheckForm();">
    <table class="TableBlock" width="80%" align="center">
        <tr>
            <td nowrap class="TableData"><?=_("离职人员：")?></td>
            <td class="TableData">
                <input type="text" name="LEAVE_PERSON_NAME" size="15" class="BigStatic" readonly value="<?=$LEAVE_PERSON_NAME?>">&nbsp;
                <input type="hidden" name="LEAVE_PERSON" value="<?=$LEAVE_PERSON?>">
                <a href="javascript:;" class="orgAdd" onClick="SelectUserSingleTSfer('','LEAVE_PERSON', 'LEAVE_PERSON_NAME','1')"><?=_("选择")?></a>
            </td>
            <td nowrap class="TableData"><?=_("担任职务：")?></td>
            <td class="TableData">
                <INPUT type="text"name="POSITION" class=BigInput size="15" value="<?=$POSITION?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("离职类型：")?></td>
            <td class="TableData" >
                <select name="QUIT_TYPE" style="background: white;" title="<?=_("离职类型可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
                    <option value=""><?=_("离职类型")?>&nbsp&nbsp&nbsp&nbsp&nbsp;</option>
                    <?=hrms_code_list("HR_STAFF_LEAVE",$QUIT_TYPE)?>
                </select>
            </td>
            <td nowrap class="TableData"><?=_("申请日期：")?></td>
            <td class="TableData">
                <input type="text" name="APPLICATION_DATE" size="15" maxlength="10" class="BigInput" value="<?=$APPLICATION_DATE?>" onClick="WdatePicker()"/>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("拟离职日期：")?></td>
            <td class="TableData">
                <input type="text" name="QUIT_TIME_PLAN" size="15" maxlength="10" class="BigInput" value="<?=$QUIT_TIME_PLAN?>" onClick="WdatePicker()"/>
            </td>
            <td nowrap class="TableData"> <?=_("实际离职日期：")?></td>
            <td class="TableData">
                <input type="text" name="QUIT_TIME_FACT" size="15" maxlength="10" class="BigInput" value="<?=$QUIT_TIME_FACT?>" onClick="WdatePicker()"/>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("工资截止日期：")?></td>
            <td class="TableData">
                <input type="text" name="LAST_SALARY_TIME" size="15" maxlength="10" class="BigInput" value="<?=$LAST_SALARY_TIME?>" onClick="WdatePicker()"/>
            </td>
            <td nowrap class="TableData"><?=_("离职部门：")?></td>
            <td class="TableData" colspan=3>
                <input type="hidden" name="LEAVE_DEPT" value="<?=$LEAVE_DEPT?>">
                <input type="text" name="LEAVE_DEPT_NAME" value="<?=$LEAVE_DEPT_NAME?>" class=BigStatic size=15 maxlength=100 readonly>
                <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','LEAVE_DEPT','LEAVE_DEPT_NAME')"><?=_("选择")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("离职当月薪资：")?></td>
            <td class="TableData" ><INPUT type="text"name="SALARY" class=BigInput size="15" value="<?=$SALARY?>"></td>
            <td nowrap class="TableData"></td>
            <td class="TableData" ></td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("去向：")?></td>
            <td class="TableData"colspan=3>
                <textarea name="TRACE" cols="70" rows="3" class="BigInput" value=""><?=$TRACE?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("物品交接情况：")?></td>
            <td class="TableData" colspan=3>
                <textarea name="MATERIALS_CONDITION" cols="70" rows="3" class="BigInput" wrap="on"><?=$MATERIALS_CONDITION?></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("备注：")?></td>
            <td class="TableData" colspan=3>
                <textarea name="REMARK" cols="70" rows="3" class="BigInput" wrap="on"><?=$REMARK?></textarea>
            </td>
        </tr>
        <tr class="TableData" id="attachment2">
            <td nowrap><?=_("附件文档：")?></td>
            <td nowrap colspan=3>
                <?
                if($ATTACHMENT_ID=="")
                    echo _("无附件");
                else
                    echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1);
                ?>
            </td>
        </tr>
        <tr height="25" id="attachment1">
            <td nowrap class="TableData"><span id="ATTACH_LABEL"><?=_("附件上传：")?></span></td>
            <td class="TableData" colspan=3>
                <script>ShowAddFile();</script>
                <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
                <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
            </td>
        </tr>
        <tr class="TableData">
            <td rowspan="2"><?=_("提醒：")?></td>
            <td colspan="3">
                <input type="checkbox" name="NOTIFY" onClick="toggle()" checked="checked"><?=_("向相关人员发送事务提醒消息(如财务人员),办公室")?>
            </td>
        </tr>
        <tr class="TableData" id="show">
            <td colspan="3">
                <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
                <textarea cols="40" name="TO_NAME" rows="2" style="overflow-y:auto;" class="SmallStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','TO_ID', 'TO_NAME')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
            </td>
        </tr>
        <tr id="EDITOR">
            <td class="TableData" colspan="4"> <?=_("离职原因：")?>
                <input type="hidden" value="ack" name="LEAVE">
                <?
                $editor = new Editor('QUIT_REASON') ;
                $editor->Height = '200';
                $editor->Config = array("EditorAreaStyles" => "body{font-size:20pt;}","model_type" => "14");
                $editor->Value = $QUIT_REASON ;
                //$editor->Config = array('model_type' => '14') ;
                $editor->Create() ;
                ?>
            </td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan=4 nowrap>
                <input type="hidden" value="<?=$LEAVE_ID?>" name="LEAVE_ID">
                <input type="submit" value="<?=_("保存")?>" class="BigButton">
                <!--<input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index1.php?start=<?=$start?>'">-->
            </td>
        </tr>
    </table>
</form>
</body>
</html>