<?
//-----zfc-------
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_all.php");
//-------------------------------------------------------------------------------------

if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("PROJECT", 10);
if(!isset($start)||$start=="")
   $start=0;

$SORT_ID=intval($SORT_ID);

$HTML_PAGE_TITLE = _("项目文档");
include_once("inc/header.inc.php");
include_once("../proj/proj_priv.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/swfupload.css">

<body class="bodycolor">
<?
$VIEW_PRIV=$MANAGE_PRIV=$NEW_PRIV=$MODIFY_PRIV=0;

$query = "SELECT SORT_NAME,SORT_PARENT,VIEW_USER,MANAGE_USER,NEW_USER,MODIFY_USER from PROJ_FILE_SORT where SORT_ID='$SORT_ID' and ((find_in_set('".$_SESSION["LOGIN_USER_ID"]."',VIEW_USER)||find_in_set('".$_SESSION["LOGIN_USER_ID"]."',MANAGE_USER)||find_in_set('".$_SESSION["LOGIN_USER_ID"]."',MODIFY_USER)||find_in_set('".$_SESSION["LOGIN_USER_ID"]."',NEW_USER)) || '".$_SESSION["LOGIN_USER_ID"]."' = 'admin')";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $SORT_NAME=$ROW["SORT_NAME"];
   $SORT_PARENT=$ROW["SORT_PARENT"];
   $VIEW_USER=$ROW["VIEW_USER"];
   $MANAGE_USER=$ROW["MANAGE_USER"];
   $NEW_USER=$ROW["NEW_USER"];
   $MODIFY_USER=$ROW["MODIFY_USER"];

   $VIEW_PRIV= find_id($VIEW_USER,$_SESSION["LOGIN_USER_ID"])?"1":"0";
   $MANAGE_PRIV= find_id($MANAGE_USER,$_SESSION["LOGIN_USER_ID"])?"1":"0";
   $NEW_PRIV= find_id($NEW_USER,$_SESSION["LOGIN_USER_ID"])?"1":"0";
   $MODIFY_PRIV= find_id($MODIFY_USER,$_SESSION["LOGIN_USER_ID"])?"1":"0";

   $SORT_NAME=td_htmlspecialchars($SORT_NAME);
   $p = 1;
}

$query = "SELECT PROJ_VIEWER,PROJ_OWNER,PROJ_MANAGER,PROJ_LEADER from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$PROJ_VIEWER=$ROW["PROJ_VIEWER"];
	$PROJ_OWNER=$ROW["PROJ_OWNER"];
	$PROJ_MANAGER=$ROW["PROJ_MANAGER"];
	$PROJ_LEADER=$ROW["PROJ_LEADER"];
}

if($PROJ_OWNER==$_SESSION["LOGIN_USER_ID"] || $PROJ_MANAGER==$_SESSION["LOGIN_USER_ID"])
   $VIEW_PRIV=$MANAGE_PRIV=$NEW_PRIV=$MODIFY_PRIV=1;
if(find_id($PROJ_VIEWER,$_SESSION["LOGIN_USER_ID"]))
   $VIEW_PRIV=1;

$PASTE_FILE_PRIV = $NEW_PRIV && $_COOKIE["proj_sort_id"]!="" && $_COOKIE["proj_file_action"]!="" && $_COOKIE["proj_id"]!="" && $_COOKIE["proj_file_id"]!="";
if(!$p&&$_SESSION["LOGIN_USER_ID"]!="admin"&&$_SESSION["LOGIN_USER_ID"]!=$PROJ_OWNER&&$_SESSION["LOGIN_USER_ID"]!=$PROJ_LEADER)
{
    Message(_("错误"),_("您没有权限访问此文档目录！"));
    exit;
}


//=============================== 文件信息 ============================
$query = "SELECT count(*) from PROJ_FILE where SORT_ID='$SORT_ID'";
$cursor= exequery(TD::conn(),$query);
$FILE_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $FILE_COUNT=$ROW[0];

if($FILE_COUNT==0)
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><b><span class="Big1"> <?=$SORT_NAME?> </span></b><br>
    </td>
  </tr>
</table>

<br>

