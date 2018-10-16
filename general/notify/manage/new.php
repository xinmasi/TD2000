<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/editor.php");
include_once("inc/td_core.php");
include_once("inc/utility_file.php");

$PARA_ARRAY = get_sys_para("SMS_REMIND");
$PARA_VALUE=$PARA_ARRAY["SMS_REMIND"];
$REMIND_ARRAY = explode("|", $PARA_VALUE);
$SMS_REMIND = $REMIND_ARRAY[0];
$SMS2_REMIND = $REMIND_ARRAY[1];
$SMS3_REMIND = $REMIND_ARRAY[2];
$SMS4_REMIND = $REMIND_ARRAY[3];
$SMS5_REMIND = $REMIND_ARRAY[4];

$FONT_SIZE = get_font_size("FONT_NOTIFY", 12);
$PARA_ARRAY=get_sys_para("NOTIFY_AUDITING_SINGLE,NOTIFY_TOP_DAYS,NOTIFY_AUDITING_ALL,NOTIFY_AUDITING_MANAGER,NOTIFY_AUDITING_EXCEPTION,NOTIFY_AUDITING_SINGLE_NEW");
$NOTIFY_AUDITING_SINGLE=$PARA_ARRAY["NOTIFY_AUDITING_SINGLE"];//是否需要审批
$NOTIFY_AUDITING_SINGLE_NEW=$PARA_ARRAY["NOTIFY_AUDITING_SINGLE_NEW"];
$NOTIFY_AUDITING_EXCEPTION=$PARA_ARRAY["NOTIFY_AUDITING_EXCEPTION"];//无需审批人员


$TOP_DAYS=$PARA_ARRAY["NOTIFY_TOP_DAYS"]; //最大置顶日期
if($TOP_DAYS=="")//最大指定日期没有限制的时候
{
    $TOP_FLAG=0;
    $TOP_DAYS_REMIND=_("0表示一直置顶");
}
else
{
    $TOP_FLAG=$TOP_DAYS;
    $TOP_DAYS_REMIND=_("最大置顶时间为").$TOP_DAYS._("天");
}
$AUDITER_ALL_ID = $PARA_ARRAY["NOTIFY_AUDITING_ALL"];//可审批人员
$AUDITER_ALL_ID = td_trim($AUDITER_ALL_ID);
$NOTIFY_AUDITING_MANAGER=$PARA_ARRAY["NOTIFY_AUDITING_MANAGER"]; //自动选择审批人为部门主管设置
if($NOTIFY_AUDITING_SINGLE!="" && $NOTIFY_AUDITING_SINGLE_NEW=="")
{
    if(find_id($NOTIFY_AUDITING_EXCEPTION,$_SESSION["LOGIN_USER_ID"]))
        $NOTIFY_AUDITING_SINGLE=0;
}
else
{
    $NOTIFY_AUDITING_SINGLE=0;
}
$STATUS_DESC="<font color='#0000FF'>"._("选择格式")."</font>";
$CUR_DATE=date("Y-m-d",time());



$HTML_PAGE_TITLE = _("发布公告通知");
include_once("inc/header.inc.php");
?>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/swfupload.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/module/upload/uploader.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/mytable.js"></script>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/swfupload/swfupload.queue.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/swfupload/fileprogress.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/swfupload/handlers.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/upload/uploader.js"></script>
<script language="javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/tooltip/jquery.tooltip.min.js"></script>
<script language="javascript" src="<?=MYOA_JS_SERVER?>/static/js/tabopt.js"></script>

