<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/editor.php");
include_once("inc/utility_file.php");

$query="select JOB_POSITION from HR_STAFF_INFO where USER_ID='$USER_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $JOB_POSITION=$ROW["JOB_POSITION"];
$query="select DEPT_ID from USER where USER_ID='$USER_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $DEPT_ID=$ROW["DEPT_ID"];

$HTML_PAGE_TITLE = _("新建员工离职信息");
include_once("inc/header.inc.php");
?>


<meta http-equiv="x-ua-compatible" content="IE=7">
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
        if(document.form1.LEAVE_DEPT_NAME.value=="")
        {
            alert("<?=_("请选择离职部门！")?>");
            return (false);
        }
        if(document.form1.QUIT_TIME_FACT.value=="")
        {
            alert("<?=_("实际离职日期不能为空！")?>");
            return (false);
        }
        if(document.form1.APPLICATION_DATE.value!=""&&document.form1.QUIT_TIME_PLAN.value!=""&&document.form1.QUIT_TIME_FACT.value!="" &&document.form1.APPLICATION_DATE.value > document.form1.QUIT_TIME_PLAN.value&&document.form1.APPLICATION_DATE.value > document.form1.QUIT_TIME_FACT.value)
        {
            alert("<?=_("实际离职日期和拟离职日期不能小于申请日期！")?>");
            return (false);
        }
        if(document.form1.APPLICATION_DATE.value!="" && document.form1.QUIT_TIME_PLAN.value!="" && document.form1.APPLICATION_DATE.value > document.form1.QUIT_TIME_PLAN.value)
        {
            alert("<?=_("拟离职日期不能小于申请日期！")?>");
            return (false);
        }

        if(document.form1.APPLICATION_DATE.value!="" && document.form1.QUIT_TIME_FACT.value!="" && document.form1.APPLICATION_DATE.value > document.form1.QUIT_TIME_FACT.value)
        {
            alert("<?=_("实际离职日期不能小于申请日期！")?>");
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
    function view_item(LEAVE_PERSON)
    {
        if(LEAVE_PERSON=="")
        {
            alert("<?=_("未指定离职人员")?>");
        }
        else
        {
            URL="use_item.php?LEAVE_PERSON="+LEAVE_PERSON;
            myleft=(screen.availWidth-500)/2;
            window.open(URL,"items","height=360,width=500,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
        }
    }
    function view_attendance_item(LEAVE_PERSON)
    {
        if(LEAVE_PERSON=="")
        {
            alert("<?=_("未指定离职人员")?>");
        }
        else
        {
            URL="/general/attendance/manage/records/user.php?LEAVE_PERSON="+LEAVE_PERSON+"&USER_ID="+LEAVE_PERSON;
            myleft=(screen.availWidth-500)/2;
            window.open(URL,"items","height=360,width=500,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
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
        if(window.showModalDialog)//window.open(URL);
            window.showModalDialog(URL,parent,"edge:raised;scroll:1;status:0;help:0;resizable:1;dialogWidth:"+width+"px;dialogHeight:"+height+"px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px",true);
        else
            window.open(URL,"load_dialog_win","height="+height+",width="+width+",status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes,modal=yes,dependent=yes,dialog=yes,minimizable=no",true);
    }
    function SelectUserSingleTSfer(MODULE_ID,TO_ID, TO_NAME, MANAGE_FLAG, FORM_NAME)
    {
        URL="user_select_single/?MODULE_ID="+MODULE_ID+"&TO_ID="+TO_ID+"&TO_NAME="+TO_NAME+"&MANAGE_FLAG="+MANAGE_FLAG+"&FORM_NAME="+FORM_NAME;
        loc_y=loc_x=200;
        if(is_ie)
        {
            loc_x=document.body.scrollLeft+event.clientX-100;
            loc_y=document.body.scrollTop+event.clientY+170;
        }
        LoadDialogWindow(URL,self,loc_x, loc_y, 400, 350);
    }
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建员工离职信息")?></span>&nbsp;&nbsp;
        </td>
    </tr>
</table>
<br>
<form enctype="multipart/form-data" action="leave_add.php"  method="post" name="form1" onSubmit="return CheckForm();">
    <table class="TableBlock" width="80%" align="center">
        <tr>
            <td nowrap class="TableData"><span style="color: red;">*</span><?=_("离职人员：")?></td>
            <td class="TableData">
                <?
                $LEAVE_PERSON =$USER_ID;
                $LEAVE_PERSON_NAME = substr( getUserNameById($USER_ID), 0, -1);
                ?>
                <input type="text" name="LEAVE_PERSON_NAME" size="15" class="BigStatic" readonly value="<?=_("$LEAVE_PERSON_NAME")?>">&nbsp;
                <input type="hidden" name="LEAVE_PERSON" value="<?=$LEAVE_PERSON?>">
                <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','LEAVE_PERSON', 'LEAVE_PERSON_NAME','1')"><?=_("选择")?></a>
                &nbsp;<a href="javascript:view_item(document.form1.LEAVE_PERSON.value)"><?=_("查看领用物品")?></a>
            </td>
            <td nowrap class="TableData"><?=_("担任职务：")?></td>
            <td class="TableData"><INPUT type="text"name="POSITION" class=BigInput size="15" value="<?=$JOB_POSITION?>"></td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("离职类型：")?></td>
            <td class="TableData" >
                <select name="QUIT_TYPE" style="background: white;" title="<?=_("离职类型可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
                    <option value=""><?=_("离职类型")?>&nbsp&nbsp&nbsp&nbsp&nbsp;</option>
                    <?=hrms_code_list("HR_STAFF_LEAVE","")?>
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
            <td nowrap class="TableData"><span style="color: red;">*</span><?=_("实际离职日期：")?></td>
            <td class="TableData">
                <input type="text" name="QUIT_TIME_FACT" size="15" maxlength="10" class="BigInput" value="<?=$QUIT_TIME_FACT?>" onClick="WdatePicker()"/>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("工资截止日期：")?></td>
            <td class="TableData">
                <input type="text" name="LAST_SALARY_TIME" size="15" maxlength="10" class="BigInput" value="<?=$LAST_SALARY_TIME?>" onClick="WdatePicker()"/>
            </td>
            <td nowrap class="TableData"><span style="color: red;">*</span><?=_("离职部门：")?></td>
            <td class="TableData" colspan=3>
                <input type="hidden" name="LEAVE_DEPT" value="<?=$DEPT_ID?>">
                <input type="text" name="LEAVE_DEPT_NAME" value="<?=substr(GetDeptNameById($DEPT_ID),0,-1)?>" class=BigStatic size=15 maxlength=100 readonly>
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
                <textarea name="TRACE" cols="70" rows="3" class="BigInput" value="<?=$TRACE?>"></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("离职手续办理：")?></td>
            <td class="TableData" colspan=3>
                <textarea name="MATERIALS_CONDITION" cols="70" rows="3" class="BigInput" value="<?=$MATERIALS_CONDITION?>"></textarea>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"><?=_("备注：")?></td>
            <td class="TableData" colspan=3>
                <textarea name="REMARK" cols="70" rows="3" class="BigInput" value="<?=$REMARK?>"></textarea>
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
        <tr class="TableData">
            <td rowspan="2"><?=_("提醒：")?></td>
            <td colspan="3">
                <input type="checkbox" name="NOTIFY" onClick="toggle()" checked="checked"><?=_("向相关人员发送事务提醒消息(如财务人员,办公室)")?>
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
                <input type="hidden" name="USER_ID1" value="<?=$USER_ID?>">
                <input type="submit" value="<?=_("保存")?>" class="BigButton">
            </td>
        </tr>
    </table>
</form>
</body>
</html>