<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/editor.php");
include_once("inc/utility_org.php");

//2013-04-11 
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$FONT_SIZE = get_font_size("FONT_NEWS", 12);

$a_para_array = get_sys_para("SMS_REMIND");
$s_sms_remind_str = $a_para_array["SMS_REMIND"];
$remind_array = explode("|", $s_sms_remind_str);
$sms_remind = $remind_array[0];
$sms2_remind = $remind_array[1];
$sms3_remind = $remind_array[2];
$sms4_remind = $remind_array[3];
$sms5_remind = $remind_array[4];

$HTML_PAGE_TITLE = _("编辑新闻");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/swfupload.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/module/upload/uploader.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script language="javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/mytable.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/swfupload/swfupload.queue.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/swfupload/fileprogress.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/swfupload/handlers.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/upload/uploader.js"></script> 
<script language="javascript" src="<?=MYOA_JS_SERVER?>/static/js/tabopt.js"></script>
<script Language="JavaScript">
confirmSaveBeforeCloseTab("new_modify", "", "");	
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
   if(document.form1.TO_ID.value==""&&document.form1.PRIV_ID.value==""&&document.form1.COPY_TO_ID.value=="")
   { 
      alert("<?=_("请指定发布范围！")?>");
      return (false);
   }

   if(document.form1.SUBJECT.value.trim()=="")
   { 
      alert("<?=_("新闻的标题不能为空！")?>");
      return (false);
   }
   if (is_moz==false)
   {
      if(getEditorText('CONTENT').length==0 && getEditorHtml('CONTENT')=="" && document.form1.ATTACHMENT_ID_OLD.value==""&& document.form1.FORMAT.value=="0")
      { 
         alert("<?=_("新闻的内容不能为空！")?>");
         return (false);
      }
   }
   else
   {
      if(getEditorHtml('CONTENT')=="<br>"  && document.form1.ATTACHMENT_ID_OLD.value==""&& document.form1.FORMAT.value=="0")
      { 
         alert("<?=_("新闻的内容不能为空！")?>");
         return (false);
      }
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
   if(document.form1.FORMAT.value=="2" && document.form1.URL_ADD.value=="")
   {
      alert("<?=_("请填写超级链接地址！")?>");
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
   document.form1.OP.value="1";
   document.form1.PUBLISH.value=publish;
   if(CheckForm())
      document.form1.submit();
}

function upload_attach()
{
   document.form1.PUBLISH.value="0";
   if(CheckForm())
   {
      document.form1.OP.value="0";
      document.form1.submit();
   }
}
function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
   var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
   if(window.confirm(msg))
   {
      URL="delete_attach.php?NEWS_ID=<?=$NEWS_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
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
      document.getElementById("attachment2").style.display="";
      document.getElementById("url_address").style.display="none";
      document.getElementById("ATTACH_LABEL").innerHTML="<?=_("MHT文件(或附件)：")?>";
      document.getElementById("FORMAT").value="1";
      document.getElementById("status1").innerText="<?=_("MHT格式")?>";  //innerText
      if(document.getElementById("add_image"))
         document.getElementById("add_image").style.display="none";
      if(document.getElementById("add_image_multi"))
         document.getElementById("add_image_multi").style.display="none";
   }
   else if(typeID=="0")
   {
      document.getElementById("EDITOR").style.display="";
      document.getElementById("tr_KEYWORD").style.display="";      
      document.getElementById("attachment1").style.display="";
      document.getElementById("attachment2").style.display="";
      document.getElementById("url_address").style.display="none";
      document.getElementById("ATTACH_LABEL").innerHTML="<?=_("附件：")?>";
      document.getElementById("FORMAT").value="0";
      document.getElementById("status1").innerText="<?=_("普通格式")?>";
      if(document.getElementById("add_image"))
         document.getElementById("add_image").style.display="";
      if(document.getElementById("add_image_multi"))
         document.getElementById("add_image_multi").style.display="";
   }
   else if(typeID=="2")
   {
      document.getElementById("EDITOR").style.display="none";
      document.getElementById("tr_KEYWORD").style.display="none";      
      document.getElementById("attachment1").style.display="none";
      document.getElementById("attachment2").style.display="none";
      document.getElementById("url_address").style.display="";
      document.getElementById("FORMAT").value="2";
      document.getElementById("status1").innerText="<?=_("超级链接")?>";
   }
}