<script Language="JavaScript">
confirmSaveBeforeCloseTab("", "", "");
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
Uploader.configs.limitType = "<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
Uploader.configs.flash.post_params.PHPSESSID = "<?=session_id()?>";
Uploader.configs.flash.file_size_limit = "<?=intval(ini_get('upload_max_filesize'))?> MB";
String.prototype.trim= function()
{
    return this.replace(/(^\s*)|(\s*$)/g, "");
}
function CheckForm()
{

    if(document.getElementById("TYPE_ID").value == "")
    {
        var msg = sprintf("<?=_("您未选择公告类型，是否继续提交？")?>");
        if(!window.confirm(msg))
        {
            return (false);
        }
    }

    if(document.form1.SUBJECT.value.trim()=="<?=_("请输入公告标题")?>...")
        document.form1.SUBJECT.value="";

    if(document.form1.SUBJECT.value.trim()=="")
    {
        alert("<?=_("公告通知的标题不能为空！")?>");
        document.form1.SUBJECT.focus();
        return (false);
    }

    if(document.form1.TO_ID.value==""&&document.form1.PRIV_ID.value==""&&document.form1.COPY_TO_ID.value=="")
    {
        alert("<?=_("请指定发布范围！")?>");
        return (false);
    }
    var end_date = document.form1.END_DATE.value;
    var begin_date = document.form1.BEGIN_DATE.value;
    var now_date=document.form1.CUR_DATE.value;
    var v_now = new Date(Date.parse(now_date.replace(/-/g,"/")));
    var v_begin = new Date(Date.parse(begin_date.replace(/-/g, "/")));
    var v_end = new Date(Date.parse(end_date.replace(/-/g, "/")));
    if(v_begin!="" && v_now >v_begin)
    {
        alert("<?=_("生效日期应该大于等于当前日期！")?>");
        document.form1.BEGIN_DATE.focus();
        return (false);
    }
    if(v_end!="" && v_end <=v_begin)
    {
        alert("<?=_("终止日期应大于生效日期！")?>");
        document.form1.END_DATE.focus();
        return (false);
    }
    if(document.form1.FORMAT.value=="1")
    {
        var inputs=document.getElementsByTagName("INPUT");
        var file_count=0;
        for(var i=0;i<inputs.length;i++)
        {
            var el = inputs[i];
            var elType = el.type.toLowerCase();
            if(elType=="file")
            {
                if(el.value!="")
                    file_count++;
            }
        }
        if (file_count==0)
        {
            alert("<?=_("请选择MHT文件！")?>");
            return (false);
        }
    }

    if(document.form1.URL_ADD.value=="" && document.form1.FORMAT.value=="2")
    {
        alert("<?=_("请指定超级链接地址！")?>");
        return (false);
    }
    if(document.getElementById("TOP").checked!=false)
    {
        if(document.form1.TOP_DAYS.value!=""&&(document.form1.TOP_DAYS.value <0 || document.form1.TOP_DAYS.value!=parseInt(document.form1.TOP_DAYS.value)))
        {
            alert("<?=_("最大置顶时间应为正整数！")?>");
            return (false);
        }
        if(document.form1.TOP_FLAG.value!=0 && document.form1.TOP_DAYS.value!="" && parseInt(document.form1.TOP_DAYS.value) <=0)
        {
            alert("<?=_("最大置顶时间应为大于0的正整数！")?>");
            return (false);
        }
        if(document.form1.TOP_FLAG.value!=0 && document.form1.TOP_DAYS.value!="" && parseInt(document.form1.TOP_DAYS.value) > parseInt(document.form1.TOP_FLAG.value))
        {
            alert("<?=_("最大置顶时间不能大于系统设置中的最大置顶时间！")?>");
            return (false);
        }
    }
    /*
     if(getEditorHtml('CONTENT')=="" && document.form1.ATTACHMENT_ID_OLD.value=="" && document.form1.FORMAT.value=="0")
     {
     alert("<?=_("公告通知的内容不能为空！")?>");
 return (false);
 }
 */

    document.form1.OP.value="1";
    document.getElementById("BUTTON").disabled = "true";
    return (true);
}

function InsertImage(src)
{
    AddImage2Editor('CONTENT', src);
}
var count=0;
function sendForm(publish)
{
    document.form1.PUBLISH.value=publish;
    if(CheckForm())
    {
        document.form1.OP.value = publish=="0" ? "0" : "1";
        if(publish!=2){
            document.form1.submit();
            document.getElementById('BUTTON').disabled='disabled';
            document.form1.action="";
        }
        else
        {
            _edit();
        }

    }
}

function upload_attach()
{
    document.form1.PUBLISH.value="0";
    if(CheckForm())
    {
        document.form1.OP.value="0";
        document.form1.OP1.value="1";
        document.form1.submit();
    }
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
    var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
    if(window.confirm(msg))
    {
        URL="../delete_attach.php?NOTIFY_ID=<?=$NOTIFY_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
        window.location=URL;
    }
}

function changeFormat(typeID)
{
    if(typeID=="1")
    {
        document.getElementById("EDITOR").style.display="none";
        document.getElementById("tr_KEYWORD").style.display="none";
        document.getElementById("attachment1").style.display="";
        document.getElementById("url_address").style.display="none";
        document.getElementById("FORMAT").value="1";
        document.getElementById("status1").innerText="MHT<?=_("格式")?>";
        if(document.getElementById("add_image"))
            document.getElementById("add_image").style.display="none";
    }
    else if(typeID=="0")
    {
        document.getElementById("EDITOR").style.display="";
        document.getElementById("tr_KEYWORD").style.display="";
        document.getElementById("attachment1").style.display="";
        document.getElementById("url_address").style.display="none";
        document.getElementById("FORMAT").value="0";
        document.getElementById("status1").innerText="<?=_("普通格式")?>";
        if(document.getElementById("add_image"))
            document.getElementById("add_image").style.display="";
    }
    else if(typeID=="2")
    {
        document.getElementById("EDITOR").style.display="none";
        document.getElementById("tr_KEYWORD").style.display="none";
        document.getElementById("attachment1").style.display="none";
        document.getElementById("url_address").style.display="";
        document.getElementById("URL_ADD").value="http://";
        document.getElementById("FORMAT").value="2";
        document.getElementById("status1").innerText="<?=_("超级链接")?>";
    }
}

