<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/editor.php");

$HTML_PAGE_TITLE = _("新建文档");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script Language="JavaScript">
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function CheckForm()
{
   if(document.form1.SUBJECT.value=="")
   { alert("<?=_("文件名称不能为空！")?>");
     return false;
   }

   document.form1.OP.value="";

   return true;
}

function InsertImage(src)
{
   AddImage2Editor('FILE_DESC', src);
}
function upload_attach()
{
   var file_name="";
   if (document.form1.ATTACHMENT.value!="" && document.form1.SUBJECT.value=="")
   {
     var Pos,file_temp=document.form1.ATTACHMENT.value;
     Pos=file_temp.lastIndexOf("\\");
     file_name=file_temp.substring(Pos+1,file_temp.length);
     document.form1.SUBJECT.value=file_name;
   }
   
   if(CheckForm())
   {
     document.form1.OP.value=1;
     document.form1.submit();
   }
}

function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
  var msg = sprintf("<?=_("确定要删除文件 '%s' 吗？")?>", ATTACHMENT_NAME);
  if(window.confirm(msg))
  {
    URL="../delete_attach.php?PROJ_ID=<?=$PROJ_ID?>&SORT_ID=<?=$SORT_ID?>&FILE_ID=<?=$FILE_ID?>&start?>=<?=$start?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
    window.location=URL;
  }
}

function new_attach()
{
   if(document.form1.NEW_TYPE.value=="")
   { alert("<?=_("请选择文件类型！")?>");
     return (false);
   }
   if(document.form1.NEW_NAME.value=="")
   { alert("<?=_("附件名不能为空！")?>");
     return (false);
   }
   if(document.form1.SUBJECT.value=="")
      document.form1.SUBJECT.value=document.form1.NEW_NAME.value;
   if(!CheckForm())
      return false;
   document.form1.OP.value=1;
   document.form1.submit();
}
</script>


<body class="bodycolor">

<?
//============================ 文件信息 =======================================
if($FILE_ID!="")
{
  $query = "SELECT * from PROJ_FILE where FILE_ID='$FILE_ID'";
  $cursor= exequery(TD::conn(),$query);

  if($ROW=mysql_fetch_array($cursor))
  {
     $SORT_ID=$ROW["SORT_ID"];
     $SUBJECT=$ROW["SUBJECT"];
     $FILE_DESC=$ROW["FILE_DESC"];  
     $ATTACHMENT_ID = $ROW["ATTACHMENT_ID"];
     $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"];
     $VERSION = $ROW["VERSION"];
  }
}

//--- 安全性 ---
$query = "SELECT PROJ_OWNER,PROJ_MANAGER from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$PROJ_OWNER=$ROW["PROJ_OWNER"];
	$PROJ_MANAGER=$ROW["PROJ_MANAGER"];
}
	
// $query = "SELECT NEW_USER from PROJ_FILE_SORT where SORT_ID='$SORT_ID'";
// $cursor= exequery(TD::conn(),$query);
// if($ROW=mysql_fetch_array($cursor))
// {
    // $NEW_USER=$ROW["NEW_USER"];
    // if(!find_id($NEW_USER,$_SESSION["LOGIN_USER_ID"]) && $PROJ_OWNER!=$_SESSION["LOGIN_USER_ID"] && $PROJ_MANAGER!=$_SESSION["LOGIN_USER_ID"])
       // exit;
// }
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><b><span class="Big1"> <?=_("新建文件")?></span></b>
    </td>
  </tr>
</table>

<form enctype="multipart/form-data" action="submit.php"  method="post" name="form1" onSubmit="return CheckForm();">
 <table class="TableBlock" width="100%" align="center">
    <tr>
      <td nowrap class="TableData"> <?=_("文件名称：")?></td>
      <td class="TableData">
        <input type="text" name="SUBJECT" size="50" maxlength="100" class="BigInput" value="<?=$SUBJECT?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" valign="top"> <?=_("文件内容：")?></td>
      <td class="TableData">
<?
$editor = new Editor('FILE_DESC');
$editor->ToolbarSet = 'Basic';
$editor->Config = array('model_type' => '10');
$editor->Value = $FILE_DESC ;
$editor->Create() ;
?>
      </td>
    </tr>
    <tr class="TableContent">
      <td nowrap><?=_("附件文档：")?></td>
      <td nowrap><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1)?></td>
    </tr>
    <tr height="25">
      <td nowrap class="TableData"><?=_("新建附件：")?></td>
      <td class="TableData">
         <input type="radio" onClick="document.form1.NEW_TYPE.value='doc'" name="DOC_TYPE" id="NEW_TYPE1"><label for="NEW_TYPE1">Word<?=_("文档")?></label>
         <input type="radio" onClick="document.form1.NEW_TYPE.value='xls'" name="DOC_TYPE" id="NEW_TYPE2"><label for="NEW_TYPE2">Excel<?=_("文档")?></label>
         <input type="radio" onClick="document.form1.NEW_TYPE.value='ppt'" name="DOC_TYPE" id="NEW_TYPE3"><label for="NEW_TYPE3">PPT<?=_("文档")?></label>&nbsp;&nbsp;
         <b><?=_("附件名：")?></b><input type="text" name="NEW_NAME" size="20" class="SmallInput" value="<?=_("新建文档")?>">
         <input type="button" class="SmallButton" value="<?=_("新建附件")?>" onClick="new_attach();">
         <input type="hidden" value="" name="NEW_TYPE">
<?
if($ATTACHMENT_ID_OFFICE!="" && $ATTACHMENT_NAME_OFFICE!="")
{
   $OFFICE_EDIT_CODE = urlencode(td_authcode("4", "ENCODE", md5($ATTACHMENT_NAME_OFFICE)));
?>
<script>window.open("/module/OC/?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_OFFICE?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME_OFFICE)?>&OP_CODE=<?=$OFFICE_EDIT_CODE?>",'CONTENT_<?=$FILE_ID?>','menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1');</script>
<?
}
?>
      </td>
    </tr>
    <tr height="25">
      <td nowrap class="TableData"><?=_("附件选择：")?></td>
      <td class="TableData">
         <script>ShowAddFile();</script>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("附件说明：")?></td>
      <td class="TableData">
        <input type="text" name="ATTACHMENT_DESC" size="50" maxlength="50" class="BigInput" value="<?=$ATTACHMENT_DESC?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("提醒：")?></td>
      <td class="TableData">
<?=sms_remind(42);?>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="hidden" value="<?=$FILE_ID?>" name="FILE_ID">
        <input type="hidden" name="OP" value="">
        <input type="hidden" name="ATTACHMENT_ID_OLD" value="<?=$ATTACHMENT_ID?>">
        <input type="hidden" name="ATTACHMENT_NAME_OLD" value="<?=$ATTACHMENT_NAME?>">
        <input type="hidden" name="SORT_ID" value="<?=$SORT_ID?>" >
        <input type="hidden" name="PROJ_ID" value="<?=$PROJ_ID?>" >
        <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='../folder.php?PROJ_ID=<?=$PROJ_ID?>&SORT_ID=<?=$SORT_ID?>'">
      </td>
    </tr>
  </table>
</form>

</body>
</html>