function get_keyword()
{
   var txtCONTENT1=getEditorText('CONTENT');
   var txtCONTENT=txtCONTENT1.substring(0,4234);
   if(txtCONTENT=="")
   {
      alert("<?=_("获取关键词前请先输入新闻内容。")?>");
      return;	
   }
   document.getElementById("showKeyword").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/loading.gif' height='20' width='20' align='absMiddle'> <?=_("正在搜索本文关键词……")?>";
   var httpReq=getXMLHttpObj();
   <?
    if(MYOA_IS_UN==1){     
   ?>  
    txtCONTENT=encodeURIComponent(txtCONTENT);  
   <?
   }
   ?>
   httpReq.open("GET","/inc/get_keyword.php?MODULE_ID=NEWS&CONTENT="+txtCONTENT+"");
   httpReq.onreadystatechange=function(){
      if(httpReq.readyState==4){
      	document.form1.KEYWORD.value=httpReq.responseText;
         document.getElementById("showKeyword").innerHTML="";
      }
  };
   httpReq.send(null);
    
}
var upload_limit=oa_upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type=oa_limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
var swfupload;

function upload_init(){
  newsId=document.getElementById("NEWS_ID").value;
  

	var settings = {
		flash_url : "<?=MYOA_JS_SERVER?>/module/swfupload/swfupload.swf",
		upload_url:"upload_attach_batch.php?NEWS_ID="+newsId,
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

	var notifyId=document.getElementById("NEWS_ID").value;
   var subject=document.form1.SUBJECT.value;
	var subjectColor=document.form1.SUBJECT_COLOR.value;
   var format=document.form1.FORMAT.value;
   var toId=document.form1.TO_ID.value;
   var copyToId=document.form1.COPY_TO_ID.value;
   var privId=document.form1.PRIV_ID.value;
   var content = getEditorHtml('CONTENT');
   var anonymityYn=document.form1.ANONYMITY_YN.value;
   var sendTime=document.form1.SEND_TIME.value;
   

	if(document.getElementById("TOP") && document.getElementById("TOP").checked)
	{
      var top="on";
	}
	else
	{
      var top="";
	}		

  var summary=document.form1.SUMMARY.value;
  var typeId=document.form1.TYPE_ID.value;
  var keyWord=document.getElementById("KEYWORD").value;
  var publish=document.form1.PUBLISH.value;

	
	swfupload.addPostParam("NEWS_ID",notifyId);
	swfupload.addPostParam("SUBJECT",subject);
	swfupload.addPostParam("SUBJECT_COLOR",subjectColor);
	swfupload.addPostParam("FORMAT",format);
	swfupload.addPostParam("TO_ID",toId);
	swfupload.addPostParam("COPY_TO_ID",copyToId);
	swfupload.addPostParam("PRIV_ID",privId);
	swfupload.addPostParam("PUBLISH",publish);
	swfupload.addPostParam("TOP",top);
	swfupload.addPostParam("TD_HTML_EDITOR_CONTENT", content);
	swfupload.addPostParam("SUMMARY",summary);
	swfupload.addPostParam("TYPE_ID",typeId);
	swfupload.addPostParam("ANONYMITY_YN",anonymityYn);
	swfupload.addPostParam("KEYWORD",keyWord);

	return true;
}

function uploadSuccessEventHandler(file, server_data) {

   var newsId="";
	if (server_data) {

		//按照server_data的格式解析出三个变量，格式形如：BODY_ID:22,FW:33,REPLAY:44
		newsId=server_data.split(":")[1];

	}

	document.getElementById("NEWS_ID").value = newsId;

}

//整个队列上传成功之后，执行这个函数 by dq 090609
function queueCompleteEventHandler() {
  var bodyIdRet = document.getElementById("NEWS_ID").value;  
	window.location = "modify.php?NEWS_ID=" + bodyIdRet+"&IS_MAIN=1";
}

</script>

<?	
$query = "SELECT POST_PRIV FROM USER where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $POST_PRIV=$ROW["POST_PRIV"];
}