function changeRange()
{
    if (document.getElementById("rang_role").style.display=="none")
    {
        document.getElementById("rang_role").style.display="";
        document.getElementById("rang_user").style.display="";
        document.getElementById("href_txt").innerText="<?=_("隐藏按人员或角色发布")?>";
    }
    else
    {
        document.getElementById("rang_role").style.display="none";
        document.getElementById("rang_user").style.display="none";
        document.getElementById("href_txt").innerText="<?=_("按人员或角色发布")?>";
    }
}
function _edit()

{

    ShowDialog("optionBlock");
}

function SetNums()
{
    document.form1.submit();
}

function set_font_color(color)
{
    document.form1.SUBJECT_COLOR.value = color;
    $('SUBJECT').style.color = color;
    hideMenu();
}

function Load()
{
    $('font_color_link_menu').innerHTML=LoadForeColorTable_notify('set_font_color');
    _get("get_type.php","TYPE_ID=",show_msg);
    upload_init();
}
function show_msg(req)
{
    if(req.status==200)
    {
        if(req.responseText=="1")

        {
            $("IS_AU").value=1;
            $("BUTTON_ID").innerHTML="<input type='BUTTON' id='BUTTON' class='BigButtonB' value='<?=_("提交审批")?>'  onClick='sendForm(2);'>&nbsp;&nbsp;";
        }
        else
        {

            $("IS_AU").value=0;
            $("BUTTON_ID").innerHTML="<input type='button' id='BUTTON' class='BigButtonA' value='<?=_("发布")?>'  onClick='sendForm(1);'>&nbsp;&nbsp;";
        }

    }

}


function get_keyword()
{
    var txtCONTENT=getEditorText('CONTENT');
    if(txtCONTENT=="")
    {
        alert("<?=_("获取关键词前请先输入公告内容。")?>");
        return;
    }
    document.getElementById("tishi").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/loading.gif' height='20' width='20' align='absMiddle'> <?=_("正在搜索本文关键词……")?>";
    var httpReq=getXMLHttpObj();
    <?
    if(MYOA_IS_UN==1){
    ?>
    txtCONTENT=encodeURIComponent(txtCONTENT);
    <?
    }
    ?>
    httpReq.open("GET","/inc/get_keyword.php?MODULE_ID=NOTIFY&CONTENT="+txtCONTENT+"");
    httpReq.onreadystatechange=function(){
        if(httpReq.readyState==4){
            document.form1.KEYWORD.value=httpReq.responseText;
            document.getElementById("tishi").innerHTML="";

        }
    };
    httpReq.send(null);

}

function changeSelect(type_id)

{

    _get("type_ajax.php","type_id="+type_id,show_msg_name);
}

function show_msg_name(req)
{


    if(req.status==200)
    {
        if(req.responseText=="1")
        {
            $("IS_AU").value=1;
            $("BUTTON_ID").innerHTML="<input type='BUTTON' id='BUTTON'  class='BigButtonB' value='<?=_("提交审批")?>'  onClick='sendForm(2);'>&nbsp;&nbsp;";
        }
        else
        {
            $("IS_AU").value=0;
            $("BUTTON_ID").innerHTML="<input type='button' id='BUTTON' class='BigButtonA' value='<?=_("发布")?>'  onClick='sendForm(1);'>&nbsp;&nbsp;";
        }

    }
}

var upload_limit=oa_upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type=oa_limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
var swfupload;

function upload_init()
{
    var notifyId = document.getElementById("NOTIFY_ID").value;

    var settings = {
        flash_url : "<?=MYOA_JS_SERVER?>/module/swfupload/swfupload.swf",
        upload_url:"upload_attach_batch.php?NOTIFY_ID="+notifyId,
        post_params: {"SORT_ID" : "<?=$SORT_ID?>","URL" : "<?=$URL?>","ORDER_BY" : "<?=$ORDER_BY?>","ASC_DESC" : "<?=$ASC_DESC?>","start" : "<?=$start?>","PHPSESSID" : "<?=session_id()?>"},
        file_size_limit : "<?=intval(ini_get('upload_max_filesize'))?> MB",
        file_types : "<?=MYOA_UPLOAD_LIMIT!=2 ? "*.*" : "*.".str_replace(",",";*.",trim(trim(MYOA_UPLOAD_LIMIT_TYPE),","))?>",
        file_types_description : "<?=MYOA_UPLOAD_LIMIT!=2 ? _("所有") : trim(trim(MYOA_UPLOAD_LIMIT_TYPE),",")?> <?=_("文件")?>",
        file_upload_limit : 0,
        file_queue_limit : 0,
        custom_settings : {
            uploadArea : "fsUploadArea",
            progressTarget : "fsUploadProgress",
            startButtonId : "btnStart",
            cancelButtonId : "btnCancel"
        },
        debug: false,

        // Button Settings
        button_placeholder_id : "spanButtonUpload",
        button_width: 100,
        button_height: 100,
        button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
        button_cursor: SWFUpload.CURSOR.HAND,



        file_queued_handler : fileQueued,
        file_queue_error_handler : fileQueueError,
        file_dialog_complete_handler : fileDialogComplete,
        //upload_start_handler : uploadStart,
        upload_start_handler : uploadStartEventHandler,
        upload_progress_handler : uploadProgress,
        upload_error_handler : uploadError,
        upload_success_handler : uploadSuccessEventHandler,
        //upload_success_handler : uploadSuccess,
        upload_complete_handler : uploadComplete,
        //queue_complete_handler : queueComplete	// Queue plugin event
        queue_complete_handler : queueCompleteEventHandler


    };

    swfupload = new SWFUpload(settings);
};