<?
   Message("",_("该目录尚无文件！"));
}
else
{
   $query = "SELECT FILE_ID,SUBJECT,UPDATE_TIME,ATTACHMENT_ID,ATTACHMENT_NAME,UPLOAD_USER from PROJ_FILE where SORT_ID='$SORT_ID'";
   if($FIELD=="")
      $query .= " order by UPDATE_TIME desc";
   else
   {
      $query .= " order by ".$FIELD;
      if($ASC_DESC=="1")
         $query .= " desc";
      else
         $query .= " asc";
   }
   $query .= " limit $start,$PAGE_SIZE";

   if($ASC_DESC=="1")
      $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_down.gif\" width=\"11\" height=\"10\">";
   else
      $ORDER_IMG="<img border=0 src=\"".MYOA_STATIC_SERVER."/static/images/arrow_up.gif\" width=\"11\" height=\"10\">";
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><b><span class="Big1"> <?=$SORT_NAME?> </span></b><br>
    </td>
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$FILE_COUNT,$PAGE_SIZE)?></td>
    </tr>
</table>

<table class="TableList" width="100%">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("选择")?></td>
      <td nowrap align="center" onclick="order_by('SUBJECT','<?if($FIELD=="SUBJECT") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("文件标题")?></u><?if($FIELD=="SUBJECT") echo $ORDER_IMG;?></td>
      <td nowrap align="center" onclick="order_by('SUBJECT','<?if($FIELD=="UPLOAD_USER") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("上传者")?></u><?if($FIELD=="UPLOAD_USER") echo $ORDER_IMG;?></td>
      <td nowrap align="center"><?=_("附件")?></td>
      <td nowrap align="center" onclick="order_by('UPDATE_TIME','<?if($FIELD=="UPDATE_TIME") echo 1-$ASC_DESC;else echo "1";?>');" style="cursor:hand;"><u><?=_("发布时间")?></u><?if($FIELD=="UPDATE_TIME") echo $ORDER_IMG;?></td>
<?
    if($MANAGE_PRIV==1 || $MODIFY_PRIV)
    {
?>
      <td nowrap align="center"><?=_("操作")?></td>
<?
    }
?>
  </tr>

<?
   $FILE_COUNT = 0;
   $cursor = exequery(TD::conn(), $query);
   while($ROW=mysql_fetch_array($cursor))
   {
     $FILE_COUNT++;

     $FILE_ID = $ROW["FILE_ID"];
     $UPLOAD_USER = $ROW["UPLOAD_USER"];
     $SUBJECT = $ROW["SUBJECT"];
     $UPDATE_TIME = substr($ROW["UPDATE_TIME"],0,-3);
     $ATTACHMENT_ID = $ROW["ATTACHMENT_ID"];
     $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"];

     $SUBJECT=td_htmlspecialchars($SUBJECT);

     $query1 = "SELECT USER_NAME FROM USER WHERE USER_ID='$UPLOAD_USER'";
     $cursor1 = exequery(TD::conn(),$query1);
     if($ROW=mysql_fetch_array($cursor1))
        $UPLOAD_USER_NAME = $ROW["USER_NAME"];

     if($FILE_COUNT%2==1)
        $TableLine="TableLine1";
     else
        $TableLine="TableLine2";
?>
    <tr class="<?=$TableLine?>">
      <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$FILE_ID?>" onClick="check_one(self);"></td>
      <td><a href="read.php?PROJ_ID=<?=$PROJ_ID?>&SORT_ID=<?=$SORT_ID?>&FILE_ID=<?=$FILE_ID?>&start=<?=$start?>"><?=$SUBJECT?></a></td>
      <td align="center" nowrap><?=$UPLOAD_USER_NAME?></td>
      <td><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,0,1,1,$MANAGE_PRIV,0)?></td>
      <td align="center"><?=$UPDATE_TIME?></td>
<?
      if($MODIFY_PRIV)
      {
?>
      <td align="center" nowrap>
          <a href="./new/?PROJ_ID=<?=$PROJ_ID?>&SORT_ID=<?=$SORT_ID?>&FILE_ID=<?=$FILE_ID?>&start=<?=$start?>"><?=_("编辑")?></a>
      </td>
<?
      }
?>
  </tr>
<?
   }

   if($FILE_COUNT>0)
   {
?>
  <tr class="TableControl">
    <td colspan="7">
     &nbsp;<input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for" style="cursor:hand"><?=_("全选")?></label>&nbsp;
     <a href="javascript:do_action('copy');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/copy.gif" align="absMiddle" border="0" title="<?=_("复制所选文件")?>"><?=_("复制")?></a>&nbsp;&nbsp;
<?
      if($MANAGE_PRIV==1)
      {
?>
     <a href="javascript:do_action('cut');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/cut.gif" align="absMiddle" border="0" title="<?=_("剪切所选文件")?>"><?=_("剪切")?></a>&nbsp;&nbsp;
     <a href="javascript:do_action('delete');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle" border="0" title="<?=_("删除所选文件")?>"><?=_("删除")?></a>&nbsp;&nbsp;
<?
      }
?>
    </td>
  </tr>
<?
   }
