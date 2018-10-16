<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");
include_once("inc/utility_cache.php");
include_once("inc/editor.php");

if($IS_MAIN==1)
    $QUERY_MASTER=true;
else
    $QUERY_MASTER="";

$PARA_ARRAY = get_sys_para("SMS_REMIND");
$PARA_VALUE=$PARA_ARRAY["SMS_REMIND"];
$REMIND_ARRAY = explode("|", $PARA_VALUE);
$SMS_REMIND = $REMIND_ARRAY[0];
$SMS2_REMIND = $REMIND_ARRAY[1];
$SMS3_REMIND = $REMIND_ARRAY[2];
$SMS4_REMIND = $REMIND_ARRAY[3];
$SMS5_REMIND = $REMIND_ARRAY[4];

$FONT_SIZE = get_font_size("FONT_NOTIFY", 12);
//-------用户管理范围-------
$POST_PRIV=GetUserInfoByUID($_SESSION["LOGIN_UID"],"POST_PRIV");
$PARA_ARRAY=get_sys_para("NOTIFY_AUDITING_SINGLE,NOTIFY_TOP_DAYS,NOTIFY_AUDITING_EXCEPTION,NOTIFY_AUDITING_ALL,NOTIFY_AUDITING_MANAGER,NOTIFY_AUDITING_SINGLE_NEW");

$NOTIFY_AUDITING_SINGLE=$PARA_ARRAY["NOTIFY_AUDITING_SINGLE"];//是否需要审批
$NOTIFY_AUDITING_SINGLE_NEW=$PARA_ARRAY["NOTIFY_AUDITING_SINGLE_NEW"];
$NOTIFY_AUDITING_EXCEPTION=$PARA_ARRAY["NOTIFY_AUDITING_EXCEPTION"];

$NOTIFY_TYPE_ARRAY=explode(",", $NOTIFY_AUDITING_SINGLE_NEW);
for ($I=0;$I<count($NOTIFY_TYPE_ARRAY);$I++)
{
    $TYPE_ID_ARRAY=explode("-", $NOTIFY_TYPE_ARRAY[$I]);
    if ($TYPE_ID_ARRAY[0]!=0)
        $TYPE_ID_STR.=$TYPE_ID_ARRAY[0].",";
}


$TOP_DAYS=$PARA_ARRAY["NOTIFY_TOP_DAYS"]; //最大置顶日期
$NOTIFY_AUDITING_MANAGER=$PARA_ARRAY["NOTIFY_AUDITING_MANAGER"];
$AUDITER_ALL_ID = $PARA_ARRAY["NOTIFY_AUDITING_ALL"];
$AUDITER_ALL_ID = td_trim($AUDITER_ALL_ID);

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