function uploadStartEventHandler(file){

    var notifyId=document.getElementById("NOTIFY_ID").value;
    var subject=document.getElementById("SUBJECT").value;

    var subjectColor=document.form1.SUBJECT_COLOR.value;
    var format=document.form1.FORMAT.value;
    var toId=document.form1.TO_ID.value;
    var copyToId=document.form1.COPY_TO_ID.value;
    var privId=document.form1.PRIV_ID.value;
    var content = getEditorHtml('CONTENT');

    if (document.getElementById("PRINT") && document.getElementById("PRINT").checked) {
        var print = "on";
    } else {
        var print = "";
    }
    if(document.getElementById("DOWNLOAD") && document.getElementById("DOWNLOAD").checked)
    {
        var download = "on";
    }
    else
    {
        var download = "";
    }

    if(document.getElementById("TOP") && document.getElementById("TOP").checked)
    {
        var top="on";
        var topDay=document.form1.TOP_DAYS.value;
    }
    else
    {
        var top="";
        var topDay="";
    }

    var summary=document.form1.SUMMARY.value;
    var typeId=document.form1.TYPE_ID.value;
    var beginDate=document.form1.BEGIN_DATE.value;
    var endDate=document.form1.END_DATE.value;
    var keyWord=document.getElementById("KEYWORD").value;

    swfupload.addPostParam("NOTIFY_ID",notifyId);
    swfupload.addPostParam("SUBJECT",subject);
    swfupload.addPostParam("SUBJECT_COLOR",subjectColor);
    swfupload.addPostParam("FORMAT",format);
    swfupload.addPostParam("TO_ID",toId);
    swfupload.addPostParam("COPY_TO_ID",copyToId);
    swfupload.addPostParam("PRIV_ID",privId);
    swfupload.addPostParam("PUBLISH",0);
    swfupload.addPostParam("PRINT",print);
    swfupload.addPostParam("DOWNLOAD",download);
    swfupload.addPostParam("TOP",top);
    swfupload.addPostParam("TOP_DAYS",topDay);
    swfupload.addPostParam("TD_HTML_EDITOR_CONTENT", content);
    swfupload.addPostParam("SUMMARY",summary);
    swfupload.addPostParam("TYPE_ID",typeId);
    swfupload.addPostParam("BEGIN_DATE",beginDate);
    swfupload.addPostParam("END_DATE",endDate);
    swfupload.addPostParam("KEYWORD",keyWord);
    return true;
}

function uploadSuccessEventHandler(file, server_data) {

    var notifyId="";
    if (server_data) {

        //按照server_data的格式解析出三个变量，格式形如：BODY_ID:22,FW:33,REPLAY:44
        notifyId=server_data.split(":")[1];
    }

    document.getElementById("NOTIFY_ID").value = notifyId;

}

//整个队列上传成功之后，执行这个函数 by dq 090609
function queueCompleteEventHandler()
{
    var bodyIdRet = parseInt(document.getElementById("NOTIFY_ID").value);

    if(bodyIdRet!=0 && bodyIdRet!="" && !isNaN(bodyIdRet))
    {
        window.location = "modify.php?NOTIFY_ID=" + bodyIdRet+"&FROM=1&IS_MAIN=1";
    }
    else
    {
        alert("<?=_("上传文件文件名包含非法字符")?>");
        window.location = "index1.php";
    }
}

function resetTime()
{
    var today=new Date();
    var y = today.getFullYear();
    var M = today.getMonth()+1;
    var d = today.getDate();
    var h =today.getHours();
    var m =today.getMinutes();
    var s =today.getSeconds();
    M = checkTimes(M);
    d = checkTimes(d);
    h = checkTimes(h);
    m = checkTimes(m);
    s = checkTimes(s);
    var nowDate=y+"-"+M+ "-"+d+" "+h+":"+m+":"+s;
    document.form1.SEND_TIME.value=nowDate;
}
function checkTimes(i)
{
    if(i<10)
    {
        i="0" + i;
    }
    return i;
}
</script>