?>
</table>
<?
}
?>

<br>
<?
if($NEW_PRIV==1)
{
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/swfupload/swfupload.queue.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/swfupload/fileprogress.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/module/swfupload/handlers.js"></script>
<script type="text/javascript">
var upload_limit=oa_upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type=oa_limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
var swfupload;
window.onload = function() {
   upload_init();
};
</script>
<table class="TableBlock" width="100%" align="center">
  <tr>
    <td class="TableContent" nowrap align="center" width="80"><b><?=_("相关操作：")?></b></td>
    <td class="TableControl" valign="center">&nbsp;
    <span id="paste_file"  style="display:<?if(!$PASTE_FILE_PRIV) echo "none";?>;"  title="<?=_("粘贴文件")?>"><a  style="color:black;font-family:<?=_("宋体")?>;" href="paste_file.php?PROJ_ID=<?=$PROJ_ID?>&SORT_ID=<?=$SORT_ID?>&ORDER_BY=<?=$ORDER_BY?>&ASC_DESC=<?=$ASC_DESC?>&start=<?=$start?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/paste.gif" align="absMiddle" border="0"><?=_("粘贴")?></a>&nbsp;&nbsp;</span>
   <span><a href="new/?PROJ_ID=<?=$PROJ_ID?>&SORT_ID=<?=$SORT_ID?>" style="color:black;font-family:<?=_("宋体")?>;" title="<?=_("创建新的文件")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absMiddle" border="0">&nbsp;<?=_("新建文件")?></a></span>&nbsp;&nbsp;
        &nbsp;<span id="spanButtonUpload" title="<?=_("批量上传")?>"></span>&nbsp;
        <!--zfc-->
        <a class="ToolBtn" href="global_query.php?PROJ_ID=<?=_($PROJ_ID)?>" title="<?=_("全局搜索")?>"><span style="font-size:13px;"><?=_("全局搜索")?></span></a>
        
        <br />

    </td>

  </tr>
</table>
        <div id="fsUploadArea" class="flash" style="width:40%;text-align:left;">
          <div id="fsUploadProgress">&nbsp;</div>
          <div id="totalStatics"></div>

          <div>
            <br>
            <?//=_("提醒：")?><?//=sms_remind(42);?>
            <br>
            <br>
            <input type="button" id="btnStart" class="SmallButton" value="<?=_("开始上传")?>" onClick="startUpload()" disabled="disabled">&nbsp;&nbsp;
            <input type="button" id="btnCancel" class="SmallButton" value="<?=_("全部取消")?>" onClick="swfupload.cancelQueue();" disabled="disabled">&nbsp;&nbsp;
            <input type="button" class="SmallButton" value="<?=_("刷新页面")?>" onClick="window.location.reload();">
          </div>

        </div>


<?
}
?>
<script Language="JavaScript">
   function startUpload(){
    /*
      if($('SMS_REMIND').checked == true)
         swfupload.addPostParam('SMS_REMIND',1);
      else
    */
         swfupload.addPostParam('SMS_REMIND',0);
      
      swfupload.startUpload();
   }
   function upload_init() {
   var bkImage = "<?=MYOA_STATIC_SERVER?>/static/images/uploadx4.gif";

   
	   var settings = {
		flash_url : "<?=MYOA_JS_SERVER?>/module/swfupload/swfupload.swf",
		upload_url: "swfupload.php",
		post_params: {"PROJ_ID" : "<?=$PROJ_ID?>","SORT_ID" : "<?=$SORT_ID?>","FILE_SORT" : "<?=$FILE_SORT?>","URL" : "<?=$URL?>","ORDER_BY" : "<?=$ORDER_BY?>","ASC_DESC" : "<?=$ASC_DESC?>","start" : "<?=$start?>" ,"PHPSESSID" : "<?php echo session_id(); ?>"},
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
		button_image_url : bkImage,	// Relative to the SWF file
		button_text : "<span class=\"textUpload\"><?=_("批量上传")?></span>",
		//button_text_style : ".textUpload { color: "+color+"; }",
		//button_text_top_padding : (isNaN(paddingTop) ? 0: paddingTop),
		button_text_top_padding : 1,
		button_text_left_padding : 18,
		button_placeholder_id : "spanButtonUpload",
		button_width: 70,
		button_height: 18,

		//button_bottom_padding : 0,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,

		// The event handler functions are defined in handlers.js
		file_queued_handler : fileQueued,
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadComplete,
		queue_complete_handler : queueCompleteEventHandler	// Queue plugin event
	};
	
	swfupload = new SWFUpload(settings);
}
function queueCompleteEventHandler() { 
    window.location = "folder.php?PROJ_ID=<?=$PROJ_ID?>&SORT_ID=<?=$SORT_ID?>";
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
    URL="delete_attach.php?PROJ_ID=<?=$PROJ_ID?>&SORT_ID=<?=$SORT_ID?>&FILE_ID=<?=$FILE_ID?>&start?>=<?=$start?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
    window.location=URL;
  }
}