$query="select * from NEWS where NEWS_ID='$NEWS_ID'";
if($_SESSION["LOGIN_USER_PRIV"]!="1" &&$POST_PRIV!="1")
   $query.=" and PROVIDER='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
{
   $PROVIDER=$ROW["PROVIDER"];
   $SUBJECT=$ROW["SUBJECT"];
   $SUMMARY=$ROW["SUMMARY"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   $ANONYMITY_YN=$ROW["ANONYMITY_YN"];
   $FORMAT=$ROW["FORMAT"];
   $TYPE_ID=$ROW["TYPE_ID"];
   $PUBLISH_OLD=$ROW["PUBLISH"];
   $TO_ID=$ROW["TO_ID"];
   $PRIV_ID=$ROW["PRIV_ID"];
   $USER_ID=$ROW["USER_ID"];
   $SEND_TIME=$ROW["NEWS_TIME"];
   $TOP=$ROW["TOP"];
   $SUBJECT_COLOR=$ROW["SUBJECT_COLOR"];
   $KEYWORD=td_htmlspecialchars($ROW["KEYWORD"]);
   $TOP_DATE = $ROW['TOP_DAYS'];
   if($TOP_DATE == '0000-00-00 00:00:00' || $TOP_DATE == '2038-01-01 00:00:00')
   {
        $TOP_DAYS = '0';
       
   }else
   {
        $TOP_DAYS = (strtotime(date('Y-m-d',strtotime($TOP_DATE)))-strtotime(date('Y-m-d',time())))/(24*60*60);
   }
   $COMPRESS_CONTENT=@gzuncompress($ROW["COMPRESS_CONTENT"]);
   if($COMPRESS_CONTENT!=""&&$FORMAT!=2)
      $CONTENT=$COMPRESS_CONTENT;
   else
      $CONTENT=$ROW["CONTENT"];

    //安全保护机制-add by gao
   if($PROVIDER!=$_SESSION["LOGIN_USER_ID"] && $_SESSION["LOGIN_USER_PRIV"]!=1 && $POST_PRIV!=1)
      exit;
   
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

$CONTENT=str_replace("\"","'",$CONTENT);
$CONTENT=str_replace(chr(10),"",$CONTENT);
$CONTENT=str_replace(chr(13),"",$CONTENT);

if($TO_ID=="ALL_DEPT")
   $TO_NAME=_("全体部门");
else
   $TO_NAME=GetDeptNameById($TO_ID);
   
$PRIV_NAME=GetPrivNameById($PRIV_ID);

$USER_NAME=GetUserNameById($USER_ID);
?>

<script Language="JavaScript">
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
   document.form1.SEND_TIME.value="<?=date("Y-m-d H:i:s",time())?>";
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
   upload_init();
   
}
</script>
<body class="bodycolor" onLoad="changeFormat('<?=$FORMAT?>');form1.SUBJECT.focus();Load();">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("编辑新闻")?></span>&nbsp;&nbsp;
     <a id="status" href="javascript:;" class="dropdown" onClick="showMenu(this.id, 1);" hidefocus="true"><span id="status1"><?=$STATUS_DESC?></span></a>&nbsp;
      <div id="status_menu" class="attach_div" >
      <a href="javascript:changeFormat(0);" style="color:#0000FF;"><?=_("普通格式")?></a>
      <a href="javascript:changeFormat(1);" style="color:#0000FF;" title="<?=_("mht格式支持图文混排，Word文档可以直接另存为mht文件。")?>"><?=_("MHT格式")?></a>
      <a href="javascript:changeFormat(2);" style="color:#0000FF;"><?=_("超级链接")?></a>
   </div>
    </td>
  </tr>
</table>

<form enctype="multipart/form-data" action="update_query.php"  method="post" name="form1">
<table class="TableBlock" width="85%" align="center">
    <tr>
      <td nowrap class="TableData"><select name="TYPE_ID" style="background: white;" title="<?=_("新闻类型可在“系统管理”->“系统代码设置”模块设置。")?>">
          <option value=""<?if($TYPE_ID=="") echo " selected";?>><?=_("选择新闻类型")?></option>
          <?=code_list("NEWS",$TYPE_ID)?>
        </select></td>
      <td class="TableData">
        <input type="text" name="SUBJECT" id="SUBJECT" size="55" maxlength="200" class="BigInput" value="<?=td_htmlspecialchars($SUBJECT)?>" style="color:<?=$SUBJECT_COLOR=="#FFFFFF" ? "" : $SUBJECT_COLOR?>;"><font color="red">(*)</font>
        <a id="font_color_link" href="javascript:;" class="dropdown" onClick="showMenu(this.id, 1);" hidefocus="true" ><span><?=_("设置标题颜色")?></span></a>&nbsp;&nbsp;
            <div id="font_color_link_menu" style="display:none;">
            </div>
      </td>
    </tr>
    <tr id="rang_dept" <?if($TO_ID=="" && $ALL_BLAMK!="1") echo "style='display:none'";?>>
      <td nowrap class="TableData"><?=_("按部门发布：")?><?=$href_dept?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
        <textarea cols=40 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('6')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
        <font color="red">(*)</font>
      </td>
    </tr>
    <tr id="rang_user" <? if($USER_ID=="") echo "style='display:none;'";?>>
      <td nowrap class="TableData"><?=_("按人员发布：")?><?=$href_user?></td>
      <td class="TableData">
        <input type="hidden" name="COPY_TO_ID" value="<?=$USER_ID?>">
        <textarea cols=40 name="COPY_TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$USER_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('105','6','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
      </td>
   </tr>
    <tr id="rang_role" <? if($PRIV_ID=="") echo "style='display:none;'";?>>
      <td nowrap class="TableData"><?=_("按角色发布：")?><?=$href_priv?></td>
      <td class="TableData">
        <input type="hidden" name="PRIV_ID" value="<?=$PRIV_ID?>">
        <textarea cols=40 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$PRIV_NAME?></textarea>       
        <a href="javascript:;" class="orgAdd" onClick="SelectPriv('6','PRIV_ID', 'PRIV_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("清空")?></a><br>
        <?=_("发布范围取部门、人员和角色的并集")?>
      </td>
   </tr>
   
    <tr id="url_address" style="display:none">
      <td nowrap class="TableData"> <?=_("超级链接地址：")?></td>
      <td class="TableData">
        <input type="text" name="URL_ADD" size="55" maxlength="500" class="BigInput" value="<?=$CONTENT?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("发布时间：")?></td>
      <td class="TableData">
        <input type="text" name="SEND_TIME" size="20" maxlength="20" class="BigInput" value="<?=$SEND_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        &nbsp;&nbsp;<a href="javascript:resetTime();"><?=_("重置为当前时间")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("评论：")?></td>
      <td class="TableData">
        <select name="ANONYMITY_YN" class="BigSelect">
          <option value="0" <?if($ANONYMITY_YN=="0") echo " selected";?>><?=_("实名评论")?></option>
          <option value="1" <?if($ANONYMITY_YN=="1") echo " selected";?>><?=_("匿名评论")?></option>
          <option value="2" <?if($ANONYMITY_YN=="2") echo " selected";?>><?=_("禁止评论")?></option>
        </select>&nbsp;
      </td>
    </tr>
    <tr class="TableData" id="attachment2">
      <td nowrap><?=_("附件文档：")?></td>
      <td><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1)?></td>
    </tr>
    <tr height="25" id="attachment1">
      <td nowrap class="TableData"><span id="ATTACH_LABEL"><?=_("附件上传：")?></span></td>
      <td class="TableData">
        <script>ShowAddFile();ShowAddImage();ShowAddImageMulti();</script>
        <script>$("ATTACHMENT_upload_div").innerHTML='<a href="javascript:upload_attach();"><?=_("上传附件")?></a>'</script>&nbsp;
        <a class="add_swfupload" href="javascript:void(0)"><?=_("批量上传")?><span id="spanButtonUpload" title="<?_("批量上传附件")?>"></span></a>
        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("提醒：")?></td>
      <td class="TableData">