<body class="bodycolor" onLoad="Load();">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新建公告通知")?></span>&nbsp;&nbsp;
            <a id="status" href="javascript:;" class="dropdown" onClick="showMenu(this.id,'1');" hidefocus="true"><span id="status1"><?=$STATUS_DESC?></span></a>&nbsp;
            <div id="status_menu" class="attach_div">
                <a href="javascript:changeFormat(0);" style="color:#0000FF;"><?=_("普通格式")?></a>
                <a href="javascript:changeFormat(1);" style="color:#0000FF;" title="<?=_("mht格式支持图文混排，Word文档可以直接另存为mht文件；超级链接可直接链接到具体网址。")?>"><?=_("MHT格式")?></a>
                <a href="javascript:changeFormat(2);" style="color:#0000FF;"><?=_("超级链接")?></a>
            </div>
        </td>
    </tr>
</table>
<form enctype="multipart/form-data" action="add.php"  method="post" name="form1">
    <table class="TableBlock" width="95%" align="center">
        <tr>
            <td nowrap class="TableData">&nbsp;
                <select name="TYPE_ID" id="TYPE_ID" style="background: white;" title="<?=_("公告通知类型可在“系统管理”->“系统代码设置”模块设置。")?>" <?if ($NOTIFY_AUDITING_SINGLE_NEW!="" ){?> onChange="changeSelect(this.value)"<?}?>>
                    <option value=""><?=_("选择公告类型")?></option>
                    <?=code_list("NOTIFY","")?>
                </select></td>
            <td class="TableData" >
                <input type="text" name="SUBJECT" id="SUBJECT" size="55" maxlength="200" class="BigInput" value="<?=_("请输入公告标题")?>..." style="color: #8896A0"  style="color:<?=$FONT_COLOR=="#FFFFFF" ? "" : $FONT_COLOR?>;" onMouseOver="if(SUBJECT.value=='<?=_("请输入公告标题")?>...')SUBJECT.style.color='#000000';" onMouseOut="if(SUBJECT.value=='<?=_("请输入公告标题")?>...')SUBJECT.style.color='#8896A0';" onFocus="if(SUBJECT.value=='<?=_("请输入公告标题")?>...'){SUBJECT.value='';SUBJECT.style.color='#000000';}"><font color=red><?=_("(*)")?></font>
                <a id="font_color_link" href="javascript:;" class="dropdown" onClick="showMenu(this.id, 1);" hidefocus="true" ><span><?=_("设置标题颜色")?></span></a>&nbsp;&nbsp;
                <div id="font_color_link_menu" style="display:none;">
                </div>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData">&nbsp;<?=_("按部门发布：")?><br>&nbsp;<a href="javascript:;" id="href_txt" onClick="changeRange();"><?=_("按人员或角色发布")?></a></td>
            <td class="TableData">
                <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
                <textarea cols=40 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectDept('5')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
                <font color=red><?=_("(*)")?></font> <?=_("(发布范围取部门、人员和角色的并集)")?>
            </td>
        </tr>
        <tr id="rang_user" style="display:none">
            <td nowrap class="TableData">&nbsp;<?=_("按人员发布：")?></td>
            <td class="TableData">
                <input type="hidden" name="COPY_TO_ID" value="">
                <textarea cols=40 name="COPY_TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('24','5','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
            </td>
        </tr>
        <tr id="rang_role" style="display:none;">
            <td nowrap class="TableData">&nbsp;<?=_("按角色发布：")?></td>
            <td class="TableData">
                <input type="hidden" name="PRIV_ID" value="">
                <textarea cols=40 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectPriv('5','PRIV_ID', 'PRIV_NAME')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("清空")?></a><br>
            </td>
        </tr>
        <tr id="url_address" style="display:none">
            <td nowrap class="TableData"> &nbsp;<?=_("超级链接地址：")?></td>
            <td class="TableData">
                <input type="text" name="URL_ADD" id="URL_ADD" size="55" maxlength="500" class="BigInput" value="<?=$CONTENT?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> &nbsp;<?=_("发布时间：")?></td>
            <td class="TableData">
                <input type="text" name="SEND_TIME" size="20" maxlength="20" class="BigInput" value="<?=$SEND_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
                &nbsp;&nbsp;<a href="javascript:resetTime();"><?=_("重置为当前时间")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> &nbsp;<?=_("有效期：")?></td>
            <td class="TableData">
                <input type="text" id="start_time" name="BEGIN_DATE" size="10" maxlength="10" class="BigInput" value="<?=$CUR_DATE?>" onClick="WdatePicker()"/>
                &nbsp;<?=_("至")?>&nbsp;&nbsp;<input type="text" name="END_DATE" size="10" maxlength="10" class="BigInput" value="<?=$END_DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
                <?=_("为空为手动终止")?>
                <input type="hidden" name="CUR_DATE" value="<?=$CUR_DATE?>">
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> &nbsp;<?=_("事务提醒：")?></td>
            <td class="TableData" id="sms_remind">
                <?if (find_id($SMS3_REMIND, 1)){?>
                    <input type="checkbox" name="SMS_REMIND1" id="SMS_REMIND1"<?if(find_id($SMS_REMIND,"1")) echo " checked";?>><label for="SMS_REMIND1"><?=_("发送事务提醒消息")?></label>&nbsp;
                <?}

                /**START 工作流转发公告时表单内容查询并直接显示在公告的内容处 BY HSJ @ 2013-5-16 14:53:22 ***/
                $DELETE_PRIV = $EDIT_PRIV = 1;
                if($actionfrom == 'workflow')
                {
                    $DELETE_PRIV = $EDIT_PRIV = 0;
                    include_once("general/workflow/list/export_flow.php");
                    include_once("inc/workflow/inc/common.inc.php");
                    include_once("inc/workflow/inc/workflow.inc.php");
                    include_once("inc/workflow/tform/twork_fw.class.php");

                    $RUN_ID = intval($RUN_ID);

                    // $query = "SELECT * from FLOW_RUN where RUN_ID='$RUN_ID'";
                    // $cursor = exequery (TD::conn(), $query );
                    // if ($ROW = mysql_fetch_array ( $cursor )) {
                    // $WORKFLOW_ATTACHMENT_ID = $ROW ["ATTACHMENT_ID"];
                    // $WORKFLOW_ATTACHMENT_NAME = $ROW ["ATTACHMENT_NAME"];
                    // }
                    // include_once ("general/workflow/list/export_flow.php");
                    // $CONTENT = export_flow ( $RUN_ID , 0);

                    //获得表单所在库
                    $archive_time = $_GET["archive_time"];

                    if($archive_time != "" && $archive_time != "undefined")
                    {
                        $use_databases = "td_oa_archive.";
                    }
                    else
                    {
                        $use_databases = "";
                        $archive_time = "";
                    }

                    $flag_hidden_type = 1;
                    $table_flow_run_prcs = $use_databases."flow_run_prcs".$archive_time;

                    if($flag_hidden_type == 1)
                    {
                        //获得设计步骤号
                        $query = "select FLOW_PRCS from ".$table_flow_run_prcs." where RUN_ID = '".$RUN_ID."' and PRCS_ID = '".$PRCS_ID."' limit 1";
                        $cursor = exequery(TD::conn(),$query);
                        if($row = mysql_fetch_array($cursor))
                        {
                            $FLOW_PRCS = $row['FLOW_PRCS'];
                        }

                        //获得步骤保密字段
                        $table_flow_prcocess = $use_databases."flow_process".$archive_time;
                        $secret_fields = "";
                        $query = "select HIDDEN_ITEM from ".$table_flow_prcocess." where FLOW_ID = '".$FLOW_ID."' and PRCS_ID = '".$FLOW_PRCS."' ";
                        $cursor = exequery(TD::conn(),$query);
                        if($row = mysql_fetch_array($cursor))
                        {
                            $secret_fields = $row['HIDDEN_ITEM'];
                        }
                    }

                    $CONFIG = array(
                        'FLOW_VIEW' => $FLOW_VIEW,
                        'TYPE' => 'notify',
                        'db' => $use_databases,
                        'archive_time' => $archive_time,
                        'secret_fields' => $secret_fields,
                        'nl2br_flag' => true
                    );
                    $PRCS_KEY_ID = empty($_GET['prcs_key_id']) ? '' : intval($_GET['prcs_key_id']);
                    $obj_fw_notify = new TWorkFw($FLOW_ID, $RUN_ID, $PRCS_ID, $FLOW_PRCS, $PRCS_KEY_ID, $CONFIG);
                    $a_attachment = $obj_fw_notify->fw_el();

                    $ATTACHMENT_ID = $a_attachment["attachment_id"];    //附件ID串
                    $ATTACHMENT_NAME = $a_attachment["attachment_name"];    //附件名称串
                    $CONTENT = $a_attachment["original_content_html"];   //公告内容
                }
                /**END**/

                $query = "select * from SMS2_PRIV";
                $cursor=exequery(TD::conn(),$query);
                if($ROW=mysql_fetch_array($cursor))
                {
                    $TYPE_PRIV=$ROW["TYPE_PRIV"];
                    $SMS2_REMIND_PRIV=$ROW["SMS2_REMIND_PRIV"];
                }

                if(find_id($TYPE_PRIV,1) && find_id($SMS2_REMIND_PRIV, $_SESSION["LOGIN_USER_ID"])) //检查该模块是否允许手机提醒
                {
                    ?>
                    <input type="checkbox" name="SMS2_REMIND1" id="SMS2_REMIND1"<?if(find_id($SMS2_REMIND,"1")) echo " checked";?>><label for="SMS2_REMIND1"><?=_("使用手机短信提醒")?></label>
                    <?
                }

                if(find_id($SMS4_REMIND, '1'))
                {
                    echo "<label class='sms-remind-label'><input type=\"checkbox\" name=\"SNS_REMIND\" id=\"SNS_REMIND\"";
                    if(find_id($SMS5_REMIND, '1'))
                        echo " checked";
                    echo ">"._("分享到企业社区")."</label>";
                }
                ?>
            </td>
        </tr>

        <tr>
            <td nowrap class="TableData"> &nbsp;<?=_("置顶：")?></td>
            <td class="TableData"><input type="checkbox" name="TOP" id="TOP"><label for="TOP"><?=_("使公告通知置顶，显示为重要")?></label>
                &nbsp;&nbsp;<input type="text" name="TOP_DAYS" size="3" maxlength="4" onkeyup="this.value=this.value.replace(/\D/g,'')" class="BigInput" value="<?=$TOP_FLAG?>">&nbsp;<?=_("天后结束置顶，")?><?=$TOP_DAYS_REMIND?>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData">&nbsp;<?=_("内容简介：")?></td>
            <td class="TableData">
                <input class="BigInput" type="text" name="SUMMARY" cols="46" rows="2" size=60 maxlength="30" value="<?=$SUMMARY?>">(<?=_("最多输入30个字")?>)
            </td>
        </tr>
        <?if($ATTACHMENT_ID!="" && $ATTACHMENT_NAME!=""){?>
            <tr class="TableData" id="attachment2">
                <td nowrap><?=_("附件文档：")?></td>

                <td nowrap><?if($FW_FLAG!='1') echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,$EDIT_PRIV,$DELETE_PRIV,1,1,1); else{ if($OP_FLAG!='11')echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,$OP_FLAG);else echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,$EDIT_PRIV,$DELETE_PRIV,1,1,1);}?></td>
            </tr>
        <?}?>
        <tr id="attachment1" >
            <td nowrap class="TableData"> &nbsp;<?=_("附件与权限：")?></td>
            <td class="TableData">
                <div>
                    <script>ShowAddFile();ShowAddImage();ShowAddImageMulti();</script>&nbsp;
                    <script>$("ATTACHMENT_upload_div").innerHTML='<a href="javascript:upload_attach();"><?=_("上传附件")?></a>'</script>
                    <a class="add_swfupload" href="javascript:void(0)"><?=_("批量上传")?><span id="spanButtonUpload" title="<?_("批量上传附件")?>"></span></a>
                    <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
                    <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>"><br>
                    <input type="checkbox" name="DOWNLOAD" id="DOWNLOAD" checked><label for="DOWNLOAD"><?=_("允许下载")?>Office<?=_("附件")?></label>&nbsp;&nbsp;
                    <input type="checkbox" name="PRINT" id="PRINT" checked><label for="PRINT"><?=_("允许打印")?>Office<?=_("附件")?></label>&nbsp;&nbsp;&nbsp;<font color="gray"><?=_("都不选中则只能阅读附件内容")?></font>
                </div>
            </td>
        </tr>
        <tr id="EDITOR">
            <td class="TableData" colspan="2">
                <?
                $editor = new Editor('CONTENT') ;
                $editor->Height = '450';
                $editor->Value = $CONTENT ;
                $editor->Config = array("contentsCss" => "body{font-size:".$FONT_SIZE."pt;}","model_type" =>"04");
                $editor->Create() ;
                ?>
            </td>
        </tr>
        <tr id="tr_KEYWORD">
            <td nowrap class="TableData"> <?=_("关键词：")?></td>
            <td class="TableData">
                <input type="text" name="KEYWORD" id='KEYWORD'  size=50><span id="tishi"></span><a href='javascript:get_keyword();' class='A1'><?=_("自动获取关键词")?></a></span>&nbsp;&nbsp;&nbsp;(<?=_("您可以调整关键词内容，多个关键词请用,分隔")?>)
            </td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan="2" nowrap>
                <input type="hidden" name="PUBLISH" value="">
                <input type="hidden" name="SUBJECT_COLOR" value="">
                <input type="hidden" name="OP" value="">
                <input type="hidden" name="OP1" value="">
                <input type="hidden" name="TOP_FLAG" value="<?=$TOP_FLAG?>">
                <?
                if ($NOTIFY_AUDITING_SINGLE!=1 && $NOTIFY_AUDITING_SINGLE_NEW==""  || find_id($NOTIFY_AUDITING_EXCEPTION, $_SESSION["LOGIN_USER_ID"]))
                {
                    ?>
                    <span id="BUTTON_ID"><input type="button" id="BUTTON" value="<?=_("发布")?>"  class="BigButtonA"  onClick="sendForm('1'); ">&nbsp;&nbsp;</span>
                    <?
                }
                else
                {
                    ?>
                    <span id="BUTTON_ID"><input type="button" id="BUTTON" value="<?=_("提交审批")?>"  class="BigButtonB"  onClick="sendForm('2');">&nbsp;&nbsp;</span>
                    <?
                }
                ?>
                <input type="button" value="<?=_("保存")?>" class="BigButton" onClick="sendForm('0');">&nbsp;&nbsp;
                <!--<input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close()">&nbsp;&nbsp;-->
            </td>
        </tr>
    </table>
    <input type="hidden" name="FORMAT" id="FORMAT" value="0">


    <?

    if(($NOTIFY_AUDITING_SINGLE==1 && $NOTIFY_AUDITING_SINGLE_NEW=="") || !find_id($NOTIFY_AUDITING_EXCEPTION,$_SESSION["LOGIN_USER_ID"]) )
    {
        ?>
        <div id="overlay"></div>
        <div id="optionBlock" class="ModalDialog" style="display:none;width:350px;">
            <h4 class="header" style="margin-top:0px;"><span id="optionBlockTitle" class="title"><?=_("提交审批")?></span><a class="operation" href="javascript:HideDialog('optionBlock');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/close.png"/></a></h4>
            <center>
                <table border="0px" style="font-size:9pt;padding:10px;line-height:23px;">
                    <tr><td>&nbsp;<?=_("审批人：");$query="select MANAGER from DEPARTMENT where DEPT_ID ='".$_SESSION["LOGIN_DEPT_ID"]."'";
                            ?></td>
                        <td ><select name="AUDITER" class="BigSelect" id="AUDITER">
                                <?
                                if($NOTIFY_AUDITING_MANAGER==1)
                                {
                                    $query="select MANAGER from DEPARTMENT where DEPT_ID ='".$_SESSION["LOGIN_DEPT_ID"]."'";
                                    $cursor= exequery(TD::conn(),$query);
                                    if($ROW=mysql_fetch_array($cursor))
                                        $AUDITER_ID=td_trim($ROW["MANAGER"]);

                                    $AUDITER_ID=explode(",",$AUDITER_ID);
                                    for($I=0;$I<sizeof($AUDITER_ID);$I++)
                                    {
                                        if ($AUDITER_ID[$I]!="")
                                        {
                                            $AUDITER_NAME=td_trim(GetUserNameById($AUDITER_ID[$I]));
                                            echo "<option value='$AUDITER_ID[$I]'>$AUDITER_NAME</option>";
                                        }
                                    }
                                    $AUDITER_ID=explode(",",$AUDITER_ALL_ID);

                                    for($I=0;$I<sizeof($AUDITER_ID);$I++)
                                    {
                                        if ($AUDITER_ID[$I]!="")
                                        {
                                            if(find_id($ROW["MANAGER"],$AUDITER_ID[$I]))
                                                continue;

                                            $AUDITER_NAME=td_trim(GetUserNameById($AUDITER_ID[$I]));
                                            echo "<option value='$AUDITER_ID[$I]'>$AUDITER_NAME</option>";
                                        }
                                    }

                                }
                                else
                                {
                                    $AUDITER_ID=explode(",",$AUDITER_ALL_ID);

                                    for($I=0;$I<sizeof($AUDITER_ID);$I++)
                                    {
                                        if ($AUDITER_ID[$I]!="")
                                        {
                                            $AUDITER_NAME=td_trim(GetUserNameById($AUDITER_ID[$I]));
                                            echo "<option value='$AUDITER_ID[$I]'>$AUDITER_NAME</option>";
                                        }
                                    }
                                }
                                ?>
                            </select></td>
                    </tr>
                    <tr><td>&nbsp;<?=_("提醒审批人：")?></td><td id="AUDITER_REMIND"><?=sms_remind(1);?></td></tr>
                    <tr><td>&nbsp;<td><tr>
                    <tr align="center">
                        <?
                        if(count($AUDITER_ID)>0)
                        {
                        ?>
                        <td colspan="2" nowrap><input class="BigButton" onClick="document.form1.submit();" type="button" value="<?=_("确定")?>"/>&nbsp;&nbsp;
                            <?
                            }else{
                            ?>
                        <td colspan="2" nowrap><input class="BigButton" onClick="window.alert('无审批人，请联系系统管理员进行设置！');" type="button" value="<?=_("确定")?>"/>&nbsp;&nbsp;
                            <?
                            }
                            ?>
                            <input class="BigButton" onClick="HideDialog('optionBlock')" type="button" value="<?=_("取消")?>"/></td></tr>
                    <tr><td>&nbsp;</td></tr>
                </table>
            </center>
        </div>
        <?
    }
    ?>

    <input type="hidden" id="IS_AU" name="IS_AU" value="">
    <input type="hidden" id="NOTIFY_ID"  value="" >
    <input type="hidden" name="FLOW_ID" id="FLOW_ID"  value="<?=$FLOW_ID?>" >
    <input type="hidden" name="RUN_ID" id="RUN_ID"  value="<?=$RUN_ID?>" >
</form>

<div id="fsUploadArea" class="flash" style="left:180px;top:277px;position:absolute;width:380px;z-index:2;">
    <div id="fsUploadProgress"></div>
    <div id="totalStatics" class="totalStatics"></div>
    <div>
        <input type="button" id="btnStart" class="SmallButton" value="<?=_("开始上传")?>" onClick="swfupload.startUpload();" disabled="disabled">&nbsp;&nbsp;
        <input type="button" id="btnCancel" class="SmallButton" value="<?=_("全部取消")?>" onClick="swfupload.cancelQueue();" disabled="disabled">&nbsp;&nbsp;
    </div>
</div>

</body>
</html>