function check_all()
{
 for (i=0;i<document.getElementsByName("email_select").length;i++)
 {
   if(document.getElementsByName("allbox")[0].checked)
      document.getElementsByName("email_select")[i].checked=true;
   else
      document.getElementsByName("email_select")[i].checked=false;
 }

 if(i==0)
 {
   if(document.getElementsByName("allbox")[0].checked)
      document.getElementsByName("email_select")[0].checked=true;
   else
      document.getElementsByName("email_select")[0].checked=false;
 }
}

function check_one(el)
{
   if(!el.checked && document.getElementsByName("allbox")[0])
      document.getElementsByName("allbox")[0].checked=false;
}
function get_checked()
{
  checked_str="";
  for(i=0;i<document.getElementsByName("email_select").length;i++)
  {

      el=document.getElementsByName("email_select")[i];
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName("email_select")[0];
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
  }
  return checked_str;
}
<?
if($RELOAD_ID!="" && $RELOAD_ACTION!="")
{
?>
if(parent.file_tree.xtree1 && parent.file_tree.xtree1.XmlSrc.substr(0,8)=="tree.php")
{
<?
   if($CUT_ID!="" && $RELOAD_ACTION=="cut")
   {
?>
   parent.file_tree.xtree1.redrawNode("<?=$CUT_ID?>", "<?=$RELOAD_ACTION?>","<?=$SORT_NAME?>");
<?
   }
?>
   parent.file_tree.xtree1.redrawNode("<?=$RELOAD_ID?>", "<?=$RELOAD_ACTION?>","<?=$SORT_NAME?>");
}
<?
}
?>
function do_action(action)
{
   delete_str=get_checked();
   var id_array=delete_str.split(",");
   var count=id_array.length-1;
   if(count <= 0)
   {
      alert("<?=_("请至少选择一个文件")?>");
      return;
   }

   switch(action)
   {
      case "copy":
      case "cut":
          document.cookie = "proj_sort_id=<?=$SORT_ID?>";
          document.cookie = "proj_file_id="+delete_str;
          document.cookie = "proj_file_action="+action;
          document.cookie = "proj_id=<?=$PROJ_ID?>";
<?
if($NEW_PRIV)
{
?>
          document.getElementById("paste_file").style.display='';
          document.getElementById("paste_file").title="<?=_("粘贴所选文件")?>";
<?
}
?>
          if(action == "copy")
             alert("<?=_("选择的文件已“复制”")?>\n<?=_("请到目标目录中进行“粘贴”操作")?>");
          else
             alert("<?=_("选择的文件已“剪切”")?>\n<?=_("请到目标目录中进行“粘贴”操作")?>");
          break;
      case "delete":
          if(window.confirm("<?=_("确定要删除选择文件吗？这将不可恢复！")?>"))
             location="delete.php?PROJ_ID=<?=$PROJ_ID?>&SORT_ID=<?=$SORT_ID?>&start=<?=$start?>&FILE_ID=" + delete_str;
          break;
   }
}

function order_by(field,asc_desc)
{
 window.location="folder.php?PROJ_ID=<?=$PROJ_ID?>&SORT_ID=<?=$SORT_ID?>&start=<?=$start?>&FIELD="+field+"&ASC_DESC="+asc_desc;
}
//------zfc-----------
var saveFile = false;
function SaveFile(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  if(saveFile)
    saveFile.close();
  URL="/module/save_file/?ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+ATTACHMENT_NAME+"&A=1";
  loc_x=screen.availWidth/2-200;
  loc_y=screen.availHeight/2-90;
  saveFile = window.open(URL,null,"height=180,width=400,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes");
}
</script>
</body>
</html>