<?
sms_remind(14);

if(find_id($sms4_remind, '14'))
{
    echo "<label class='sms-remind-label'><input type=\"checkbox\" name=\"SNS_REMIND\" id=\"SNS_REMIND\"";
    if(find_id($sms5_remind, '14'))
        echo " checked";
    echo ">"._("分享到企业社区")."</label>";
}
?>
      </td>
    </tr>
     <tr>
      <td nowrap class="TableData">&nbsp;<?=_("置顶：")?></td>
      <?
        $sql3="SELECT TOP FROM NEWS WHERE NEWS_ID = '$NEWS_ID'";
        $cursor1 = exequery(TD::conn(),$sql3);
        if($ROW=mysql_fetch_array($cursor1))
        {
             $TOP1 = $ROW['TOP'];
        }
      ?>
      
      <td class="TableData"><input type="checkbox" name="TOP" id="TOP" <? if($TOP1 == 1){echo 'checked';}else{echo'';}?>><label for="TOP"><?=_("使新闻置顶，显示为重要")?></label>&nbsp;&nbsp;
      <input type="text" name="TOP_DAYS" size="3" maxlength="4" onkeyup="this.value=this.value.replace(/\D/g,'')" class="BigInput" value="<?=$TOP_DAYS?>"><?="天后结束置顶，0表示一直置顶"?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("内容简介：")?></td>
      <td class="TableData">
      	<input class="BigInput" name="SUMMARY" cols="46" rows="2" class="SmallInput" size=60 maxlength="30" value="<?=$SUMMARY?>">(<?=_("最多30个字")?>)
      </td>
    </tr>
    <tr id="EDITOR">
      <td class="TableData" colspan="2">