$CUR_DATE=date("Y-m-d",time());
$query="select * from NOTIFY where NOTIFY_ID='$NOTIFY_ID'";
if($_SESSION["LOGIN_USER_PRIV"]!="1"&&$POST_PRIV!="1")
{
    if($IS_AUDITING_EDIT==1)
        $query.=" and AUDITER='".$_SESSION["LOGIN_USER_ID"]."'";
    //else
    //   $query.=" and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
}
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
{
    $FROM_ID=$ROW["FROM_ID"];
    $TO_ID=$ROW["TO_ID"];
    $SUBJECT=$ROW["SUBJECT"];
    $SUMMARY=$ROW["SUMMARY"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
    $PRINT=$ROW["PRINT"];
    $DOWNLOAD=$ROW["DOWNLOAD"];
    $FORMAT=$ROW["FORMAT"];
    $TOP=$ROW["TOP"];
    $TOP_DAYS=$ROW["TOP_DAYS"];
    $PRIV_ID=$ROW["PRIV_ID"];
    $USER_ID=$ROW["USER_ID"];
    $TYPE_ID=$ROW["TYPE_ID"];
    $PUBLISH_OLD=$ROW["PUBLISH"];
    $AUDITER=$ROW["AUDITER"];
    $SEND_TIME=$ROW["SEND_TIME"];
    $FW_FLAG=$ROW["IS_FW"];
    $READERS=$ROW["READERS"];
    if($PUBLISH_OLD!=1 && $SEND_TIME<date("Y-m-d H:i:s",time()))//如果不是已批准
        $SEND_TIME=date("Y-m-d H:i:s",time());
    $SUBJECT_COLOR=$ROW["SUBJECT_COLOR"];
    $KEYWORD=td_htmlspecialchars($ROW["KEYWORD"]);
    if($PRINT!="1"&&$DOWNLOAD!="1")
        $OP_FLAG="00";
    if($PRINT=="1"&&($DOWNLOAD=="1"||$DOWNLOAD==""))
        $OP_FLAG="11";
    if($OP_FLAG=="")
        $OP_FLAG=$DOWNLOAD.$PRINT;
    $COMPRESS_CONTENT=@gzuncompress($ROW["COMPRESS_CONTENT"]);
    if($COMPRESS_CONTENT!=""&&$FORMAT!=2)
        $CONTENT=$COMPRESS_CONTENT;
    else
        $CONTENT=$ROW["CONTENT"];
    //if($FROM_ID!=$_SESSION["LOGIN_USER_ID"] && $_SESSION["LOGIN_USER_PRIV"]!=1 && $POST_PRIV!=1 && $AUDITER!=$_SESSION["LOGIN_USER_ID"])
    //   exit;
    $IMG_TYPE_STR="gif,jpg,png,tiff,bmp,iff,jp2,jpx,jb2,jpc,xbm,wbmp,";
    $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
    $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);
    $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
    for($I=0;$I<$ARRAY_COUNT;$I++)
    {
        if($ATTACHMENT_ID_ARRAY[$I]=="")
            continue;
        if(is_image($ATTACHMENT_NAME_ARRAY[$I]))
        {
            $ATTACHMENT_ID1=$ATTACHMENT_ID_ARRAY[$I];
            $YM=substr($ATTACHMENT_ID1,0,strpos($ATTACHMENT_ID1,"_"));
            if($YM)
                $ATTACHMENT_ID1=substr($ATTACHMENT_ID1,strpos($ATTACHMENT_ID1,"_")+1);
            $IMG_YM.=$YM.",";
            $IMG_ATTACHMENT_ID.=attach_id_encode($ATTACHMENT_ID1,$ATTACHMENT_NAME_ARRAY[$I]).",";
            $IMG_ATTACHMENT_NAME.=$ATTACHMENT_NAME_ARRAY[$I]."*";
        }
    }
    if($TO_ID!=""&&$USER_ID==""&&$PRIV_ID=="")
        $href_dept="<br>&nbsp;<a href='javascript:;' id='href_txt' onclick='changeRange();'>"._("按人员或角色发布")."</a>";
    if($TO_ID!=""&&$USER_ID!=""&&$PRIV_ID=="")
        $href_dept="<br>&nbsp;<a href='javascript:;' id='href_txt' onclick='changeRange();'>"._("按角色发布")."</a>";
    if($TO_ID!=""&&$USER_ID==""&&$PRIV_ID!="")
        $href_dept="<br>&nbsp;<a href='javascript:;' id='href_txt' onclick='changeRange();'>"._("按人员发布")."</a>";
    if($TO_ID==""&&$USER_ID!=""&&$PRIV_ID=="")
        $href_user="<br>&nbsp;<a href='javascript:;' id='href_txt' onclick='changeRange();'>"._("按部门或角色发布")."</a>";
    if($TO_ID==""&&$USER_ID!=""&&$PRIV_ID!="")
        $href_user="<br>&nbsp;<a href='javascript:;' id='href_txt' onclick='changeRange();'>"._("按部门发布")."</a>";
    if($TO_ID==""&&$USER_ID==""&&$PRIV_ID!="")
        $href_priv="<br>&nbsp;<a href='javascript:;' id='href_txt' onclick='changeRange();'>"._("按部门或人员发布")."</a>";
    if($TO_ID==""&&$USER_ID==""&&$PRIV_ID=="")
    {
        $ALL_BLAMK="1";
        $href_dept="<br>&nbsp;<a href='javascript:;' id='href_txt' onclick='changeRange();'>"._("按人员或角色发布")."</a>";
    }
}
else
    exit;

$BEGIN_DATE=date("Y-m-d",$BEGIN_DATE);
if($END_DATE==0)
    $END_DATE="";
else
    $END_DATE=date("Y-m-d",$END_DATE);

$query="select DEPT_ID,DEPT_NAME from DEPARTMENT";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $DEPT_ID=$ROW["DEPT_ID"];
    $DEPT_NAME=$ROW["DEPT_NAME"];
    if(find_id($TO_ID,$DEPT_ID))
        $TO_NAME.=$DEPT_NAME.",";
}
if($TO_ID=="ALL_DEPT")
    $TO_NAME=_("全体部门");