<?
$editor = new Editor('CONTENT') ;
$editor->Height = '450';
$editor->Config = array("contentsCss" => "body{font-size:".$FONT_SIZE."pt;}","model_type" =>"05");
$editor->Value = $CONTENT ;
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
        <input type="hidden" name="FORMAT" id="FORMAT" value="0">
        <input type="hidden" value="<?=$PUBLISH_OLD?>" name="PUBLISH_OLD">
        <input type="hidden" name="OP" value="">
        <input type="hidden" value="<?=$NEWS_ID?>" name="NEWS_ID">
        <input type="hidden" value="<?=$start?>" name="start">
        <input type="hidden" value="<?=attach_sub_dir()?>" name="IMG_MODULE">
        <input type="hidden" value="<?=$IMG_YM?>" name="IMG_YM">
        <input type="hidden" name="SUBJECT_COLOR" value="<?=$SUBJECT_COLOR?>">
        <input type="hidden" value="<?=$IMG_ATTACHMENT_ID?>" name="IMG_ATTACHMENT_ID">
        <input type="hidden" value="<?=$IMG_ATTACHMENT_NAME?>" name="IMG_ATTACHMENT_NAME">
        <input type="hidden" id="NEWS_ID" value="<?=$NEWS_ID?>">
        <input type="button" value="<?=_("发布")?>" class="BigButton" onClick="sendForm('1');">&nbsp;&nbsp;
        <input type="button" value="<?=_("保存")?>" class="BigButton" onClick="sendForm('0');">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='search.php'">
      </td>
    </tr>
  </table>
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