$TOK=strtok($PRIV_ID,",");
while($TOK!="")
{
    $query1 = "SELECT PRIV_NAME from USER_PRIV where USER_PRIV='$TOK'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
        $PRIV_NAME.=$ROW["PRIV_NAME"].",";
    $TOK=strtok(",");
}

$TOK=strtok($USER_ID,",");
while($TOK!="")
{
    $query1 = "SELECT USER_NAME from USER where USER_ID='$TOK'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor1))
        $USER_NAME.=$ROW["USER_NAME"].",";
    $TOK=strtok(",");
}

if($FORMAT=="0")
    $STATUS_DESC="<font color='#0000FF'>"._("普通格式")."</font>";
if($FORMAT=="1")
    $STATUS_DESC="<font color='#0000FF'>"._("MHT格式")."</font>";
if($FORMAT=="2")
    $STATUS_DESC="<font color='#0000FF'>"._("超级链接")."</font>";


$HTML_PAGE_TITLE = _("编辑公告通知");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/swfupload.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/module/upload/uploader.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/mytable.js"></script>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/swfupload/swfupload.queue.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/swfupload/fileprogress.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/swfupload/handlers.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/upload/uploader.js"></script>
<script language="javascript" src="<?=MYOA_JS_SERVER?>/static/js/tabopt.js"></script>

<script Language="JavaScript">
confirmSaveBeforeCloseTab("notify_modify", "", "");
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
    var value=document.getElementById("TYPE_ID").value;
    if(document.form1.TO_ID.value==""&&document.form1.PRIV_ID.value==""&&document.form1.COPY_TO_ID.value=="")
    {
        alert("<?=_("请指定发布范围！")?>");
        return (false);
    }

    if(document.form1.SUBJECT.value.trim()=="")
    {
        alert("<?=_("公告通知的标题不能为空！")?>");
        return (false);
    }
    var end_date = document.form1.END_DATE.value;
    var begin_date = document.form1.BEGIN_DATE.value;
    var now_date=document.form1.CUR_DATE.value;
    var v_now = new Date(Date.parse(now_date.replace(/-/g,"/")));
    var v_begin = new Date(Date.parse(begin_date.replace(/-/g, "/")));
    var v_end = new Date(Date.parse(end_date.replace(/-/g, "/")));

    if(v_end!="" && v_end <=v_begin)
    {
        alert("<?=_("终止日期应大于生效日期！")?>");
        document.form1.END_DATE.focus();
        return (false);
    }
    if(document.form1.FORMAT.value=="1" && document.form1.ATTACHMENT_ID_OLD.value=="")
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
    if(getEditorHtml('CONTENT')=="" && document.form1.ATTACHMENT_ID_OLD.value=="" && document.form1.FORMAT.value=="0")
    {
        alert("<?=_("公告通知的内容不能为空！")?>");
        return (false);
    }

    return (true);
}

function InsertImage(src)
{
    AddImage2Editor('CONTENT', src);
}

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
        }else{
            _edit();
        }
    }
}

function _edit()
{
    ShowDialog("optionBlock");
}

function upload_attach()
{
    var PUBLISH_UPLOADATTACH=document.form1.PUBLISH_UPLOADATTACH.value;
    if(PUBLISH_UPLOADATTACH!="")
        document.form1.PUBLISH.value=PUBLISH_UPLOADATTACH;
    else
        document.form1.PUBLISH.value=0;
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
        URL="delete_attach.php?FROM=<?=$FROM?>&NOTIFY_ID=<?=$NOTIFY_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
        window.location=URL;
    }
}
function changeFormat(typeID)
{
    if(typeID=="1")
    {
        document.getElementById("EDITOR").style.display="none";
        document.getElementById("attachment1").style.display="";
        document.getElementById("attachment2").style.display="";
        document.getElementById("url_address").style.display="none";
        document.getElementById("FORMAT").value="1";
        document.getElementById("status1").innerText="MHT<?=_("格式")?>";
        if(document.getElementById("add_image"))
            document.getElementById("add_image").style.display="none";
    }
    else if(typeID=="0")
    {
        document.getElementById("EDITOR").style.display="";
        document.getElementById("attachment1").style.display="";
        document.getElementById("attachment2").style.display="";
        document.getElementById("url_address").style.display="none";
        document.getElementById("FORMAT").value="0";
        document.getElementById("status1").innerText="<?=_("普通格式")?>";
        if(document.getElementById("add_image"))
            document.getElementById("add_image").style.display="";
    }
    else if(typeID=="2")
    {
        document.getElementById("EDITOR").style.display="none";
        document.getElementById("attachment1").style.display="none";
        document.getElementById("attachment2").style.display="";
        document.getElementById("url_address").style.display="";
        document.getElementById("FORMAT").value="2";
        document.getElementById("status1").innerText="<?=_("超级链接")?>";
    }
}

function changeRange()
{
    var txtTemp=document.getElementById("href_txt").innerText;
    if(txtTemp.indexOf("<?=_("按")?>")!=-1)
        txtTemp=txtTemp.replace("<?=_("按")?>","<?=_("隐藏")?>");
    else
        txtTemp=txtTemp.replace("<?=_("隐藏")?>","<?=_("按")?>");
    document.getElementById("href_txt").innerText=txtTemp;
    if(document.getElementById("rang_role").style.display=="none")
    {
        document.getElementById("rang_role").style.display="";
    }
    else
    {
        <? if($PRIV_ID=="") echo "document.getElementById('rang_role').style.display='none';";?>

    }

    if(document.getElementById("rang_user").style.display=="none")
    {
        document.getElementById("rang_user").style.display="";
    }
    else
    {
        <? if($USER_ID=="") echo "document.getElementById('rang_user').style.display='none';";?>
    }

    if(document.getElementById("rang_dept").style.display=="none")
    {
        document.getElementById("rang_dept").style.display="";
    }
    else
    {
        <? if($TO_ID==""&&$ALL_BLAMK!="1") echo "document.getElementById('rang_dept').style.display='none';";?>

    }
}
function resetTime()
{
    var today=new Date();
    var y= today.getFullYear();
    var M = today.getMonth()+1;
    var d = today.getDate();
    var h=today.getHours();
    var m=today.getMinutes();
    var s=today.getSeconds();
    M=checkTimes(M);
    d=checkTimes(d);
    h=checkTimes(h);
    m=checkTimes(m);
    s=checkTimes(s);
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

function set_font_color(color)
{
    document.form1.SUBJECT_COLOR.value = color;
    $('SUBJECT').style.color = color;
    hideMenu();
}

function Load(from)
{
    $('font_color_link_menu').innerHTML=LoadForeColorTable_notify('set_font_color');
    upload_init();
    if(from!=2)
        _get("get_type.php","TYPE_ID=<?=$TYPE_ID?>",show_msg);
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
    <?
    if(MYOA_IS_UN==1)
    {
    ?>
    txtCONTENT=encodeURIComponent(txtCONTENT);
    <?
    }
    ?>
    document.getElementById("showKeyword").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/loading.gif' height='20' width='20' align='absMiddle'> <?=_("正在搜索本文关键词……")?>";
    var httpReq=getXMLHttpObj();

    httpReq.open("GET","/inc/get_keyword.php?MODULE_ID=NOTIFY&CONTENT="+txtCONTENT+"");
    httpReq.onreadystatechange=function(){
        if(httpReq.readyState==4){
            document.form1.KEYWORD.value=httpReq.responseText;
            document.getElementById("showKeyword").innerHTML="";
        }
    };
    httpReq.send(null);

}


function changeSelect(type_id)

{
    _get("type_ajax.php","type_id="+type_id,show_msg_name)
}

function show_msg_name(req)
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

var upload_limit=oa_upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type=oa_limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
var swfupload;

function upload_init(){
    notifyId=document.getElementById("NOTIFY_ID").value;

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

    var PUBLISH_UPLOADATTACH=document.form1.PUBLISH_UPLOADATTACH.value;
    if(PUBLISH_UPLOADATTACH!="")
        document.form1.PUBLISH.value=PUBLISH_UPLOADATTACH;
    else
        document.form1.PUBLISH.value=0;

    var notifyId=document.getElementById("NOTIFY_ID").value;
    var subject=document.getElementById("SUBJECT").value;
    //subjectColor=document.getElementById("SUBJECT_COLOR").value;
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
    var isAuditingEdit=document.form1.IS_AUDITING_EDIT.value;
    var postPriv=document.form1.POST_PRIV.value;
    var keyWord=document.getElementById("KEYWORD").value;
    var publish=document.form1.PUBLISH.value;


    swfupload.addPostParam("NOTIFY_ID",notifyId);
    swfupload.addPostParam("SUBJECT",subject);
    swfupload.addPostParam("SUBJECT_COLOR",subjectColor);
    swfupload.addPostParam("FORMAT",format);
    swfupload.addPostParam("TO_ID",toId);
    swfupload.addPostParam("COPY_TO_ID",copyToId);
    swfupload.addPostParam("PRIV_ID",privId);
    //swfupload.addPostParam("PUBLISH",0);
    swfupload.addPostParam("PUBLISH",publish);
    swfupload.addPostParam("PRINT",print);
    swfupload.addPostParam("DOWNLOAD",download);
    swfupload.addPostParam("TOP",top);
    swfupload.addPostParam("TOP_DAYS",topDay);
    swfupload.addPostParam("TD_HTML_EDITOR_CONTENT", content);
    swfupload.addPostParam("SUMMARY",summary);
    swfupload.addPostParam("TYPE_ID",typeId);
    swfupload.addPostParam("BEGIN_DATE",beginDate)
    swfupload.addPostParam("END_DATE",endDate);
    swfupload.addPostParam("IS_AUDITING_EDIT",isAuditingEdit);
    swfupload.addPostParam("POST_PRIV",postPriv);
    swfupload.addPostParam("KEYWORD",keyWord);
    return true;
}

function uploadSuccessEventHandler(file, server_data) {

    var notifyId="";
    if (server_data) {
        //var tmpArray1 = server_data.split(",");

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
        window.location = "modify.php?NOTIFY_ID=" + bodyIdRet+"&FROM=<?=$FROM?>&IS_MAIN=1";
    }
    else
    {
        alert("<?=_("上传文件的文件名中包含非法字符")?>");
        window.location = "index1.php";
    }
}
</script>

<br>
<body class="bodycolor"  onLoad="changeFormat('<?=$FORMAT?>'); form1.SUBJECT.focus();Load('<?=$FROM?>');">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("编辑公告通知")?></span>&nbsp;&nbsp;
            <a id="status" href="javascript:;" class="dropdown" onClick="showMenu(this.id,'1');" hidefocus="true"><span id="status1"><?//=$STATUS_DESC?></span></a>&nbsp;
            <div id="status_menu" class="attach_div">
                <a href="javascript:changeFormat(0);" style="color:#0000FF;"><?=_("普通格式")?></a>
                <a href="javascript:changeFormat(1);" style="color:#0000FF;" title="<?=_("mht格式支持图文混排?琖ord文档可以直接另存为mht文件；超级链接可直接链接到具体网址。")?>"><?=_("MHT格式")?></a>
                <a href="javascript:changeFormat(2);" style="color:#0000FF;"><?=_("超级链接")?></a>
            </div>
        </td>
    </tr>
</table>
<form enctype="multipart/form-data" action="update.php"  method="post" name="form1">
    <table class="TableBlock" width="95%" align="center">
        <tr>
            <td nowrap class="TableData">
                <select name="TYPE_ID" id="TYPE_ID" style="background: white;" title="<?=_("公告通知类型可在“系统管理”->“系统代码设置”模块设置。")?>" <?if ($NOTIFY_AUDITING_SINGLE_NEW!="" && $FROM!=2){?>onchange="changeSelect(this.value)"<?}?>>
                    <option value=""<?if($TYPE_ID=="") echo " selected";?>><?=_("选择公告类型")?></option>
                    <?=code_list("NOTIFY",$TYPE_ID)?>
                </select></td>
            <td class="TableData">
                <input id="SUBJECT" type="text" name="SUBJECT" size="55" maxlength="200" class="BigInput" value="<?=td_htmlspecialchars($SUBJECT)?>" style="color:<?=$SUBJECT_COLOR=="#FFFFFF" ? "" : $SUBJECT_COLOR?>;"><font color="red">(*)</font>
                <a id="font_color_link" href="javascript:;" class="dropdown" onClick="showMenu(this.id, 1);" hidefocus="true" ><span><?=_("设置标题颜色")?></span></a>&nbsp;&nbsp;
                <div id="font_color_link_menu" style="display:none;">
                </div>
            </td>
        </tr>
        <tr id="rang_dept" <?if($TO_ID==""  && $ALL_BLAMK!="1") echo "style='display:none'";?>>
            <td nowrap class="TableData"><?=_("按部门发布：")?><?=$href_dept?></td>
            <td class="TableData">
                <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
                <textarea cols=40 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectDept('5','','','')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a><font color="red">(*)</font>
            </td>
        </tr>
        <tr id="rang_user" <?if($USER_ID=="") echo "style='display:none'";?> >
            <td nowrap class="TableData"><?=_("按人员发布：")?><?=$href_user?></td>
            <td class="TableData">
                <input type="hidden" name="COPY_TO_ID" value="<?=$USER_ID?>">
                <textarea cols=40 name="COPY_TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$USER_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectUser('24','5','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
            </td>
        </tr>
        <tr id="rang_role" <?if($PRIV_ID=="" ) echo "style='display:none'";?>>
            <td nowrap class="TableData"><?=_("按角色发布：")?><?=$href_priv?></td>
            <td class="TableData">
                <input type="hidden" name="PRIV_ID" value="<?=$PRIV_ID?>">
                <textarea cols=40 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$PRIV_NAME?></textarea>
                <a href="javascript:;" class="orgAdd" onClick="SelectPriv('5','PRIV_ID', 'PRIV_NAME')"><?=_("添加")?></a>
                <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("清空")?></a><br>
                <?=_("发布范围取部门、人员和角色的并集")?>
            </td>
        </tr>
        <tr id="url_address" style="display:none">
            <td nowrap class="TableData"> <?=_("超级链接地址：")?></td>
            <td class="TableData">
                <input type="text" name="URL_ADD" size="55" maxlength="500" class="BigInput" value="<?=td_htmlspecialchars($CONTENT)?>">
            </td>
        </tr>
        <tr> <td nowrap class="TableData"> <?=_("发布时间：")?></td>
            <td class="TableData">
                <input type="text" name="SEND_TIME" size="20" maxlength="20" class="BigInput" value="<?=$SEND_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
                &nbsp;&nbsp;<a href="javascript:resetTime();"><?=_("重置为当前时间")?></a>
            </td>
        </tr>
        <tr>
            <td nowrap class="TableData"> <?=_("有效期：")?></td>
            <td class="TableData">
                <input type="text" name="BEGIN_DATE" size="10" maxlength="10" class="BigInput" value="<?=$BEGIN_DATE?>" onClick="WdatePicker()"/>
                &nbsp;<?=_("至")?>&nbsp;&nbsp;<input type="text" name="END_DATE" size="10" maxlength="10" class="BigInput" value="<?=$END_DATE?>" onClick="WdatePicker()"/>
                <?=_("为空为手动终止")?>
                <input type="hidden" name="CUR_DATE" value=<?=$CUR_DATE?>>
            </td>
        </tr>

        <tr class="TableData" id="attachment2">
            <td nowrap><?=_("附件文档：")?></td>

            <td><?if($FW_FLAG!='1') echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1); else{ if($OP_FLAG!='11')echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,$OP_FLAG);else echo attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1);}?></td>
        </tr>

        <tr height="25" id="attachment1">
            <td nowrap class="TableData"><?=_("附件与权限：")?></td>
            <td class="TableData">
                <script>ShowAddFile();ShowAddImage();ShowAddImageMulti();</script>
                <script>$("ATTACHMENT_upload_div").innerHTML='<a href="javascript:upload_attach();"><?=_("上传附件")?></a>'</script>&nbsp;
                <a class="add_swfupload" href="javascript:void(0)"><?=_("批量上传")?><span id="spanButtonUpload" title="<?_("批量上传附件")?>"></span></a>
                <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
                <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>"><br>
                <input type="checkbox" name="DOWNLOAD" id="DOWNLOAD" <?if($DOWNLOAD=='1') echo "checked"; if($FW_FLAG=='1' && $DOWNLOAD!="1") echo " disabled";?>><label for="DOWNLOAD"><?=_("允许下载")?>Office<?=_("附件")?></label>&nbsp;&nbsp;
                <input type="checkbox" name="PRINT" id="PRINT" <?if($PRINT=='1') echo "checked"; if($FW_FLAG=='1' && $PRINT!="1") echo " disabled";?>><label for="PRINT"><?=_("允许打印")?>Office<?=_("附件")?></label>&nbsp;&nbsp;&nbsp;<font color="gray"><?=_("都不选中则只能阅读附件内容")?></font>
            </td>
        </tr>

        <tr>
            <td nowrap class="TableData"><?=_("事务提醒：")?></td>
            <td class="TableData" id="sms_remind">
                <?if(find_id($SMS3_REMIND, 1)){?>
                    <input type="checkbox" name="SMS_REMIND1" id="SMS_REMIND1"<?if(find_id($SMS_REMIND,"1")) echo " checked";?>><label for="SMS_REMIND1"><?=_("发送事务提醒消息")?></label>&nbsp;
                <?}
                $query = "select * from SMS2_PRIV";
                $cursor=exequery(TD::conn(),$query);
                if($ROW=mysql_fetch_array($cursor))
                {
                    $TYPE_PRIV=$ROW["TYPE_PRIV"];
                    $SMS2_REMIND_PRIV=$ROW["SMS2_REMIND_PRIV"];
                }
                if(find_id($TYPE_PRIV,1)&& find_id($SMS2_REMIND_PRIV, $_SESSION["LOGIN_USER_ID"])) //检查该模块是否允许手机提醒
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
            <td nowrap class="TableData"> <?=_("置顶：")?></td>
            <td class="TableData">
                <input type="checkbox" name="TOP" id="TOP" <?if($TOP=="1") echo " checked";?>><label for="TOP"><?=_("使公告通知置顶，显示为重要")?></label>
                &nbsp;&nbsp;<input type="text" name="TOP_DAYS" size="3" maxlength="4" onkeyup="this.value=this.value.replace(/\D/g,'')" class="BigInput" value="<?if($TOP=="1"){ echo $TOP_DAYS;}else{echo $TOP_FLAG;}?>">&nbsp;<?=_("天后结束置顶，")?><?=$TOP_DAYS_REMIND?>
            </td>
        </tr>

        <tr>
            <td nowrap class="TableData"> <?=_("内容简介：")?></td>
            <td class="TableData">
                <input class="BigInput" type="text" name="SUMMARY" cols="46" rows="2" size=60 maxlength="30" value=<?=$SUMMARY?> >(<?=_("最多输入30个字")?>)
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
                <input type="text" name="KEYWORD" size="50" id="KEYWORD" value="<?=$KEYWORD?>" >  <span id='showKeyword' class='small1'></span><a href='javascript:get_keyword();' class='A1'><?=_("自动获取关键词")?></a>&nbsp;&nbsp;(<?=_("您可以调整关键词内容，多个关键词请用,分隔")?>)
            </td>
        </tr>
        <tr align="center" class="TableControl">
            <td colspan="2" nowrap>
                <input type="hidden" name="PUBLISH" value="">
                <input type="hidden" name="SUBJECT_COLOR" value="<?=$SUBJECT_COLOR?>">
                <input type="hidden" value="<?=$PUBLISH_OLD?>" name="PUBLISH_OLD">
                <input type="hidden" name="OP" value="">
                <input type="hidden" name="OP1" value="">
                <input type="hidden" value="<?=$NOTIFY_ID?>" class="BigButton" name="NOTIFY_ID" id="NOTIFY_ID">
                <input type="hidden" value="<?=$start?>" class="BigButton" name="start">
                <input type="hidden" value="<?=attach_sub_dir()?>" name="IMG_MODULE">
                <input type="hidden" value="<?=$IMG_YM?>" name="IMG_YM">
                <input type="hidden" value="<?=$IMG_ATTACHMENT_ID?>" name="IMG_ATTACHMENT_ID">
                <input type="hidden" value="<?=$IMG_ATTACHMENT_NAME?>" name="IMG_ATTACHMENT_NAME">
                <input type="hidden" value="<?=$IS_AUDITING_EDIT?>" name="IS_AUDITING_EDIT">
                <input type="hidden" value="<?=$FROM_ID?>" name="FROM_ID">
                <input type="hidden" name="OLD_SEND_TIME" value="<?=$SEND_TIME?>">
                <input type="hidden" name="TOP_FLAG" value="<?=$TOP_FLAG?>">
                <input type="hidden" name="FROM" value="<?=$FROM?>">
                <input type="hidden" name="READERS_OLD" value="<?=$READERS?>">
                <input type="hidden" name="POST_PRIV" value="<?=$POST_PRIV?>">

                <?
                if($FROM==1)//管理界面修改

                {
                    if ($NOTIFY_AUDITING_SINGLE_NEW!="")
                    {
                        if (find_id($NOTIFY_AUDITING_SINGLE_NEW, $TYPE_ID."-0") ||($TYPE_ID=="" && $NOTIFY_AUDITING_SINGLE!=1) || (!find_id($TYPE_ID_STR, $TYPE_ID) && find_id($NOTIFY_AUDITING_SINGLE_NEW, 'TYPE-0'))|| find_id($NOTIFY_AUDITING_EXCEPTION,$_SESSION["LOGIN_USER_ID"]))

                        {
                            ?>
                            <span id="BUTTON_ID"><input type="button" id="BUTTON" value="<?=_('发布')?>" class="BigButton" onClick="sendForm('1');">&nbsp;&nbsp;</span>
                            <?
                        }
                        else
                        {
                            ?>
                            <span id="BUTTON_ID"><input type="button" id="BUTTON" value="<?=_('提交审批')?>" class="BigButton" onClick="sendForm('2');">&nbsp;&nbsp;</span>
                            <?
                        }
                    }
                    else if ($NOTIFY_AUDITING_SINGLE_NEW=="")
                    {
                        if ($NOTIFY_AUDITING_SINGLE=="0")
                        {
                            ?>
                            <span id="BUTTON_ID"><input type="button" id="BUTTON" value="<?=_('发布')?>" class="BigButton" onClick="sendForm('1');">&nbsp;&nbsp;</span>
                            <?
                        }
                        else
                        {
                            ?>
                            <span id="BUTTON_ID"><input type="button" id="BUTTON" value="<?=_("提交审批")?>" class="BigButton" onClick="sendForm('2');">&nbsp;&nbsp;</span>
                            <?
                        }
                    }

                    ?>
                    <input type="hidden" name="PUBLISH_UPLOADATTACH" value="">
                    <input type="button" value="<?=_("保存")?>" class="BigButton" onClick="sendForm('0');">&nbsp;&nbsp;
                <?}
                else if($FROM==2)//审批人修改
                {

                    ?>
                    <input type="button" value="<?=_("发布")?>" class="BigButton" onClick="sendForm('4');">&nbsp;&nbsp;
                    <input type="button" value="<?=_("保存")?>" class="BigButton" onClick="sendForm('5');">&nbsp;&nbsp;
                    <input type="hidden" name="PUBLISH_UPLOADATTACH" value="5">
                    <?
                }
                ?>
                <!--<input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close()">-->

            </td>
        </tr>
    </table>
    <input type="hidden" name="FORMAT" id="FORMAT" value="0">
    <input type="hidden" name="FROM" value="<?=$FROM?>">
    <?
    //if ($FROM==1)
    if(($NOTIFY_AUDITING_SINGLE==1 && $NOTIFY_AUDITING_SINGLE_NEW=="") || !find_id($NOTIFY_AUDITING_EXCEPTION,$_SESSION["LOGIN_USER_ID"]) )
    {
        //{
        ?>
        <div id="overlay"></div>
        <div id="optionBlock" class="ModalDialog" style="display:none;width:350px;">
            <h4 class="header"><span id="optionBlockTitle" class="title"><?=_("提交审批")?></span><a class="operation" href="javascript:HideDialog('optionBlock');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/close.png"/></a></h4>
            <center>
                <table border="0px" style="font-size:9pt;padding:10px;line-height:23px;">
                    <tr><td>&nbsp;<?=_("审批人：")?></td>
                        <td><select name="AUDITER" class="BigSelect">
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
                                        if ($AUDITER_ID[$I]==$AUDITER)
                                            echo "<option value='$AUDITER_ID[$I]' selected>$AUDITER_NAME</option>";
                                        else
                                            echo "<option value='$AUDITER_ID[$I]'>$AUDITER_NAME</option>";
                                    }
                                }
                                $AUDITER_ID=explode(",",$AUDITER_ALL_ID);
                                for($I=0;$I<sizeof($AUDITER_ID);$I++)
                                {
                                    if($AUDITER_ID[$I]!="")
                                    {
                                        if(find_id($ROW["MANAGER"],$AUDITER_ID[$I]))
                                            continue;
                                        $AUDITER_NAME=GetUserNameById($AUDITER_ID[$I]);
                                        if ($AUDITER_ID[$I]==$AUDITER)
                                            echo "<option value='$AUDITER_ID[$I]' selected>$AUDITER_NAME</option>";
                                        else
                                            echo "<option value='$AUDITER_ID[$I]'>$AUDITER_NAME</option>";
                                    }
                                }
                            }
                            else
                            {
                                $AUDITER_ID=explode(",",$AUDITER_ALL_ID);
                                for($I=0;$I<sizeof($AUDITER_ID);$I++)
                                {
                                    if($AUDITER_ID[$I]!="")
                                    {
                                        $AUDITER_NAME=td_trim(GetUserNameById($AUDITER_ID[$I]));
                                        if ($AUDITER_ID[$I]==$AUDITER)
                                            echo "<option value='$AUDITER_ID[$I]' selected>$AUDITER_NAME</option>";
                                        else
                                            echo "<option value='$AUDITER_ID[$I]'>$AUDITER_NAME</option>";
                                    }
                                }
                            }
                            ?>
                        </select></td>
                    </tr>
                    <tr><td>&nbsp;<?=_("提醒审批人：")?></td><td><?=sms_remind(1);?></td></tr>
                    <tr><td>&nbsp;<td><tr>
                    <tr align="center">
                        <?
                        if($AUDITER !='' || count($AUDITER_ID)>0)
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